<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Receipt } from '@/types/Receipts/receipt';

import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/Graphs/ReceiptGraph.vue';
import ReceiptYearlyGraph from '@/Components/Receipts/Graphs/ReceiptYearlyGraph.vue';
import YearlyReceiptCategories from '@/Components/Receipts/Statistics/YearlyReceiptCategories.vue';
import YearlyReceiptStores from '@/Components/Receipts/Statistics/YearlyReceiptStores.vue';
import MonthDropdown from '@/Components/Inputs/MonthDropdown.vue';

import { computed, onMounted, watch, reactive } from 'vue';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import receiptApi from '@/services/Receipts/receiptApi';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import { MdStoreOutlined } from 'oh-vue-icons/icons';

const ReceiptStore = useReceiptStore();
const receipts: Array<Receipt> = reactive([]);
const is = reactive({
	loading: false
});

const loadReceipts = async () => {
	is.loading = true;
	const response = await receiptApi.listReceipts(ReceiptStore.selected.month, ReceiptStore.selected.year);
	receipts.length = 0;
	if (response.status === 200) {
		for(const item of response.data.result) {
			const receiptItem = {
				id: item.id,
				store: item.store,
				location: item.location,
				reference: item.reference,
				cost: item.cost,
				category: item.category,
				date: item.date,
				items: item.items
			} as Receipt;
			receipts.push(receiptItem);
		}
	}
	is.loading = false;
}

const yearTitle = computed(() => ReceiptStore.selected.year == (new Date).getFullYear() ? 'This Year' : ReceiptStore.selected.year);

//this is an absolute mess and needs to be refactored
const stats = computed(() => {
	let cats: any = [];
	let stores: any = [];

	for (const receipt of receipts) {
		if (!stores[receipt.store as string]) {
			stores[receipt.store as string] = {
				name: receipt.store,
				count: 1,
				cost: receipt.cost
			};
		} else {
			stores[receipt.store as string].count++;
			stores[receipt.store as string].cost += receipt.cost;
		}

		for (const item of receipt.items) {
			const category = (item.category as ReceiptItemCategory).name;
			if (cats[category]) {
				cats[category].count++;
				cats[category].cost += item.cost * item.count;
			} else {
				cats[category] = {
					name: category,
					cost: item.cost * item.count,
					count: 1
				};
			}
		}
	}

	let catOut:Array<any> = [];
	for (let [key, value] of Object.entries(cats)) {
		catOut.push(value);
	}
	catOut = catOut.sort((a, b) => b.count - a.count);


	let storeOut:Array<any> = [];
	for (let [key, value] of Object.entries(stores)) {
		storeOut.push(value);
	}
	storeOut = storeOut.sort((a, b) => b.count - a.count);

	return {
		categories: catOut.slice(0, 5),
		stores: storeOut.slice(0, 5)
	};
});


watch(() => ReceiptStore.selected.year, () => {
	loadReceipts();
});
watch(() => ReceiptStore.selected.month, () => {
	loadReceipts();
});
onMounted(() => loadReceipts());
</script>
<template>
    <Head title="Graphs" />
    <AuthenticatedLayout>
		<div class="flex flex-col">
			<span class="text-xl font-semibold m-2 text-center">
				{{ yearTitle }}
			</span>
			<div class="grid grid-cols-2 gap-x-2 px-2">
				<div class="flex flex-col border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
					<div class="inline-flex flex-row">
						<MonthDropdown v-model="ReceiptStore.selected.month" />
					</div>
					<ReceiptGraph :receipts="receipts" :loading="is.loading" />
					<div v-if="!is.loading">
						<span class="font-semibold text-lg">Top 5 Item Categories</span>
						<div class="pl-2 w-full grid grid-cols-4 gap-x-2 gap-y-1 bg-slate-400 text-center">
							<span class="font-semibold text-md">Category</span>
							<span class="font-semibold text-md">Item Count</span>
							<span class="font-semibold text-md">Average Cost</span>
							<span class="font-semibold text-md">Total Cost</span>
						</div>
						<div class="grid grid-cols-4 gap-x-2 gap-y-1 text-sm pl-2">
							<template v-for="item in stats.categories" :key="item.name">
								<span class="capitalize">{{ item.name.toLowerCase() }}</span>
								<span class="text-center">{{ item.count }}</span>
								<span class="text-center">${{ (item.cost / item.count).toFixed(2) }}</span>
								<span class="text-center">${{ item.cost.toFixed(2) }}</span>
							</template>
						</div>
					</div>

					<div v-if="!is.loading" class="mt-2">
						<span class="font-semibold text-lg">Top 5 Stores</span>
						<div class="pl-2 w-full grid grid-cols-4 gap-x-2 gap-y-1 bg-slate-400 text-center">
							<span class="font-semibold text-md">Store</span>
							<span class="font-semibold text-md">Entries</span>
							<span class="font-semibold text-md">Average Cost</span>
							<span class="font-semibold text-md">Total Cost</span>
						</div>
						<div class="grid grid-cols-4 gap-x-2 gap-y-1 text-sm pl-2">
							<template v-for="store in stats.stores" :key="store.name">
								<span class="capitalize">{{ store.name.toLowerCase() }}</span>
								<span class="text-center">{{ store.count }}</span>
								<span class="text-center">${{ (store.cost / store.count).toFixed(2) }}</span>
								<span class="text-center">${{ store.cost.toFixed(2) }}</span>
							</template>
						</div>
					</div>
				</div>
				<div class="flex flex-col border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
					<ReceiptYearlyGraph />
					<YearlyReceiptCategories :year="ReceiptStore.selected.year" />
					<YearlyReceiptStores :year="ReceiptStore.selected.year" class="mt-2"/>
				</div>

			</div>
		</div>
    </AuthenticatedLayout>
</template>
