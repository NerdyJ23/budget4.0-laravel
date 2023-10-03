<script setup lang="ts">
import { computed, reactive, onMounted, nextTick } from 'vue';
import { TableHeader, TableItem, TableSort } from '@/types/table';
import { BiArrowDownShort, BiArrowUpShort } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";


const props = withDefaults(defineProps<{
	count?: number,
	title?: string
	headers: Array<TableHeader>,
	items: TableItem[],
	sort?: TableSort
}>(), {
	count: 5
});

const gridCols = [
	'grid-cols-1',
	'grid-cols-2',
	'grid-cols-3',
	'grid-cols-4',
	'grid-cols-5',
	'grid-cols-6',
	'grid-cols-7',
	'grid-cols-8',
	'grid-cols-9',
	'grid-cols-10',
	'grid-cols-11',
	'grid-cols-12',
]; //Need to use full class names due to Tailwind not supporting dynamic class generation even with a safelist

let items: Array<TableItem> = reactive(props.items);
const sort: TableSort = reactive({
	key: props.sort?.key ?? null,
	direction: props.sort?.direction ?? 'desc'
})
const tableId = crypto.randomUUID();

const itemList = computed(() => {
	if (sort.direction === 'asc') {
		return items.slice(-props.count).reverse();
	} else {
		return items.slice(0, props.count);
	}
});

const changeSort = (item: TableHeader) => {
	if (item.options && item.options.sortKey) {
		if (item.options.sortKey === sort.key) {
			sort.direction = sort.direction === 'asc' ? 'desc' : 'asc';
		} else {
			sort.key = item.options.sortKey;
			sort.direction = 'desc';
		}
		nextTick(() => sortItems());
	}
}
const sortItems = () => {
	items.sort((a: TableItem, b: TableItem) => {
		const aItem = a.item[sort.key as string];
		const bItem = b.item[sort.key as string];
		if (typeof aItem !== typeof bItem) {
			return 0;
		}

		switch(typeof aItem) {
			case 'number':
				return bItem - aItem;
			case 'string':
				return aItem < bItem ? 1 : -1;
			default:
				return 0;
		}
	})
}

const formatItemValue = (header: TableHeader, value: any) => {
	switch(typeof value) {
		case 'string':
			return value;
		case 'number':
			let output = value as any;
			if (header.options) {
				if (header.options.finance) {
					output = `$${value.toFixed(header.options.finance.decimals)} ${header.options.finance.currency ?? ''}`;
				}
			}
			return output;
		default:
			return value;
	}
}
onMounted(() => sortItems());
addIcons(BiArrowDownShort, BiArrowUpShort);
</script>
<template>
	<div v-bind="$attrs">
		<span class="font-semibold text-lg" v-if="title">{{ sort.direction === 'desc' ? 'Top' : 'Bottom' }} {{ props.count }} {{ props.title }}</span>
		<div :class="`grid ${gridCols[headers.length - 1]} gap-x-2 gap-y-1 bg-slate-400 text-center`">
			<template v-for="header in headers">
				<span
					@click="changeSort(header)"
					:class="`font-semibold text-sm md:text-md capitalize ${header.options?.sortKey ? 'cursor-pointer select-none' : ''}`"
				>
					<template v-if="header.options && header.options.sortKey === sort.key">
						<VIcon v-if="sort.direction === 'desc'" name="bi-arrow-down-short" />
						<VIcon v-else name="bi-arrow-up-short" />
					</template>
					{{ header.name }}
				</span>
			</template>
		</div>
		<div v-if="itemList.length != 0" :class="`grid ${gridCols[headers.length - 1]} gap-x-2 gap-y-1 text-sm break-all`">
			<template v-for="item in itemList" :key="item.key">
				<template v-for="(value, key, index) in item.item">
					<span :class="`capitalize pl-2 ${index != 0 ? 'text-center' : ''}`">{{ formatItemValue(headers[index], value) }}</span>
				</template>
			</template>
		</div>
		<div v-else class="w-full bg-slate-200 italic text-center">
			No Data
		</div>
	</div>
</template>