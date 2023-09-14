<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Receipt } from '@/types/Receipts/receipt';
import { TableItem } from '@/types/table';

import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/Graphs/ReceiptGraph.vue';
import ReceiptYearlyGraph from '@/Components/Receipts/Graphs/ReceiptYearlyGraph.vue';
import YearlyReceiptCategories from '@/Components/Receipts/Statistics/YearlyReceiptCategories.vue';
import YearlyReceiptStores from '@/Components/Receipts/Statistics/YearlyReceiptStores.vue';
import MonthDropdown from '@/Components/Inputs/MonthDropdown.vue';
import SortableTable from '@/Components/Tables/SortableTable.vue';
import LoadingDialog from '@/Components/LoadingDialog.vue';

import { computed, onMounted, watch, reactive } from 'vue';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import receiptApi from '@/services/Receipts/receiptApi';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';

export interface StatItem {
	name: string,
	count: number,
	cost: string | number,
	avg: string | number
};

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

	let catOut:Array<TableItem> = [];
	let storeOut:Array<TableItem> = [];

	for (let [key, value] of Object.entries(cats)) {
		catOut.push({
			key: key,
			item: {
				name: cats[key].name.toLowerCase(),
				count: cats[key].count,
				cost: `$${cats[key].cost.toFixed(2)}`,
				avg: `$${(cats[key].cost / cats[key].count).toFixed(2)}`
			} as StatItem
		} as TableItem);
	}
	catOut = catOut.sort((a: TableItem, b: TableItem) => (b.item as StatItem).count - (a.item as StatItem).count);


	for (let [key, value] of Object.entries(stores)) {
		storeOut.push({
			key: key,
			item: {
				name: stores[key].name.toLowerCase(),
				count: stores[key].count,
				cost: `$${stores[key].cost.toFixed(2)}`,
				avg: `$${(stores[key].cost / stores[key].count).toFixed(2)}`,
			} as StatItem
		});
	}
	storeOut = storeOut.sort((a: TableItem, b: TableItem) => (b.item as StatItem).count - (a.item as StatItem).count);

	return {
		categories: catOut as TableItem[],
		stores: storeOut as TableItem[]
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
					<template v-if="!is.loading">
						<div class="inline-flex flex-row">
							<MonthDropdown v-model="ReceiptStore.selected.month" />
						</div>
						<ReceiptGraph :receipts="receipts" :loading="is.loading" />
						<!-- Categories -->
						<SortableTable
							:headers="['category', 'item count', 'average cost', 'total cost']"
							:items="stats.categories"
							:loading="is.loading"
							title="Item Categories"
						/>
						<!-- Stores -->
						<SortableTable
							:headers="['store', 'entries', 'average cost', 'total cost']"
							:items="stats.stores"
							:loading="is.loading"
							title="Stores"
						/>
					</template>
					<LoadingDialog v-else />
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
