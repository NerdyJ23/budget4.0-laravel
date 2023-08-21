<template>
		<input
		type="button"
		:value="label"
		:class="`ml-auto hover:cursor-pointer px-2 rounded-sm text-lg mt-1
		bg-lime-400 hover:bg-lime-500
		border-solid border-2 border-lime-500
		`"
	/>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';
import VueButton from '@/Components/Inputs/VueButton.vue';

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
