<template>
<div
	v-bind="$attrs"
	:class="`ml-auto hover:cursor-pointer px-2 rounded-sm text-lg mt-1
	bg-lime-500/80 hover:bg-lime-500
	border-solid border border-lime-500
	`"
>
	<slot>Confirm</slot>
</div>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label?: string,
	link?: string
}>(), {
	label: 'Confirm'
});
</script>

<script lang="ts">
export default defineComponent({
	name: 'ConfirmButton'
})
</script>
