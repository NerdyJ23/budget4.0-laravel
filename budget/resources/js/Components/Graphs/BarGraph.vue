<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import { newPlot, Data } from 'plotly.js-dist-min';

defineProps<{
	info?: Array<number>,
	keys?: Array<string>
}>();
const graph = ref<HTMLElement | null>(null);
const id = `${crypto.randomUUID()}-barchart`;
defineExpose({id, graph});
</script>
<script lang="ts">

export default defineComponent({
	name: 'BarGraph',
	mounted() {
		const plot: Data[] = [{
			x: this.keys ?? [],
			y: this.info ?? [],
			type: 'bar'
		}];

		const layout = {
			title: 'test',
			xaxis: {
				tickangle: -45
			}
		};
		newPlot(this.$refs.graph, plot, layout);
	}
})
</script>
<template>
	<div ref="graph" class="w-full min-h-[400px]" :id="id">
	</div>
</template>