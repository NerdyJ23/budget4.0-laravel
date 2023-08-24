<template>
	<input
		type="button"
		:value="label"
		:class="`hover:cursor-pointer px-2 rounded-sm select-none
		border-solid border border-red-500 hover:bg-red-200`"
	/>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label?: string,
	link?: string
}>(), {
	label: 'Cancel'
});
</script>

<script lang="ts">
export default defineComponent({
	name: 'CancelButton'
})
</script>
