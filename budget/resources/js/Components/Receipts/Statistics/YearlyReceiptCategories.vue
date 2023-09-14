<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { reactive, onMounted, watch, computed } from 'vue';
import { TableItem } from '@/types/table';
import SortableTable from '@/Components/Tables/SortableTable.vue';
export interface CategoryStat {
	name: string,
	cost: number,
	count: number
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
const categories: Array<CategoryStat> = reactive([]);

const loadCategoryStats = async () => {
	is.loading = true;
	const response = await receiptApi.loadFavouriteCategories(props.year);
	categories.length = 0;
	if (response.status === 200) {
		for (const item of response.data.result) {
			categories.push(item as CategoryStat);
		}
	}
	is.loading = false;
}
const items = computed(() => {
	return categories.map((item) => {
		return {
			key: item.name,
			item: {
				name: item.name.toLowerCase(),
				count: item.count,
				avg: `$${(item.cost / item.count).toFixed(2)}`,
				cost: `$${item.cost.toFixed(2)}`
			}
		} as TableItem
	}) as TableItem[];
});
const headers = computed(() => ['name', 'count', 'average cost', 'total cost']);

onMounted((() => loadCategoryStats()));
watch(() =>  props.year, () => {
	loadCategoryStats();
});

</script>
<template>
	<SortableTable class="pl-2" v-if="!is.loading" :items="items" :headers="headers" title="Item Categories"/>
</template>