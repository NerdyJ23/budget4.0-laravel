<script setup lang="ts">
import { defineComponent } from 'vue';

defineProps<{
	label: string,
	link?: string,
	classes?: string
}>();
</script>

<script lang="ts">
export default defineComponent({
	name: 'VueButton'
})
</script>

<template>
	<input
		type="button"
		:value="label"
		:class="`
			bg-stone-300
			hover:bg-stone-400
			hover:cursor-pointer
			px-2 rounded-sm
			text-lg
			${classes}
		`"
	/>
</template>