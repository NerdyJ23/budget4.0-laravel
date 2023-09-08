<script setup lang="ts">
const props = defineProps<{
	link?: string,
	current?: boolean
}>();
</script>

<template>
	<a :href="link" v-bind="$attrs" :class="[{'bg-slate-300': current}, 'cursor-pointer hover:bg-stone-200']">
		<slot></slot>
	</a>
</template>