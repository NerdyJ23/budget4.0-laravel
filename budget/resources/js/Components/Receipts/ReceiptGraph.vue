<script setup lang="ts">
import receiptApi from '@/services/Receipts/receiptApi';
import { Receipt } from '@/types/Receipts/receipt';
import { defineComponent, ref, onMounted, reactive } from 'vue';
import BarGraph from '@/Components/Graphs/BarGraph.vue';

const receipts: Receipt[] = [];

const is = reactive({
	loading: false,
	show: false
});

const graphValues = {};

const loadReceipts = async () => {
	const response = await receiptApi.listReceipts(2, 2022);

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

// const generateGraph = () => {
// 	for(const receipt of receipts) {
// 		for(const item of receipt.items) {
// 			if (!graphValues[item.category.name]) {
// 				graphValues[item.category.name] = 0;
// 			}
// 			graphValues[item.category.name] += item.cost * item.count;
// 		}
// 	}
// }

onMounted(async () => {
	await loadReceipts();
	// generateGraph();
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
	<BarGraph v-if="is.show" :values="graphValues"></BarGraph>
</template>