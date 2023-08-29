<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { Receipt } from '@/types/Receipts/receipt';
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
const graphValues = reactive({});
const selectedMonth = computed({
	get: () => store.getters.selectedMonth,
	set: (value) => store.commit('selectedMonth', value)
});

const selectedYear = computed({
	get: () => store.getters.selectedYear,
	set: (value) => store.commit('selectedYear', value)
});

const title = computed(() => `${store.getters.getMonthListAsStrings[store.getters.selectedMonth - 1]} - ${store.getters.selectedYear}`)
const loadReceipts = async () => {
	const response = await receiptApi.listReceipts(selected.month as number, selected.year as number);

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
	console.log(response);
}

const generateGraph = () => {
	for(const receipt of receipts) {
		for(const item of receipt.items) {
			if (!graphValues[item.category.name]) {
				graphValues[item.category.name] = 0;
			}
			graphValues[item.category.name] += item.cost * item.count;
		}
	}
}

const refreshGraph = async () => {
	is.show = false;
	await loadReceipts();
	generateGraph();
	is.show = true;
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
	await loadReceipts();
	generateGraph();
	is.show = true;
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
		<BarGraph class="w-full" v-if="is.show" :title="title" :values="graphValues"></BarGraph>
	</div>
</template>