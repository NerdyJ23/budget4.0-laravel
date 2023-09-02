<script setup lang="ts">
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { BarItem, BarColor } from '@/types/Graphs/graph';
import { onBeforeMount, reactive } from 'vue';
import { useGenericStore } from '@/store/genericPiniaStore';
import receiptApi from '@/services/Receipts/receiptApi';

const GenericStore = useGenericStore();
const value: Array<BarItem> = reactive([]);
const loadValues = () => {

}
onBeforeMount(() => {
	for(const item of GenericStore.months) {
		value.push({
			key: item,
			value: 1,
			color: {
				r: 50,
				g: 50,
				b: 50,
				a: 1
			},
			text: ''
		});
	}
})
</script>
<template>
	<BarGraph title="Yearly Overview" :values="value"></BarGraph>
</template>