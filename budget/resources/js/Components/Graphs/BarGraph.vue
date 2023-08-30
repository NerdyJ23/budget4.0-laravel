<script setup lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { newPlot, react, Data, Layout, Config, PlotlyHTMLElement, PlotHoverEvent, PlotMouseEvent } from 'plotly.js-dist-min';
import { BarItem } from '@/types/Graphs/graph';
import { reactive } from 'vue';

const props = defineProps<{
	values: Array<BarItem>,
	title: string
}>();
const id = `${crypto.randomUUID()}-barchart`;
const data: Array<Data> = reactive([]);
const graph = ref<HTMLElement | null>(null);
const layout: Partial<Layout> = reactive({
	title: `${props.title}`,
	xaxis: {
		visible: false
	},
	legend: {
		orientation: 'h',
	},
	hovermode: 'closest',
	dragmode: 'pan'
});

const config: Partial<Config> = reactive({
	showAxisDragHandles: false,
	scrollZoom: true,
	displayModeBar: false,
	showTips: false,
});

const loadData = () => {
	props.values.sort((a, b) => {
		if (a.value == b.value) {
			return 0;
		}
		return a.value < b.value ? 1 : -1;
	});
	props.values.map(item => data.push(createDataElement(item)))

	let total = 0;
	props.values.forEach((item) => total+= item.value);
	layout.title = `${props.title} - $${total.toFixed(2)}`


	newPlot((graph.value as HTMLElement), data, layout, config);
	(graph.value as PlotlyHTMLElement).on('plotly_hover', (e: PlotHoverEvent) => {
		itemHovered(e.points[0].x as string);
	});

	(graph.value as PlotlyHTMLElement).on('plotly_unhover', (e: PlotMouseEvent) => {
		resetItemColors();
	})
}
const itemHovered = (itemName: string) => {
	data.length = 0;
	let tempData = props.values.map((item) => {
		item.color!.a = item.key !== itemName ? 0.4 : 1;
		return item;
	});
	tempData.map(item => data.push(createDataElement(item)));
	react((graph.value as PlotlyHTMLElement), data, layout);
}
const resetItemColors = () => {
	data.length = 0;
	let tempData = props.values.map((item) => {
		item.color!.a = 1;
		return item;
	});
	tempData.map(item => data.push(createDataElement(item)));
	react((graph.value as PlotlyHTMLElement), data, layout);
}
const createDataElement = (item: BarItem):Data => {
	return {
		x: [item.key],
		y: [item.value],
		marker: {
			color: [`rgba(${item.color?.r}, ${item.color?.g}, ${item.color?.b}, ${item.color?.a})`],
		},
		name: `${item.key} - $${item.value.toFixed(2)}`,
		text: [item.text as string],
		textposition: 'none',
		type: 'bar',
		hoverinfo: 'x+text',
		showlegend: true
	} as Data;
}
onMounted(() => {
	loadData();
})
defineExpose({id, graph });
</script>

<script lang="ts">export default defineComponent({name: 'BarGraph'});</script>

<template>
	<div ref="graph" class="w-full min-h-[400px]" :id="id"></div>
</template>