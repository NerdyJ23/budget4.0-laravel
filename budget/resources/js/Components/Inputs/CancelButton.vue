<template>
	<input
		type="button"
		:value="label"
		:class="`hover:cursor-pointer px-2 rounded-sm text-lg ml-2 mt-1
		border-solid border-2 border-red-500`"
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
