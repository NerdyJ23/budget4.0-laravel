<script setup lang="ts">
import { computed, reactive } from 'vue';
import { TableItem, TableSort } from '@/types/table';
import LoadingDialog from '@/Components/LoadingDialog.vue';

const props = withDefaults(defineProps<{
	count?: number,
	title?: string
	headers: Array<string>,
	items: TableItem[],
	loading: boolean
}>(), {
	count: 5,
	title: 'Items'
});

const sort: TableSort = reactive({
	key: null,
	direction: 'asc'
})
const itemList = computed(() => props.items.slice(0, props.count));
</script>
<template>
	<div v-bind="$attrs" :key="title">
		<template v-if="!loading">
			<span class="font-semibold text-lg">Top {{ props.count }} {{ props.title }}</span>
			<div :class="`grid grid-cols-${headers.length} gap-x-2 gap-y-1 bg-slate-400 text-center`">
				<span v-for="header in headers" class="font-semibold text-md capitalize">{{ header }}</span>
			</div>
			<div v-if="itemList.length != 0" :class="`grid grid-cols-${headers.length} gap-x-2 gap-y-1 text-sm`">
				<template v-for="item in itemList" :key="item.key">
					<template v-for="(value, key, index) in item.item">
						<span :class="`capitalize pl-2 ${index != 0 ? 'text-center' : ''}`">{{ value }}</span>
					</template>
				</template>
			</div>
			<div v-else class="w-full bg-slate-200 italic text-center">
				No Data
			</div>
		</template>
		<template v-else>
			<LoadingDialog />
		</template>
	</div>
</template>