<script setup lang="ts">
import { defineComponent, reactive, ref, computed, watch } from 'vue';
import { addIcons } from "oh-vue-icons";
import { MdArrowdropdown, MdArrowdropup } from "oh-vue-icons/icons";
import VueTextField from './VueTextField.vue';
import { nextTick } from 'vue';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';

const filterValue = ref("");

const is = reactive({
	show: false
});
const container= ref<HTMLElement | null>(null);

const showOptions = () => {
	is.show = true;
	nextTick(() => {offsetItems()})
}
const hideOptions = () => {nextTick(() => is.show = false);}
const offsetItems = () => {
	const elements = container.value?.getElementsByClassName('autocomplete-item');
	if (elements) {
		for (let i = 0; i < elements.length; i++) {
			if (i != 0) {
				(elements[i] as HTMLElement).style.transform = `translateY(${i * elements[i-1].clientHeight}px)`;
			}
		}
	}
}
const filteredItems = computed(() => {
	return props.items?.filter((item: ReceiptItemCategory) => {
		return item.name.includes(filterValue.value.toUpperCase());
	});
})

watch(filterValue, () => { emit('model.changed', filterValue.value) });
// const filter = () => {
// 	console.log(filterValue);
// 	console.log(filterValue.value.trim().length);
// 	if (filterValue.value.trim().length > 2) {
// 		nextTick(() => offsetItems());
// 		return true;
// 	}
// 	return false;
// };

//Definitions
const props = withDefaults(defineProps<{
	items?: ReceiptItemCategory[],
	id?: string,
	rules?: Function
}>(), {
	id: `dropdown-${crypto.randomUUID()}`
});

const emit = defineEmits<{
	(e: 'model.changed', value: any): void
}>();

addIcons(MdArrowdropdown, MdArrowdropup);
</script>
<script lang="ts">
export default defineComponent({
	name: "DropdownMenu"
});
</script>
<template>
	<div class="grid grid-cols-1">
		<VueTextField
			:name="id"
			v-model="filterValue"
			v-bind="$attrs"
			@focused="showOptions"
			@input.change="showOptions"
			@blur="hideOptions"
			:rules="rules"
			/>
		<div v-if="is.show" class="relative" ref="container">
			<span
				class="autocomplete-item"
				v-if="filterValue.trim().length >= 2"
				v-for="item in filteredItems"
				@mousedown="filterValue = item.name"
			>{{ item.name }}</span>
		</div>
	</div>
</template>
<style lang="scss">
.autocomplete-item {
	z-index: 999;
	@apply hover:bg-zinc-200 absolute overflow-hidden text-ellipsis cursor-pointer bg-zinc-100 border-x border-b border-solid border-slate-300 p-4 w-full left-0 right-0;
}
</style>