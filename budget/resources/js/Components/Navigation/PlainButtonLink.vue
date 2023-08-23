<script setup lang="ts">
defineProps<{
	text: string,
	link?: string
}>();
</script>

<template>
	<a :href="link">
		<input type="button" class="sidebar-item" :value="text"/>
	</a>
</template>