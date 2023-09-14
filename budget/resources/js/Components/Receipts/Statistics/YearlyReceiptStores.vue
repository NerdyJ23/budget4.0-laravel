<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { reactive, onMounted, watch, computed } from 'vue';
import { TableItem, TableHeader, TableSort } from '@/types/table';
import SortableTable from '@/Components/Tables/SortableTable.vue';
import LoadingDialog from '@/Components/LoadingDialog.vue';

export interface StoreStats {
	store: string,
	count: number,
	total: number
};

const props = withDefaults(defineProps<{
	year?: number | string,
	count?: number
}>(), {
	year: (new Date).getFullYear(),
	count: 5
});

const is = reactive({
	loading: true
});

const headers: Array<TableHeader> = [
	{ name: 'store' },
	{ name: 'entries', options: { sortKey: 'count' } },
	{ name: 'average cost', options: {
		sortKey: 'avg',
		finance: { decimals: 2 }
	} },
	{ name: 'total cost', options: {
		sortKey: 'total',
		finance: { decimals: 2 }
	} }
] as TableHeader[];
const stores:Array<StoreStats> = reactive([]);
const items = computed(() => {
	return stores.map((store) => {
		return {
			key: store.store,
			item: {
				name: store.store.toLowerCase(),
				count: store.count,
				avg: (store.total / store.count),
				total: store.total
			}
		} as TableItem
	}) as TableItem[]
});

const tableSort: TableSort = {
	direction: 'desc',
	key: 'total'
};

const loadStoreStats = async () => {
	is.loading = true;
	const response = await receiptApi.loadFavouriteStores(props.year);
	stores.length = 0;
	if (response.status === 200) {
		for (const item of response.data.result) {
			stores.push(item as StoreStats);
		}
	}
	is.loading = false;
}

onMounted(() => {
	loadStoreStats();
});

watch(() => props.year, () => {
	loadStoreStats();
});
</script>
<template>
	<SortableTable class="pl-2"
		v-if="!is.loading"
		:loading="is.loading"
		:items="items"
		:headers="headers"
		title="Stores"
		key="store-table"
		:sort="tableSort"
	/>
	<LoadingDialog v-else />
</template>