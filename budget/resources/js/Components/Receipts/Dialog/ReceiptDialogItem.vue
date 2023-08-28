<script setup lang="ts">
import { defineComponent, reactive, computed } from 'vue';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import ReceiptStore from '@/store/receiptStore';


import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptCategoryDropdown from '@/Components/Receipts/ReceiptCategoryDropdown.vue';

import { addIcons } from "oh-vue-icons";
import { MdDeleteforeverOutlined, HiSolidPencilAlt } from "oh-vue-icons/icons";

const props = withDefaults(defineProps<{
	item: ReceiptItem,
	editing?: boolean
}>(), {
	editing: false
});

const is = reactive({
	editing: props.editing
});
const item: ReceiptItem = props.item;
const id: string = item.id ?? crypto.randomUUID();

const total = computed(() => { return ((item.cost * item.count) ?? 0 )})

const validName = (value: string) => { return value.length == 0 ? 'Name is required' : true };
const validCategory = (value: string) => { return value.length == 0 ? 'Category cannot be blank' : true };
const defaultNumber = (item: any, defaultValue: number = 0): number => {
	return item == '' ? defaultValue : item as number;
}
const setCategory = (cat: string) => {
	item.category = cat;
}

addIcons(MdDeleteforeverOutlined, HiSolidPencilAlt);
defineExpose({item});
</script>

<script lang="ts">
defineComponent({
	name: "ReceiptDialogItem"
})
</script>
<template>
	<div class="relative grid grid-cols-5 gap-2 px-2 py-1 even:bg-slate-100 odd:bg-slate-200 hover:bg-amber-400/20">
		<template v-if="is.editing">
			<VueTextField v-model="item.name" :rules="validName" class="self-center" placeholder="Item Name" :name="`item_name-${id}`" />
			<VueTextField v-model="item.count" class="self-center mx-auto w-[75px]" :name="`item_count-${id}`" @blur="item.count = defaultNumber(item.count)"/>
			<div class="inline-flex flex-row w-[100px] self-center justify-self-center">
				<span class="self-center text-xl">$</span>
				<VueTextField v-model="item.cost" :name="`item_cost-${id}`" @blur="item.cost = defaultNumber(item.cost)"/>
			</div>
			<span class="text-lg self-center align-self-center">$ {{ total }}</span>
			<div class="self-center inline-flex flex-row">
				<ReceiptCategoryDropdown
					:rules="validCategory"
					:items="ReceiptStore.state.categories"
					@changed="(n: string) => setCategory(n)"
					:key="`item_category-${id}`"
				/>
				<div class="icon-button ml-2 p-1">
					<VIcon v-if="is.editing" class="fill-red-600" name="md-deleteforever-outlined" scale="1.5" @click="$emit('delete')"></VIcon>
					<VIcon v-else class=" fill-blue-400" name="hi-solid-pencil-alt" scale="1.5"></VIcon>
				</div>
			</div>
		</template>
		<template v-else>
			<span class="w-full text-sm">{{ item.name }}</span>
			<span class="w-full text-sm text-center">{{ item.count }}</span>
			<span class="w-full text-sm text-center">${{ item.cost.toFixed(2) }}</span>
			<span class="w-full text-sm">${{ total.toFixed(2) }}</span>
			<span class="w-full text-sm">{{ (item.category as ReceiptItemCategory).name }}</span>
		</template>
	</div>
</template>
<style lang="scss">
.table {
	&-head {
		@apply bg-slate-200;
		&-text {
			@apply text-lg font-semibold;
		}
	}
}
</style>