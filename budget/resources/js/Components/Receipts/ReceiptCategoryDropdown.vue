<script setup lang="ts">
import { defineComponent, reactive, ref, computed, watch, onMounted } from 'vue';
import { addIcons } from "oh-vue-icons";
import { MdArrowdropdown, MdArrowdropup } from "oh-vue-icons/icons";
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import { nextTick } from 'vue';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import { useReceiptStore } from '@/store/receiptPiniaStore';

//Definitions
const props = withDefaults(defineProps<{
	items?: ReceiptItemCategory[],
	id?: string,
	rules?: Function
}>(), {
});

//Variables
const ReceiptStore = useReceiptStore();
const filterValue = ref("");
const id = props.id ?? crypto.randomUUID();

const is = reactive({
	show: false
});
const container= ref<HTMLElement | null>(null);
const inputfield = ref<InstanceType<typeof VueTextField> | null>(null);
const filteredItems = computed(() => {
	return props.items?.filter((item: ReceiptItemCategory) => {
		return item.name.includes(filterValue.value.toUpperCase());
	});
});

const width = computed(() => {
	if (inputfield.value) {
		return `width: ${inputfield.value.selfInput!.clientWidth}px;`;
	}
	return `width: 0px`;
});
//Functions
const showOptions = () => {
	is.show = true;
	nextTick(() => { offsetItems() })
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

const updateFilterValue = (value: string) => {
	filterValue.value = value;
	if (inputfield.value && inputfield.value.selfInput) {
		inputfield.value.selfInput.value = value;
	}
}

//Final Setups
onMounted(() => {
	filterValue.value = ReceiptStore.filter.category;
});

watch(filterValue, () => { emit('changed', filterValue.value) })
const emit = defineEmits<{
	(e: 'changed', value: any): void
}>();

defineExpose({updateFilterValue});
addIcons(MdArrowdropdown, MdArrowdropup);
</script>
<script lang="ts">
export default defineComponent({
	name: "ReceiptCategoryDropdown"
});
</script>
<template>
	<div class="grid grid-cols-1">
		<VueTextField
			:name="id"
			ref="inputfield"
			v-model="filterValue"
			v-bind="$attrs"
			@focused="showOptions"
			@input.change="showOptions"
			@changed="(value: string) => updateFilterValue(value)"
			@blur="hideOptions"
			:rules="rules"
			:key="`dropdown-${id}`"
			/>
		<div v-if="is.show" class="relative" ref="container" :style="width">
			<span class="autocomplete-item uppercase"
				v-if="filterValue.trim().length >= 2"
				v-for="item in filteredItems"
				@mousedown="filterValue = item.name"
			>{{ item.name }}</span>
			<span v-else class="autocomplete-item italic text-sm text-left">
				Begin typing...
			</span>
		</div>
	</div>
</template>
<style lang="scss">
.autocomplete-item {
	z-index: 999;
	@apply hover:bg-zinc-200 absolute overflow-hidden text-ellipsis cursor-pointer bg-zinc-100 border-x border-b border-solid border-slate-300 px-4 py-2 w-full;
}
</style>