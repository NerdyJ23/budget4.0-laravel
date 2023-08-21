<template>
	<input
		:class="[
			{'cursor-pointer': link},
			'p-2 hover:bg-stone-200 w-full text-left'
		]"
		type="button"
		:value="text"
	/>
</template>

<script setup lang="ts">
import { defineComponent } from 'vue';

defineProps<{
	text: string,
	link?: string
}>();
</script>

<script lang="ts">

export default defineComponent({
	name: 'SideBarItem',
})
</script>