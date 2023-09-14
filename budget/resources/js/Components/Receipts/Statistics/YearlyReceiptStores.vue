<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { reactive, onMounted, watch, computed } from 'vue';
import { TableItem } from '@/types/table';
import SortableTable from '@/Components/Tables/SortableTable.vue';

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
const headers = ['store', 'entries', 'average cost', 'total cost'];
const stores:Array<StoreStats> = reactive([]);

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

const items = computed(() => {
	return stores.map((store) => {
		return {
			key: store.store,
			item: {
				name: store.store.toLowerCase(),
				count: store.count,
				avg: `$${(store.total / store.count).toFixed(2)}`,
				total: `$${store.total.toFixed(2)}`
			}
		} as TableItem
	}) as TableItem[]
})
onMounted(() => {
	loadStoreStats();
});

watch(() => props.year, () => {
	loadStoreStats();
});
</script>
<template>
	<SortableTable class="pl-2" :loading="is.loading" :items="items" :headers="headers" title="Item Categories" key="store-table"/>
</template>