<script setup lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { newPlot, Data } from 'plotly.js-dist-min';
import { BarItem } from '@/types/Graphs/graph';

const props = defineProps<{
	values: Array<BarItem>,
	title: string
}>();
const graph = ref<HTMLElement | null>(null);
const id = `${crypto.randomUUID()}-barchart`;

const loadData = () => {
	props.values.sort((a, b) => {
		if (a.value == b.value) {
			return 0;
		}
		return a.value < b.value ? 1 : -1;
	});

	const plot: Data[] = [{
		x: props.values.map(item => item.key),
		y: props.values.map(item => item.value),
		type: 'bar'
	}];

	const layout = {
		title: props.title,
		xaxis: {
			tickangle: -45
		},
		sort: true
	};
	newPlot((graph.value as HTMLElement), plot, layout);
}

onMounted(() => {
	loadData();
})
defineExpose({id, graph });
</script>

<script lang="ts">export default defineComponent({name: 'BarGraph'});</script>

<template>
	<div ref="graph" class="w-full min-h-[400px]" :id="id">
	</div>
</template>