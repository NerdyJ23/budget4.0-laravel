<script setup lang="ts">
import { computed, reactive } from 'vue';
import { TableItem, TableSort } from '@/types/table';

const props = withDefaults(defineProps<{
	count?: number,
	title?: string
	headers: Array<string>,
	items: TableItem[]
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
	<div v-bind="$attrs">
		<span class="font-semibold text-lg">Top {{ props.count }} {{ props.title }}</span>
		<div :class="`grid grid-cols-${headers.length} gap-x-2 gap-y-1 bg-slate-400 text-center`">
			<span v-for="header in headers" class="font-semibold text-md capitalize">{{ header }}</span>
		</div>
		<div :class="`grid grid-cols-${headers.length} gap-x-2 gap-y-1 text-sm`">
			<template v-for="item in itemList" :key="item.key">
				<template v-for="(value, key, index) in item.item">
					<span :class="`capitalize pl-2 ${index != 0 ? 'text-center' : ''}`">{{ value }}</span>
				</template>
			</template>
		</div>
	</div>
</template>