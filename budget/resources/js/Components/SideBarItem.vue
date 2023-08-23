<template>
	<input
		:class="[
			{'cursor-pointer': link},
			'p-2 hover:bg-stone-200 w-full text-left font-normal text-xl'
		]"
		type="button"
		:value="text"
		v-bind="$attrs"
	/>
</template>

<script setup lang="ts">
import { defineComponent } from 'vue';

defineProps<{
	text: string,
	link?: boolean
}>();
</script>

<script lang="ts">

export default defineComponent({
	name: 'SideBarItem',
})
</script>