<script setup lang="ts">
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { BarItem, BarColor } from '@/types/Graphs/graph';
import { onMounted, reactive } from 'vue';
import { useGenericStore } from '@/store/genericPiniaStore';
import receiptApi from '@/services/Receipts/receiptApi';
import LoadingDialog from '@/Components/LoadingDialog.vue';

const GenericStore = useGenericStore();
const value: Array<BarItem> = reactive([]);
const is = reactive({
	loading: true
});
const loadValues = async () => {
	const response = await receiptApi.loadYearlyCosts();
	if (response.status === 200) {
		for(let i = 1; i < GenericStore.months.length; i++) {
			value.push({
				key: GenericStore.months[i - 1],
				value: response.data.result[i - 1],
				color: {
					r: 50,
					g: 50,
					b: 50,
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
	<LoadingDialog v-if="is.loading"></LoadingDialog>
	<BarGraph v-else title="Yearly Overview" :values="value" key="yearly-bar-graph" labels sort="none"></BarGraph>
</template>