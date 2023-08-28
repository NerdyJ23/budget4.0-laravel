<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { defineComponent, ref, onMounted, reactive, nextTick, onUnmounted } from 'vue';
import { Form } from 'vee-validate';
import moment from 'moment';
import receiptApi from '@/services/Receipts/receiptApi';
import { BiPencilSquare } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";

import ReceiptDialogItem from './ReceiptDialogItem.vue';
import BasicDialog from '@/Components/BasicDialog.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import CancelButton from '@/Components/Inputs/CancelButton.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptDocumentDialog from './ReceiptDocumentDialog.vue';

//Props
const props = withDefaults(defineProps<{
	editing?: boolean
}>(), {
	editing: false
});

//Data
let receipt: Receipt = reactive({
	store: '',
	date: '',
	location: '',
	reference: '',
	items: [],
	documents: []
});

const is = reactive({
	loading: false,
	editing: props.editing
});
const error = ref({
	show: false,
	message: ''
});
const receiptDocumentDialogShowing = ref(false);

//Refs
const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
const documentDialog = ref<InstanceType<typeof ReceiptDocumentDialog> | null>(null);
const receiptForm = ref<InstanceType<typeof Form> | null>(null);

//Methods
const validDate = (value: string) => {
	console.log(value);
	if (value.trim() == '') {
		return 'Date is not valid';
	}
	const newDate = moment(value);
	const now = moment();
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

			if (response.status === 201) {
				if (receipt.documents.length > 0) {
					let fullErrors = "";
					const createdReceipt: Receipt = {
						id: response.data.result[0].id,
						store: response.data.result[0].store,
						date: response.data.result[0].date,
						location: response.data.result[0].location,
						reference: response.data.result[0].reference,
						items: [],
						documents: []
					};

					for(const file of receipt.documents) {
						if (file instanceof File) {
							const response = await receiptApi.uploadDocument(file, createdReceipt);
							if (response.status != 201) {
								fullErrors += `${response.data.errors}\n`;
							}
						}
					}

					if (fullErrors.length > 1) {
						console.error(JSON.stringify(fullErrors))
					}
				}
				setTimeout(() => {
					window.location.reload();
					// dialog.value?.setLoading(false);
					// dialog.value?.hide();
				}, 800);
			} else {
				error.value.show = true;
				error.value.message = response.data.errors;
			}
		}
	})
}

const uploadDocument = async (file: File) => {
	const response = await receiptApi.uploadDocument(file, receipt);
	if (response.status === 201) {
		return;
	}
	return response.data.errors;
}
const deleteItem = (index: any) => {
	receipt.items.splice(index, 1);
	if (receipt.items.length == 0) {
		nextTick(() => addNewReceiptItem());
	}
}

const setReceipt = (newReceipt: Receipt) => {
	receipt = newReceipt;
}

const show = () => {
	receiptDocumentDialogShowing.value = true;
	dialog.value?.show();
	nextTick(() => {
		if (!receipt.id) {
			addNewReceiptItem();
		}
	})
}
const hide = () => {
	dialog.value?.hide();
}

//Receipt documents
const showReceiptDocumentDialog = () => {
	receiptDocumentDialogShowing.value = true;
	nextTick(() => {
		documentDialog.value?.show();
	})
}

const cost = (): number => {
	if (receipt.cost && receipt.cost > 0) {
		return receipt.cost;
	}
	let total = 0;
	receipt.items.forEach(item => {
		total += (item.cost * item.count);
	});
	return total;
}

addIcons(BiPencilSquare);
defineExpose({dialog, reset, show, hide, setReceipt});
defineEmits(['destroy']);
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
	<BasicDialog
		ref="dialog"
		class="flex flex-col min-w-[50vw]"
		:persistent="is.editing"
		blur
		:title="`${is.editing ? 'Create Receipt' : ''}`"
		@destroy="$emit('destroy')"
	>
	<!-- Receipt Items -->
	<div>
		<VForm ref="receiptForm">
			<div class="grid grid-cols-4 gap-x-4 gap-y-1 relative">
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
					<span>{{ receipt.reference ?? '-' }}</span>
					<span>{{ receipt.store ?? '-' }}</span>
					<span>{{ receipt.location ?? '-' }}</span>
					<span>{{ receipt.date }}</span>
					<div class="text-center py-1 mx-2 select-none cursor-pointer self-center px-3 rounded-sm bg-orange-400 hover:bg-orange-500 absolute right-0 inline-flex flex-row">
						<VIcon name="bi-pencil-square" class="self-center"/>
						<span class="pl-1" @click="is.editing = true">Edit</span>
					</div>
				</template>
			</div>
			<div class="table mt-4 pt-1 w-full">
				<div class="overflow-y-auto overscroll max-h-[70vh] pb-8 min-h-[40vh]">
					<div class="table-head grid grid-cols-5 force-front gap-2 mb-2 px-2 sticky top-0">
						<span class>Name</span>
						<span class="text-center">Count</span>
						<span class="text-center">Cost</span>
						<span class>Total</span>
						<span class>Category</span>
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
		<template v-if="is.editing">
			<div class="py-1 select-none cursor-pointer self-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="addNewReceiptItem">Add Item</div>
		</template>
		<div v-if="receipt.documents.length || is.editing" class="py-1 ml-2 select-none cursor-pointer self-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="showReceiptDocumentDialog">Documents ({{ receipt.documents?.length ?? 0 }})</div>
		<div v-else class="py-1 ml-2">&nbsp</div>
		<span class="absolute left-0 right-0 mx-auto text-center font-semibold text-lg self-center pointer-events-none">Total: ${{ cost().toFixed(2) }}</span>
		<div class="ml-auto inline-flex flex-row">
			<template v-if="is.editing">
				<ConfirmButton class="py-1 text-md self-center" @click="saveReceipt"/>
				<CancelButton @click="($refs.dialog as typeof BasicDialog).hide()" class="py-1 text-md self-center ml-2" />
			</template>
		</div>
		<template v-if="!is.editing">
		</template>
	</div>
	</BasicDialog>
	<ReceiptDocumentDialog
		v-if="receiptDocumentDialogShowing"
		ref="documentDialog"
		@destroy="receiptDocumentDialogShowing = false"
		:new-receipt="is.editing"
		:receipt="receipt"
	/>
</template>