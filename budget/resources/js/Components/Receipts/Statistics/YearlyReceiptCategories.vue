<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { reactive, onMounted, watch, computed } from 'vue';

const props = withDefaults(defineProps<{
	year?: number | string,
	count?: number
}>(), {
	year: (new Date).getFullYear(),
	count: 5
});

const categories: Array<any> = reactive([]);
const category = computed(() => categories.slice(0, props.count));

const loadCategoryStats = async () => {
	const response = await receiptApi.loadFavouriteCategories(props.year);
	categories.length = 0;
	if (response.status === 200) {
		for (const item of response.data.result) {
			categories.push(item);
		}
	}
}

onMounted((() => loadCategoryStats()));
watch(() =>  props.year, () => {
	loadCategoryStats();
});

</script>
<template>
	<span class="font-semibold text-lg">Top {{ props.count }} Item Categories</span>
	<div class="pl-2 w-full grid grid-cols-4 gap-x-2 gap-y-1 bg-slate-400 text-center">
		<span class="font-semibold text-md">Category</span>
		<span class="font-semibold text-md">Item Count</span>
		<span class="font-semibold text-md">Average Item Cost</span>
		<span class="font-semibold text-md">Total Cost</span>
	</div>
	<div class="grid grid-cols-4 gap-x-2 gap-y-1">
		<template v-for="item in category" :key="item.name">
			<span class="capitalize">{{ item.name.toLowerCase() }}</span>
			<span class="text-center">{{ item.count }}</span>
			<span class="text-center">${{ (item.cost / item.count).toFixed(2) }}</span>
			<span class="text-center">${{ item.cost.toFixed(2) }}</span>
		</template>
	</div>
</template>