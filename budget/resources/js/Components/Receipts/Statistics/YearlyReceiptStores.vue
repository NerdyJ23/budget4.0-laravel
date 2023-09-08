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

const stores:Array<any> = reactive([]);
const storeList = computed(() => stores.slice(0, props.count));

const loadStoreStats = async () => {
	const response = await receiptApi.loadFavouriteStores(props.year);
	stores.length = 0;
	if (response.status === 200) {
		for (const item of response.data.result) {
			stores.push(item);
		}
	}
}
onMounted(() => {
	loadStoreStats();
});

watch(() => props.year, () => {
	loadStoreStats();
});
</script>
<template>
	<div v-bind="$attrs">
		<span class="font-semibold text-lg">Top {{ props.count }} Stores</span>
		<div class="pl-2 w-full grid grid-cols-4 gap-x-2 gap-y-1 bg-slate-400 text-center">
			<span class="font-semibold text-md">Store</span>
			<span class="font-semibold text-md">Entries</span>
			<span class="font-semibold text-md">Average Cost</span>
			<span class="font-semibold text-md">Total Cost</span>
		</div>
		<div class="grid grid-cols-4 gap-x-2 gap-y-1 text-sm pl-2">
			<template v-for="store in storeList" :key="store.store">
				<span class="capitalize">{{ store.store.toLowerCase() }}</span>
				<span class="text-center">{{ store.count }}</span>
				<span class="text-center">${{ (store.total / store.count).toFixed(2) }}</span>
				<span class="text-center">${{ store.total.toFixed(2) }}</span>
			</template>
		</div>
	</div>
</template>