<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import { BarItem, BarColor } from '@/types/Graphs/graph';

import LoadingDialog from '@/Components/LoadingDialog.vue';
import { defineComponent, onMounted, reactive, computed, watch } from 'vue';
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { useReceiptStore } from '@/store/receiptPiniaStore';
import { useGenericStore } from '@/store/genericPiniaStore';

const props = defineProps<{
	receipts: Array<Receipt>,
	loading: boolean
}>();

const receiptStore = useReceiptStore();
const genericStore = useGenericStore();

const is = reactive({
	loading: props.loading,
	show: false
});

const graphValues: Array<BarItem> = reactive([]);

const title = computed(() => `${genericStore.months[receiptStore.selected.month - 1]} ${receiptStore.selected.year}`)

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
	is.show = false;
	is.loading = true;
	graphValues.length = 0;
	let localGraphValues = {} as any;
	for(const receipt of props.receipts) {
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
	is.show = true;
	is.loading = false;
}

onMounted(async () => {
	receiptStore.setup();
	await generateGraph();
})

watch(() => props.receipts, () => {
	generateGraph();
}, {deep: true});
watch(() => props.loading, (loading) => {
	is.loading = loading;
});

</script>

<script lang="ts">
export default defineComponent({
	name: 'ReceiptGraph',
	components: { BarGraph },
})
</script>
<template>
	<div class="inline-flex flex-col w-full">
		<LoadingDialog v-if="is.loading"></LoadingDialog>
		<BarGraph class="w-full" v-else-if="is.show && graphValues.length" :title="title" :values="graphValues"></BarGraph>
		<span v-else class="text-center w-full text-xl font-semibold">No data</span>
	</div>
</template>