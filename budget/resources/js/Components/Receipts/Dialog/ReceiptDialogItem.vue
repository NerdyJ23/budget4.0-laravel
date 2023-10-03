<script setup lang="ts">
import { defineComponent, ref, computed, watch, reactive } from 'vue';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';
import { useReceiptStore } from '@/store/receiptPiniaStore';

import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptCategoryDropdown from '@/Components/Receipts/ReceiptCategoryDropdown.vue';
import CancelButton from '@/Components/Inputs/CancelButton.vue';

import { addIcons } from "oh-vue-icons";
import { MdDeleteforeverOutlined, HiSolidPencilAlt } from "oh-vue-icons/icons";

const props = withDefaults(defineProps<{
	item: ReceiptItem,
	editing?: boolean
}>(), {
	editing: false
});
const ReceiptStore = useReceiptStore();
props.item.category = (props.item.category as ReceiptItemCategory).name ?? props.item.category;

const item: ReceiptItem = reactive(props.item);
const id: string = item.id ?? crypto.randomUUID();

const total = computed(() => { return ((item.cost * item.count) ?? 0 )})

const validName = (value: string) => { return value.length == 0 ? 'Name is required' : true };
const validCategory = (value: string) => { return value.length == 0 ? 'Category cannot be blank' : true };
const defaultNumber = (item: any, defaultValue: number = 0): number => {
	return item == '' ? defaultValue : item as number;
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
	<div class="relative grid grid-cols-4 md:grid-cols-6 gap-2 px-2 py-1 even:bg-slate-100 odd:bg-slate-200 hover:bg-amber-400/20">
		<template v-if="editing">
			<VueTextField v-model="item.name" :rules="validName" class="self-center col-span-4 md:col-span-1" placeholder="Item Name" :name="`item_name-${id}`" />
			<VueTextField v-model="item.count" type="number" class="self-center md:px-0 min-w-[75px]" :name="`item_count-${id}`" @blur="item.count = defaultNumber(item.count)"/>
			<div class="inline-flex flex-row self-center md:justify-self-center">
				<span class="self-center md:text-xl">$</span>
				<VueTextField v-model="item.cost" :name="`item_cost-${id}`" type="number" @blur="item.cost = defaultNumber(item.cost)"/>
			</div>
			<span class="hidden md:inline md:text-lg self-center align-self-center">$ {{ total }}</span>
			<div class="self-center inline-flex flex-row col-span-2 md:col-span-2">
				<ReceiptCategoryDropdown
					v-model="item.category"
					@changed="(value: string) => item.category = value"
					:rules="validCategory"
					class="self-center"
					:items="ReceiptStore.categories"
					:key="`item_category-${id}`"
				/>

				<div class="hidden md:inline icon-button ml-2 p-1">
					<VIcon v-if="editing" class="fill-red-600" name="md-deleteforever-outlined" scale="1.5" @click="$emit('delete')"></VIcon>
					<VIcon v-else class=" fill-blue-400" name="hi-solid-pencil-alt" scale="1.5"></VIcon>
				</div>
			</div>
			<!-- Mobile delete button -->
			<div class="inline md:hidden col-span-4 text-center">
				<CancelButton @click="$emit('delete')" class="rounded-md bg-red-500/80">Remove Item</CancelButton>
			</div>
		</template>
		<template v-else>
			<span class="w-full text-sm">{{ item.name }}</span>
			<span class="w-full text-sm text-center">{{ item.count }}</span>
			<span class="w-full text-sm text-center">${{ item.cost.toFixed(2) }}</span>
			<span class="w-full text-sm">${{ total.toFixed(2) }}</span>
			<span class="w-full text-sm col-span-2">{{ item.category }}</span>
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