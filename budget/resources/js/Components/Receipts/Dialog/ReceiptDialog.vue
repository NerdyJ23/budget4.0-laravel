<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { defineComponent, ref, onMounted, reactive, nextTick } from 'vue';
import { Form } from 'vee-validate';
import moment from 'moment';
import receiptApi from '@/services/Receipts/receiptApi';

import ReceiptDialogItem from './ReceiptDialogItem.vue';
import BasicDialog from '@/Components/BasicDialog.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import CancelButton from '@/Components/Inputs/CancelButton.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';

import { addIcons } from "oh-vue-icons";
import { IoCloseOutline } from "oh-vue-icons/icons";

//Date
const receipt: Receipt = reactive({
	store: '',
	date: '',
	location: '',
	reference: ''
});

const items: ReceiptItem[] = reactive([]);
const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
const receiptForm = ref<InstanceType<typeof Form> | null>(null);
const error = ref({
	show: false,
	message: ''
})

//Methods
const validDate = (value: string) => {
	console.log(value);
	if (value.trim() == '') {
		return 'Date is not valid';
	}
	const newDate = moment(value);
	const now = moment();

	// console.log(`date entered: ${newDate.getTime()} < ${now.getTime()} ?= ${newDate.getTime() < now.getTime()}`);
	return newDate.isBefore(now) ? true : 'Date cannot be in the future';
}
const is = reactive({
	loading: false
})

const reset = () => {
	receipt.store = '';
	receipt.date = '';
	receipt.location = '';
	receipt.reference = '';
	items.length = 0;
	addNewReceiptItem();
	receiptForm.value?.resetForm();
}
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
}
const saveReceipt = () => {
	const meta = receiptForm.value?.getMeta();
	receiptForm.value?.validate();
	nextTick(async () => {
		if (meta && meta.dirty && meta.valid) {
			dialog.value?.setLoading(true);
			const response = await receiptApi.createReceipt(receipt, items);
			console.log(response);
			if (response.status === 201) {
				setTimeout(() => {
					dialog.value?.setLoading(false);
					dialog.value?.hide();
				}, 800);
			} else {
				error.value.show = true;
				error.value.message = response.data.errors;
			}
		}
	})
}

const deleteItem = (index: number) => {
	items.splice(index, 1);
	if (items.length == 0) {
		nextTick(() => addNewReceiptItem());
	}
}
//Setup
addIcons(IoCloseOutline);
onMounted(() => {addNewReceiptItem(); });
defineExpose({dialog, reset});
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
	<div>
		<VForm ref="receiptForm">
			<div class="grid grid-cols-4 gap-4">
				<VueTextField v-model="receipt.reference" placeholder="Receipt Number" name="receiptnumber" />
				<VueTextField v-model="receipt.store" placeholder="Store Name" name="store" />
				<VueTextField v-model="receipt.location" placeholder="Location" name="location" />
				<VueTextField v-model="receipt.date" :rules="validDate" placeholder="Date" name="date" type="date" required/>
			</div>
			<div class="table mt-4 pt-1 sticky">
				<div class="table-head grid grid-cols-5 gap-2 mb-2 px-2">
					<span class="table-head-text">Name</span>
					<span class="table-head-text text-center">Count</span>
					<span class="table-head-text text-center">Cost</span>
					<span class="table-head-text">Total</span>
					<span class="table-head-text">Category</span>
				</div>
				<div class="overflow-y-scroll max-h-[70vh] pb-8 min-h-[40vh]">
					<ReceiptDialogItem v-for="(item, index) in items" :item="item" @delete="deleteItem(index)"></ReceiptDialogItem>
				</div>
			</div>
		</VForm>
	</div>
	<div v-if="error.show" class="text-red-400 italic">
		{{ error.message }}
	</div>
	<!-- Receipt Footer -->
	<div class="pt-4 border-t border-solid border-neutral-500 flex flex-row">
		<div class=" py-1 select-none cursor-pointer self-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="addNewReceiptItem">Add Item</div>
		<ConfirmButton class="py-1 text-md self-center ml-auto" @click="saveReceipt"/>
		<CancelButton @click="($refs.dialog as typeof BasicDialog).hide()" class="py-1 text-md self-center ml-2" />
	</div>
	</BasicDialog>
</template>