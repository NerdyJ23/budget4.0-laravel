<script setup lang="ts">
import { reactive, onMounted, ref, defineEmits, computed } from 'vue';
import { MdFilterlistRound } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { onClickOutside } from '@vueuse/core'

const filterDialog = ref<HTMLElement | null>(null);
const innerDialog = ref<HTMLElement | null>(null);

const props = withDefaults(defineProps<{
	iconSize?: number
}>(), {
	iconSize: 1
});
const is = reactive({
	open: false
})

const x = computed(():string => {
	if (innerDialog.value) {
		const pos = innerDialog.value.getBoundingClientRect();
		const buttonWidth = filterDialog.value.clientWidth;

		console.log(pos);
		if (pos.right > window.innerWidth) { //overflows off right
			return `right: 0px`;
		} else if (pos.left - pos.width < 0) { //overflows off left
			return `left: 0`;
		}
		return `left: -${pos.width / 2 - buttonWidth}px`; //center under button
	}
	return `left: 0px`;
})
onMounted(() => {
	onClickOutside(filterDialog.value, (event) => {
		is.open = false;
	});
})

const emit = defineEmits<{
	(e: 'closed'): void,
	(e: 'opened'): void
}>();
addIcons(MdFilterlistRound);
</script>
<template>
	<div class="relative force-front" ref="filterDialog">
		<div class="hover:bg-slate-300/60 cursor-pointer rounded-full p-1">
			<VIcon class="" name="md-filterlist-round" :scale="iconSize*1.2" @click="is.open = true"></VIcon>
		</div>
		<div v-if="is.open" class="force-front border border-solid border-slate-300 absolute min-w-[200px] min-h-[200px] bg-white" :style="x" ref="innerDialog">
			<slot></slot>
		</div>
	</div>
</template>