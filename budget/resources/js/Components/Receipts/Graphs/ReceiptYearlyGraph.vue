<script setup lang="ts">
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { BarItem, BarColor } from '@/types/Graphs/graph';
import { onMounted, reactive } from 'vue';
import { useGenericStore } from '@/store/genericPiniaStore';
import { useReceiptStore } from '@/store/receiptPiniaStore';

import YearDropdown from '@/Components/Inputs/YearDropdown.vue';
import receiptApi from '@/services/Receipts/receiptApi';
import LoadingDialog from '@/Components/LoadingDialog.vue';

const GenericStore = useGenericStore();
const ReceiptStore = useReceiptStore();

const value: Array<BarItem> = reactive([]);
const is = reactive({
	loading: true
});

const loadValues = async () => {
	value.length = 0;
	is.loading = true;
	const response = await receiptApi.loadYearlyCosts(ReceiptStore.selected.year);
	if (response.status === 200) {
		for(let i = 1; i <= GenericStore.months.length; i++) {
			value.push({
				key: GenericStore.months[i - 1],
				value: response.data.result[i - 1],
				color: {
					r: 106,
					g: 164,
					b: 229,
					a: 1
				} as BarColor,
			})
		}
	}
	is.loading = false;
}
onMounted(() => {
	loadValues();
})
</script>
<template>
	<div class="inline-flex flex-col w-full">
		<div class="inline-flex flex-row">
			<YearDropdown v-model="ReceiptStore.selected.year" @update:model-value="loadValues" />
		</div>
		<LoadingDialog v-if="is.loading"></LoadingDialog>
		<BarGraph v-else title="Yearly Overview" :values="value" key="yearly-bar-graph" labels sort="none"></BarGraph>
	</div>
</template>