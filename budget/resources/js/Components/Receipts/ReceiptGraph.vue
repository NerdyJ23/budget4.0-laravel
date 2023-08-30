<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { Receipt } from '@/types/Receipts/receipt';
import { BarItem, BarColor } from '@/types/Graphs/graph';

import LoadingDialog from '@/Components/LoadingDialog.vue';

import { defineComponent, ref, onMounted, reactive, computed } from 'vue';
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { useStore } from 'vuex';

const receipts: Receipt[] = [];
const urlParams = new URLSearchParams(window.location.search);
const date = new Date;
const store = useStore();

const selected = reactive({
	year: urlParams.get('year') ?? date.getFullYear(),
	month: urlParams.get('month') ?? (date.getMonth() + 1)
});


const is = reactive({
	loading: false,
	show: false
});
const graphValues: Array<BarItem> = [];
const selectedMonth = computed({
	get: () => store.getters.selectedMonth,
	set: (value) => store.commit('selectedMonth', value)
});

const selectedYear = computed({
	get: () => store.getters.selectedYear,
	set: (value) => store.commit('selectedYear', value)
});

const title = computed(() => `${store.getters.getMonthListAsStrings[store.getters.selectedMonth - 1]} ${store.getters.selectedYear}`)
const loadReceipts = async () => {
	graphValues.length = 0;
	const response = await receiptApi.listReceipts(store.getters.selectedMonth, store.getters.selectedYear);
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
	let localGraphValues = {};
	for(const receipt of receipts) {
		for(const item of receipt.items) {
			if (!localGraphValues[item.category.name]) {
				localGraphValues[item.category.name] = 0;
			}
			localGraphValues[item.category.name] += item.cost * item.count;
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
	if (typeof selected.month == 'string') {
		selected.month = parseInt(selected.month as string);
	}

	if (typeof selected.year == 'string') {
		selected.year = parseInt(selected.year as string);
	}

	store.commit('selectedMonth', selected.month);
	store.commit('selectedYear', selected.year);
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
			<select v-model="selectedMonth" @change="refreshGraph">
				<option
					v-for="(month, index) in store.getters.getMonthListAsStrings"
					:selected="index == store.getters.selectedMonth"
					:value="index + 1"
				>
					{{ month }}
				</option>
			</select>
		</div>
		<LoadingDialog v-if="is.loading"></LoadingDialog>
		<BarGraph class="w-full" v-else-if="is.show && graphValues.length" :title="title" :values="graphValues"></BarGraph>
		<span v-else class="text-center w-full text-xl font-semibold">No data</span>
	</div>
</template>