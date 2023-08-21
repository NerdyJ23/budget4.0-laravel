<script setup lang="ts">
import { defineComponent } from 'vue';

defineProps<{
	text: string,
	link?: string
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
		:value="text"
		:class="['bg-stone-300 hover:bg-stone-400 hover:cursor-pointer text-lg']"
	/>
</template>