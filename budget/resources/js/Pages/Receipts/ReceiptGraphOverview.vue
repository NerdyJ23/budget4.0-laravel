<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/Graphs/ReceiptGraph.vue';
import ReceiptYearlyGraph from '@/Components/Receipts/Graphs/ReceiptYearlyGraph.vue';
import YearlyReceiptCategories from '@/Components/Receipts/Statistics/YearlyReceiptCategories.vue';

import receiptApi from '@/services/Receipts/receiptApi';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import { reactive, onMounted, computed, watch } from 'vue';

const ReceiptStore = useReceiptStore();
const stores: any = reactive([]);
const category: any = reactive([]);

const loadStoreStats = async () => {
	const response = await receiptApi.loadFavouriteStores(ReceiptStore.selected.year);
	stores.length = 0;
	if (response.status === 200) {
		for (const item of response.data.result) {
			stores.push(item);
		}
	}
}

const topCategories = computed(() => category.slice(0,5));
const topStores = computed(() => stores.slice(0, 10));

watch(() => ReceiptStore.selected.year, () => {
	loadStoreStats();
});

watch(() => ReceiptStore.selected.month, () => {
	loadStoreStats();
});

onMounted(() => {
	loadStoreStats();
});
</script>
<template>
    <Head title="Graphs" />
    <AuthenticatedLayout>
		<div class="grid grid-cols-2 gap-x-2 px-2">
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Monthly View
				</span>
				<ReceiptGraph />
			</div>
			<div class="flex flex-col border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					This Year
				</span>
				<ReceiptYearlyGraph />
				<YearlyReceiptCategories :year="ReceiptStore.selected.year" />
			</div>
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Your Favourite Stores ({{ ReceiptStore.selected.year }})
				</span>
				<div v-for="store in topStores">
					{{ store.count}}x {{ store.store }} totalling ${{ store.total.toFixed(2) }}
				</div>
			</div>
		</div>
    </AuthenticatedLayout>
</template>
