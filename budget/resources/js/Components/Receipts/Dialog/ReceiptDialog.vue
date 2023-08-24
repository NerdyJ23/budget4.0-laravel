<script setup lang="ts">
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { defineComponent, ref, onMounted, reactive, nextTick } from 'vue';

import BasicDialog from '@/Components/BasicDialog.vue';
import { Form } from 'vee-validate';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptDialogItem from './ReceiptDialogItem.vue';

import { addIcons } from "oh-vue-icons";
import { IoCloseOutline } from "oh-vue-icons/icons";

const items: ReceiptItem[] = reactive([]);
const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
const validDate = (value: string) => {
	const newDate: Date = new Date(value);
	return newDate.getTime() < Date.now() ? true : 'Date cannot be in the future 😎';
};

addIcons(IoCloseOutline);
const addNewReceiptItem = () => {
	const item: ReceiptItem = {
		name: '',
		count: 1,
		cost: 0,
		category: ''
	};
	items.push(item);
	nextTick(() => {focusLastItem()}) ;
}
const focusLastItem = () => {
	const items = document.querySelectorAll('[name ^= item_name]');
	(items[items.length-1] as HTMLInputElement).focus();
	console.log(items);
}
onMounted(() => {addNewReceiptItem(); });
defineExpose({dialog});
</script>

<script lang="ts">
export default defineComponent({
	name: 'ReceiptDialog',
	components: {
		VForm: Form,
		VueTextField
	}

});
</script>
<template>
 <BasicDialog ref="dialog" class="flex flex-col max-h-screen" persistent blur>
	<!-- Header -->
	<div class="flex flex-row">
		<span class="header-text mr-auto">Create Receipt</span>
		<span class="icon-button hover:animate-hop-once" @click="($refs.dialog as typeof BasicDialog).hide()">
			<VIcon name="io-close-outline" label="Close" scale="1.3"></VIcon>
		</span>
	</div>

	<!-- Receipt Items -->
	<div class="min-h-min">
		<VForm>
			<div class="grid grid-cols-4 gap-4">
				<VueTextField placeholder="Receipt Number" name="receiptnumber" />
				<VueTextField placeholder="Store Name" name="store" />
				<VueTextField placeholder="Location" name="location" />
				<VueTextField :rules="validDate" placeholder="Date" name="date" type="date" required/>
			</div>
			<div class="table mt-4 pt-1 sticky">
				<div class="table-head grid grid-cols-5 gap-2 mb-2 px-2">
					<span class="table-head-text">Name</span>
					<span class="table-head-text text-center">Count</span>
					<span class="table-head-text text-center">Cost</span>
					<span class="table-head-text">Total</span>
					<span class="table-head-text">Category</span>
				</div>
				<div class="overflow-y-auto max-h-[70vh]">
					<ReceiptDialogItem v-for="item in items" :item="item"></ReceiptDialogItem>
				</div>
			</div>
		</VForm>
	</div>

	<!-- Receipt Footer -->
	<div class="pt-4 border-t border-solid border-neutral-500">
		<input type="button" class="px-2 py-1 mb-1 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="addNewReceiptItem" value="Add Item"/>
	</div>

 </BasicDialog>
</template>