<script setup lang="ts">
import { defineComponent, ref, onMounted } from 'vue';
import { Receipt } from '@/types/Receipts/receipt';
import { Head, usePage } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import ReceiptDialog from '@/Components/Receipts/Dialog/ReceiptDialog.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptCategoryDropdown from '@/Components/Receipts/ReceiptCategoryDropdown.vue';
import store from '@/store';
import ReceiptStore from '@/store/receiptStore';
import { nextTick } from 'vue';

const matches = route('receipts') == window.location.origin + window.location.pathname;
const location = window.location.href;
const receiptTable = ref<InstanceType<typeof ReceiptTable> | null>(null);
const input = ref<InstanceType<typeof VueTextField> | null>(null);

	const reload = () => {
	receiptTable.value?.reload();
};

onMounted(() => {
	store.dispatch('loadCategories');
})

const filteredReceipts = () => {
	let filteredReceipts: Array<Receipt> = usePage().props.receipts;
	const nameFilter = (ReceiptStore.state.filter.receipt.trim()).toLowerCase();
	if (nameFilter != '') {
		//FILTER RECEIPT
		filteredReceipts = filteredReceipts.filter((receipt) => {
			return receipt.store?.toLowerCase().includes(nameFilter) ||
			receipt.location?.toLowerCase().includes(nameFilter) ||
			receipt.date.toLowerCase().includes(nameFilter) ||
			receipt.cost?.toString().toLowerCase().includes(nameFilter) ||
			receipt.createdUtc?.toString().toLowerCase().includes(nameFilter);
		});
	}

	const categoryFilter = ReceiptStore.state.filter.category.trim();
	if (categoryFilter != '') {
		filteredReceipts = filteredReceipts.filter((receipt) => {
			return receipt.category == categoryFilter;
		})
	}
	if (receiptTable.value) {
		receiptTable.value.receipts = filteredReceipts;
		nextTick(() => {
			receiptTable.value?.$forceUpdate();
		});
	}
};

const updateCategory = (category: string) => {
	ReceiptStore.state.filter.category = category.toUpperCase();
	filteredReceipts();
}
const updateReceiptFilter = (value: string) => {
	console.log(value);
	ReceiptStore.state.filter.receipt = value;
	filteredReceipts();
}

defineExpose({matches, location});
</script>
<script lang="ts">
export default defineComponent({
	name: 'ReceiptList',
	components: {Head, ReceiptTable, ReceiptDialog},
	methods: {
		openCreateReceiptDialog() {
			(this.$refs.dialog as typeof ReceiptDialog).dialog.show();
			(this.$refs.dialog as typeof ReceiptDialog).reset();
		}
	}
})
</script>

<template>
	<Head title="Receipts" />
	<AuthenticatedLayout>
		<div class="m-2">
			<div class="receipt-controls mx-2">
				<VueTextField class="w-full" @changed="(value: string) => updateReceiptFilter(value)" name="search-name" placeholder="Search" clearable/>
				<ReceiptCategoryDropdown
					:items="ReceiptStore.state.categories"
					placeholder="Category"
					name="search-category"
					key="search-category"
					class="self-center px-2 min-w-max"
					@changed="(category: string) => updateCategory(category)"
					clearable
				/>
				<ConfirmButton class="ml-auto self-center min-w-max py-1 text-sm" @click="openCreateReceiptDialog">Create Receipt</ConfirmButton>
			</div>
			<div class="table-container pt-2">
				<ReceiptTable ref="receiptTable" key="receiptId"/>
			</div>
		</div>
		<!-- Create new receipt dialog -->
		<ReceiptDialog ref="dialog" editing></ReceiptDialog>
	</AuthenticatedLayout>
</template>
<style lang="scss">
.table-container {
	min-height: 200px;
	// @apply border border-solid border-neutral-800;
}

.receipt-controls {
	@apply flex flex-row;
}
</style>