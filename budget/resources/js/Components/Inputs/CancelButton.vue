<template>
	<div
		:value="label"
		:class="`hover:cursor-pointer px-1 md:px-2 rounded-full md:rounded-sm select-none
		border-solid border border-red-500 hover:bg-red-200`"
	>
	<slot>
		<div class="hidden md:inline">
			Cancel
		</div>
		<div class="inline md:hidden">
			<VIcon name="io-close" scale="1.5" class="fill-red-600"></VIcon>
		</div>
	</slot>
	</div>
</template>
<script setup lang="ts">
import { IoClose } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label?: string,
	link?: string
}>(), {
	label: 'Cancel'
});
addIcons(IoClose)
</script>

<script lang="ts">
export default defineComponent({
	name: 'CancelButton'
})
</script>
