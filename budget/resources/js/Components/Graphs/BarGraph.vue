<script setup lang="ts">
import { defineComponent, ref, onMounted, toRefs, toRaw } from 'vue';
import { newPlot, Data } from 'plotly.js-dist-min';

defineProps<{
	values?: Object
}>();
const graph = ref<HTMLElement | null>(null);
const id = `${crypto.randomUUID()}-barchart`;

defineExpose({id, graph });
</script>

<script lang="ts">
export default defineComponent({
	name: 'BarGraph',
	mounted() {
		this.loadData();
	},
	methods: {
		loadData() {
			console.log(this.values);
			let x = Object.keys(this.values as Object);
			let y = Object.values(this.values as Object);
			const plot: Data[] = [{
				x: x,
				y: y,
				type: 'bar'
			}];

			const layout = {
				title: 'test',
				xaxis: {
					tickangle: -45
				},
				sort: true
			};
			newPlot((this.$refs.graph as HTMLElement), plot, layout);
		}
	}

})
</script>

<template>
	<div ref="graph" class="w-full min-h-[400px]" :id="id">
	</div>
</template>