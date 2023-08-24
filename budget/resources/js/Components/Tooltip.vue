<script setup lang="ts">
import { nextTick } from 'vue';
import { defineComponent, ref, watch } from 'vue';
const show = ref(false);
const offset = ref(0);
const tooltipElement = ref<HTMLElement | null>(null);
defineProps<{
	tooltip?: string
}>();

watch(show, (_new, _old) => {
	if (_new) {
		nextTick(() => {
				if (tooltipElement.value) {
					offset.value = tooltipElement.value?.clientWidth / 2;
				}
			})
	}
})
</script>
<script lang="ts">
export default defineComponent({name: 'Tooltip'})
</script>
<template>
	<div class="tooltip relative">
		<div class="tooltip-activator" @mouseover="show = true" @mouseleave="show = false">
			<slot></slot>
		</div>
		<div
			v-show="show"
			ref="tooltipElement"
			class="tooltip-text absolute"
			:style="`max-width: 200px; margin-left: -${offset - 20}px`"
		>
			<slot name="tooltip">{{ tooltip }}</slot>
		</div>
	</div>
</template>
<style lang="scss">
.tooltip {
	// @apply relative;
	&-text {
		@apply bg-neutral-800/80 px-2 py-1 rounded-md text-white break-words;
	}
}
</style>