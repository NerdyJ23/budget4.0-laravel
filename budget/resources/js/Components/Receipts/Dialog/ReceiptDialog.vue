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

//Props
const props = withDefaults(defineProps<{
	receipt?: Receipt
	editing?: boolean
}>(), {
	editing: false,
	receipt: Receipt => ({
		store: '',
		date: '',
		location: '',
		reference: '',
		items: []
	})
});

//Data
let receipt: Receipt = reactive(props.receipt);

const is = reactive({
	loading: false,
	editing: props.editing
})

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


const reset = () => {
	receipt.store = '';
	receipt.date = '';
	receipt.location = '';
	receipt.reference = '';
	receipt.items.length = 0;
	addNewReceiptItem();
	receiptForm.value?.resetForm();
}
const addNewReceiptItem = () => {
	const item: ReceiptItem = {
		id: crypto.randomUUID(),
		name: '',
		count: 1,
		cost: 0,
		category: ''
	};
	receipt.items.push(item);
	nextTick(() => {focusLastItem()}) ;
}
const focusLastItem = () => {
	const items = document.querySelectorAll('[name ^= item_name]');
	(items[items.length-1] as HTMLInputElement)?.focus();
}
const saveReceipt = () => {
	const meta = receiptForm.value?.getMeta();
	receiptForm.value?.validate();
	nextTick(async () => {
		if (meta && meta.dirty && meta.valid) {
			dialog.value?.setLoading(true);
			const response = await receiptApi.createReceipt(receipt);
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

const deleteItem = (index: any) => {
	console.log(index);
	// const i = items.indexOf(index);
	// console.log(i);
	const item = receipt.items[index];
	console.log(item);
	receipt.items.splice(index, 1);
	if (receipt.items.length == 0) {
		nextTick(() => addNewReceiptItem());
	}
}

const setReceipt = (newReceipt: Receipt) => {
	receipt = newReceipt;
}

const show = () => {dialog.value?.show();}
const hide = () => {dialog.value?.hide();}
const cost = ():number => {
	if (receipt.cost && receipt.cost > 0) {
		return receipt.cost;
	}
	let total = 0;
	receipt.items.forEach(item => {
		total += (item.cost * item.count);
	});
	return total;
}

//Setup
addIcons(IoCloseOutline);
onMounted(() => {
	if (!props.receipt.id) {
		addNewReceiptItem();
	}
});
defineExpose({dialog, reset, show, hide, setReceipt});
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
	<BasicDialog ref="dialog" class="flex flex-col min-w-[50vw]" :persistent="is.editing" blur>
	<!-- Header -->
	<div class="flex flex-row">
		<span class="header-text mr-auto">{{ is.editing ? 'Create' : ''}} Receipt</span>
		<span class="icon-button hover:animate-hop-once" @click="($refs.dialog as typeof BasicDialog).hide()">
			<VIcon name="io-close-outline" label="Close" scale="1.3"></VIcon>
		</span>
	</div>

	<!-- Receipt Items -->
	<div>
		<VForm ref="receiptForm">
			<div class="grid grid-cols-4 gap-4">
				<template v-if="editing">
					<VueTextField v-model="receipt.reference" placeholder="Receipt Number" name="receiptnumber" />
					<VueTextField v-model="receipt.store" placeholder="Store Name" name="store" />
					<VueTextField v-model="receipt.location" placeholder="Location" name="location" />
					<VueTextField v-model="receipt.date" :rules="validDate" placeholder="Date" name="date" type="date" required/>
				</template>
				<template v-else>
					<span class="font-semibold text-md">Receipt Number</span>
					<span class="font-semibold text-md">Store</span>
					<span class="font-semibold text-md">Location</span>
					<span class="font-semibold text-md">Date</span>
					<span>{{ receipt.reference }}</span>
					<span>{{ receipt.store }}</span>
					<span>{{ receipt.location }}</span>
					<span>{{ receipt.date }}</span>
				</template>
			</div>
			<div class="table mt-4 pt-1 w-full">
				<div class="overflow-y-scroll overscroll max-h-[70vh] pb-8 min-h-[40vh]">
					<div class="table-head grid grid-cols-5 force-front gap-2 mb-2 px-2 sticky top-0">
						<span class="table-head-text">Name</span>
						<span class="table-head-text text-center">Count</span>
						<span class="table-head-text text-center">Cost</span>
						<span class="table-head-text">Total</span>
						<span class="table-head-text">Category</span>
					</div>
					<ReceiptDialogItem :editing="is.editing" v-for="(item, index) in receipt.items" :item="item" @delete="deleteItem(index)" :key="item.id"></ReceiptDialogItem>
				</div>
			</div>
		</VForm>
	</div>
	<div v-if="error.show" class="text-red-400 italic">
		{{ error.message }}
	</div>
	<!-- Receipt Footer -->
	<div class="pt-4 border-t border-solid border-neutral-500 flex flex-row">
		<div v-if="is.editing" class="py-1 select-none cursor-pointer self-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="addNewReceiptItem">Add Item</div>
		<span class="ml-auto font-semibold text-lg self-center">Total: ${{ cost().toFixed(2) }}</span>
		<div class="ml-auto inline-flex flex-row">
			<template v-if="is.editing">
				<ConfirmButton class="py-1 text-md self-center" @click="saveReceipt"/>
				<CancelButton @click="($refs.dialog as typeof BasicDialog).hide()" class="py-1 text-md self-center ml-2" />
			</template>
		</div>
	</div>
	</BasicDialog>
</template>
<style lang="scss">
.overscroll {
	overscroll-behavior: contain;
}
</style>