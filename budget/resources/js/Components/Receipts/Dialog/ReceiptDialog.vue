<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { defineComponent, ref, onMounted, reactive, nextTick, computed } from 'vue';
import { Form } from 'vee-validate';
import moment from 'moment';
import receiptApi from '@/services/Receipts/receiptApi';
import { BiPencilSquare } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { TableItem, TableHeader, TableSort } from '@/types/table';

import ReceiptDialogItem from './ReceiptDialogItem.vue';
import BasicDialog from '@/Components/BasicDialog.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import CancelButton from '@/Components/Inputs/CancelButton.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptDocumentDialog from './ReceiptDocumentDialog.vue';
import SortableTable from '@/Components/Tables/SortableTable.vue';
import { ReceiptItemCategory } from '@/types/Receipts/receiptItemCategory';

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
const headers = [
	{ name: 'name' },
	{ name: 'count',  options: { sortKey: 'count' } },
	{ name: 'cost', options: {
		sortKey: 'cost',
		finance: { decimals: 2 }
	}},
	{ name: 'total', options: {
		sortKey: 'total',
		finance: { decimals: 2 }
	}},
	{ name: 'category' }
] as TableHeader[];

const stats = (): TableItem[] => {
	return receipt.items.map(item => {
		return {
			key: item.name,
			item: {
				name: item.name,
				count: item.count,
				cost: item.cost,
				total: item.cost * item.count,
				category: (item.category as ReceiptItemCategory).name
			}
		} as TableItem
	}) as Array<TableItem>;
}
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
	nextTick(() => {focusLastItem()});
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
			const response = receipt.id ? await receiptApi.updateReceipt(receipt) : await receiptApi.createReceipt(receipt);
			const success = receipt.id ? response.status === 200 : response.status === 201;
			if (success) {
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

const deleteItem = (index: any) => {
	receipt.items.splice(index, 1);
	if (receipt.items.length == 0) {
		nextTick(() => addNewReceiptItem());
	}
}

const setReceipt = (newReceipt: Receipt) => {
	receipt = reactive(newReceipt);
}

const show = () => {
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
const cancel = () => {
	if (receipt.id) {
		// nextTick(() => is.editing = false);
		dialog.value?.hide();
	} else {
		dialog.value?.hide();
	}
}
//Receipt documents
const showReceiptDocumentDialog = () => {
	receiptDocumentDialogShowing.value = true;
	nextTick(() => {
		documentDialog.value?.show();
	})
}

const cost = computed((): number => {
	if (is.editing) {
		let total = 0;
		receipt.items.forEach(item => {
			total += (item.cost * item.count);
		});
		return total;
	}
	return receipt.cost ?? 0;
});

const title = computed((): string => {
	let title = 'Receipt';
	if (is.editing) {
		if (receipt.id) {
			title = 'Edit Recceipt';
		} else {
			title = 'Create Receipt';
		}
	}
	return title;
})
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
		class="flex flex-col min-w-full min-h-full max-h-screen md:min-w-[50vw] md:min-h-[30vh] backdrop-blur-[1px] rounded-none md:rounded-lg"
		:persistent="is.editing"
		blur
		:title="title"
		@destroy="$emit('destroy')"
	>
	<!-- Receipt Items -->
	<div :class="`${is.editing ? '' : 'overflow-auto'} `">
		<VForm ref="receiptForm">
			<!-- Header Information -->
			<template v-if="is.editing">
				<div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1 relative">
					<VueTextField v-model="receipt.reference" placeholder="Receipt Number" name="receiptnumber" />
					<VueTextField v-model="receipt.store" placeholder="Store Name" name="store" />
					<VueTextField v-model="receipt.location" placeholder="Location" name="location" />
					<VueTextField v-model="receipt.date" :rules="validDate" placeholder="Date" name="date" type="date" required/>
				</div>
			</template>
			<template v-else>
				<div class="flex flex-row">
					<div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1 relative flex-grow">
						<span class="font-semibold text-md">Receipt <span class="hidden md:inline">Number</span></span>
						<span class="font-semibold text-md">Store</span>
						<span class="inline md:hidden text-ellipsis">{{ receipt.reference ?? '-' }}</span>
						<span class="inline md:hidden">{{ receipt.store ?? '-' }}</span>
						<span class="font-semibold text-md">Location</span>
						<span class="font-semibold text-md">Date</span>
						<span class="hidden md:inline">{{ receipt.reference ?? '-' }}</span>
						<span class="hidden md:inline">{{ receipt.store ?? '-' }}</span>
						<span>{{ receipt.location ?? '-' }}</span>
						<span>{{ receipt.date }}</span>
					</div>
					<div class="text-center py-1 mx-2 select-none cursor-pointer self-center ml-auto px-3 rounded-sm bg-orange-400 hover:bg-orange-500 inline-flex flex-row flex-shrink"  @click="is.editing = true">
						<VIcon name="bi-pencil-square" class="self-center"/>
						<span class="pl-1 hidden md:inline">Edit</span>
					</div>
				</div>
			</template>
			<!-- Item Table -->
			<div class="table mt-4 pt-1 w-full max-w-full">
				<SortableTable v-if="!is.editing"
					:headers="headers"
					:items="stats()"
					:count="9999"
					class="overflow-x-hidden"
				/>
				<div v-else class="overflow-y-auto overflow-x-hidden overscroll max-h-[70vh] pb-8 min-h-[40vh]">
					<div class="table-head grid grid-cols-6 force-front gap-2 mb-2 p-1 md:px-2 sticky top-0 text-sm md:text-default">
						<span>Name</span>
						<span class="text-center">Count</span>
						<span class="text-center">Cost</span>
						<span>Total</span>
						<span class="break-all col-span-2">Category</span>
					</div>
					<ReceiptDialogItem :editing="is.editing" v-for="(item, index) in receipt.items" :item="item" @delete="deleteItem(index)" :key="`${item.id}-${index}`"></ReceiptDialogItem>
				</div>
			</div>
		</VForm>
	</div>
	<div v-if="error.show" class="text-red-400 italic">
		{{ error.message }}
	</div>

	<!-- Receipt Footer -->
	<div class="mt-auto pt-2 border-t border-solid border-neutral-500 md:flex md:flex-row grid grid-cols-2 gap-y-2">
		<template v-if="is.editing">
			<div class="py-1 select-none cursor-pointer self-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80 text-center" @click="addNewReceiptItem">Add Item</div>
		</template>
		<div v-if="receipt.documents.length || is.editing" class="py-1 ml-2 select-none cursor-pointer self-center text-center px-2 rounded-sm bg-neutral-300 hover:bg-neutral-500/80" @click="showReceiptDocumentDialog">
			Documents ({{ receipt.documents?.length ?? 0 }})
		</div>
		<div v-else class="py-1 ml-2">&nbsp</div>
		<span :class="[{'absolute': !is.editing}, `left-0 right-0 mx-auto text-center font-semibold text-lg self-center pointer-events-none`]">Total: ${{ cost.toFixed(2) }}</span>
		<div class="ml-auto inline-flex flex-row">
			<template v-if="is.editing">
				<ConfirmButton class="py-1 text-md self-center" @click="saveReceipt" />
				<CancelButton @click="cancel" class="py-1 text-md self-center ml-2" />
			</template>
		</div>
	</div>
	</BasicDialog>
	<ReceiptDocumentDialog
		v-if="receiptDocumentDialogShowing"
		ref="documentDialog"
		@destroy="receiptDocumentDialogShowing = false"
		:editing="is.editing"
		:receipt="receipt"
	/>
</template>