<template>
<div
	v-bind="$attrs"
	:class="`hover:cursor-pointer px-1 md:px-2 md:rounded-sm md:border-none select-none
	md:bg-lime-600/80 hover:bg-lime-600/40 border border-lime-600 rounded-full
	`"
>
	<slot>
		<div class="hidden md:inline">
			Confirm
		</div>
		<div class="inline md:hidden">
			<VIcon name="pr-save" scale="1.5" class="fill-lime-600"></VIcon>
		</div>
	</slot></div>
</template>
<script setup lang="ts">
import { PrSave } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label?: string,
	link?: string
}>(), {
	label: 'Confirm'
});
addIcons(PrSave);
</script>

<script lang="ts">
export default defineComponent({
	name: 'ConfirmButton'
})
</script>
