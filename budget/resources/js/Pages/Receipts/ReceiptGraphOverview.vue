<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/Graphs/ReceiptGraph.vue';
import ReceiptYearlyGraph from '@/Components/Receipts/Graphs/ReceiptYearlyGraph.vue';
import receiptApi from '@/services/Receipts/receiptApi';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import { reactive, onMounted, computed } from 'vue';

const ReceiptStore = useReceiptStore();
const stores: any = reactive([]);
const category: any = reactive([]);

const loadStoreStats = async () => {
	const response = await receiptApi.loadFavouriteStores(ReceiptStore.selected.year);

	if (response.status === 200) {
		for (const item of response.data.result) {
			stores.push(item);
		}
	}
}

const loadCategoryStats = async () => {
	const response = await receiptApi.loadFavouriteCategories(ReceiptStore.selected.year);

	if (response.status === 200) {
		for (const item of response.data.result) {
			category.push(item);
		}
	}
}

const topCategories = computed(() => category.slice(0,5));
const topStores = computed(() => stores.slice(0, 10));

onMounted(() => {
	loadStoreStats();
	loadCategoryStats();
});
</script>
<template>
    <Head title="Graphs" />
    <AuthenticatedLayout>
		<div class="grid grid-cols-2 gap-x-2 px-2">
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Monthly Overview
				</span>
				<ReceiptGraph />
			</div>
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Yearly Overview
				</span>
				<ReceiptYearlyGraph />
			</div>
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Your Favourite Stores
				</span>
				<div v-for="store in topStores">
					{{ store.count}}x {{ store.store }} totalling ${{ store.total }}
				</div>
			</div>
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Yearly Overview by Category
				</span>
				<div class="grid grid-cols-2">
					<template v-for="item in topCategories">
						<span>
							{{ item.count }}x
							<span class="capitalize">
								{{ item.name.toLowerCase() }}
							</span>
						</span>
						<span class="font-semibold">
							${{ item.cost }}
						</span>
					</template>
				</div>
			</div>
		</div>
    </AuthenticatedLayout>
</template>
