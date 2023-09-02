<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import { BarItem, BarColor } from '@/types/Graphs/graph';

import LoadingDialog from '@/Components/LoadingDialog.vue';
import MonthDropdown from '@/Components/Inputs/MonthDropdown.vue';

import { defineComponent, onMounted, reactive, computed } from 'vue';
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import { useGenericStore } from '@/store/genericPiniaStore';

const receipts: Receipt[] = [];
const date = new Date;

const receiptStore = useReceiptStore();
const genericStore = useGenericStore();

const is = reactive({
	loading: false,
	show: false
});
const graphValues: Array<BarItem> = [];

//convert to writeable state later
const selectedMonth = computed({
	get: () => receiptStore.selected.month,
	set: (value) => receiptStore.$patch({selected: {month: value}})
});

const selectedYear = computed({
	get: () => receiptStore.selected.year,
	set: (value) => receiptStore.$patch({selected: {year: value}})
});

const title = computed(() => `${genericStore.months[receiptStore.selected.month - 1]} ${receiptStore.selected.year}`)
const loadReceipts = async () => {
	graphValues.length = 0;
	const response = await receiptApi.listReceipts(receiptStore.selected.month, receiptStore.selected.year);
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
}
const randomRgb = ():BarColor => {
	let r = Math.floor(Math.random() * 256);
	let g = Math.floor(Math.random() * 256);
	let b = Math.floor(Math.random() * 256);
	return {
		r: r,
		g: g,
		b: b,
		a: 1
	} as BarColor;
}

const generateGraph = () => {
	let localGraphValues = {} as any;
	for(const receipt of receipts) {
		for(const item of receipt.items) {
			if (!localGraphValues[(item.category as ReceiptItemCategory).name]) {
				localGraphValues[(item.category as ReceiptItemCategory).name] = 0;
			}
			localGraphValues[(item.category as ReceiptItemCategory).name] += item.cost * item.count;
		}
	}
	for (const [key, value] of Object.entries(localGraphValues)) {
		const temp: BarItem = {
			key: key,
			value: value as number,
			color: randomRgb(),
			text: `$${(value as number).toFixed(2)}`
		};
		graphValues.push(temp);
	}
}

const refreshGraph = async () => {
	is.show = false;
	is.loading = true;
	await loadReceipts();
	generateGraph();
	is.show = true;
	is.loading = false;
}

onMounted(async () => {
	receiptStore.setup();
	refreshGraph();
})

defineExpose({
	receipts, is
})
</script>

<script lang="ts">
export default defineComponent({
	name: 'ReceiptGraph',
	components: { BarGraph },
})
</script>
<template>
	<div class="inline-flex flex-col w-full">
		<div class="inline-flex flex-row">
			<MonthDropdown v-model="selectedMonth" @update:model-value="refreshGraph"/>
		</div>
		<LoadingDialog v-if="is.loading"></LoadingDialog>
		<BarGraph class="w-full" v-else-if="is.show && graphValues.length" :title="title" :values="graphValues"></BarGraph>
		<span v-else class="text-center w-full text-xl font-semibold">No data</span>
	</div>
</template>