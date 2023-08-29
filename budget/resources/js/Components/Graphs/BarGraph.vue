<script setup lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { newPlot, Data } from 'plotly.js-dist-min';

const props = defineProps<{
	values?: Object,
	title: string
}>();
const graph = ref<HTMLElement | null>(null);
const id = `${crypto.randomUUID()}-barchart`;

const loadData = () => {
	console.log(props.values);
			let x = Object.keys(props.values as Object);
			let y = Object.values(props.values as Object);
			const plot: Data[] = [{
				x: x,
				y: y,
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