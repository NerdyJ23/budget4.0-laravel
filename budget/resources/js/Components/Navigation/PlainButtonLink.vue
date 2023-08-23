<script setup lang="ts">
defineProps<{
	text: string,
	link?: string,
	current?: boolean
}>();
</script>

<template>
	<a :href="link">
		<input type="button" :class="[{'bg-slate-300': current}, `sidebar-item`]" :value="text"/>
	</a>
</template>