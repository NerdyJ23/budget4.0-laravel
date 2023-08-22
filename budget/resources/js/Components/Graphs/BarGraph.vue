<script setup lang="ts">
import { defineComponent } from 'vue';
import { newPlot, Data } from 'plotly.js-dist-min';

defineProps<{
	info?: Array<number>,
	keys?: Array<string>
}>();

const id = `${crypto.randomUUID()}-barchart`;
defineExpose({id});
</script>
<script lang="ts">

export default defineComponent({
	name: 'BarGraph',
	mounted() {
		const plot: Data[] = [{
			x: this.info ?? [],
			y: this.keys ?? [],
			type: 'bar'
		}];

		const layout = {
			title: 'test'
		};

		newPlot(this.id, plot, layout);
	}
})
</script>
<template>
	<div :id="id">
	</div>
</template>