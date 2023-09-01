<script setup lang="ts">
import { defineComponent, ref, onMounted, nextTick } from 'vue';
import { Receipt } from '@/types/Receipts/receipt';
import { Head, usePage } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';

//Inputs
import MonthDropdown from '@/Components/Inputs/MonthDropdown.vue';
import YearDropdown from '@/Components/Inputs/YearDropdown.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';

//Components
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';
import ReceiptDialog from '@/Components/Receipts/Dialog/ReceiptDialog.vue';
import ReceiptCategoryDropdown from '@/Components/Receipts/ReceiptCategoryDropdown.vue';

//Store
import { useReceiptStore } from '@/store/receiptPiniaStore';

const ReceiptStore = useReceiptStore();
const matches = route('receipts') == window.location.origin + window.location.pathname;
const location = window.location.href;
const receiptTable = ref<InstanceType<typeof ReceiptTable> | null>(null);
const categoryFilterInput = ref<InstanceType<typeof ReceiptCategoryDropdown> | null>(null);

const reload = () => {
	receiptTable.value?.reload();
};

const filteredReceipts = () => {
	let filteredReceipts: Array<Receipt> = usePage().props.receipts;
	const nameFilter = (ReceiptStore.filter.receipt.trim()).toLowerCase();
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

	const categoryFilter = ReceiptStore.filter.category.trim();
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
	ReceiptStore.filter.category = category.toUpperCase();
	filteredReceipts();
}

const updateReceiptFilter = (value: string) => {
	console.log(value);
	ReceiptStore.filter.receipt = value;
	filteredReceipts();
}

const setHeaderValue = (value: number, param: string) => {
	const params = useUrlSearchParams('history');
	params[param] = `${value}`;
	nextTick(() => {
		window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + window.location.search;
	})
}

onMounted(() => {
	const params = useUrlSearchParams('history');
	if(params.category) {
		if (categoryFilterInput.value) {
			console.log(categoryFilterInput.value);
			categoryFilterInput.value.updateFilterValue((params.category as string).toUpperCase())
		}
	}
	if (params.month) {
		ReceiptStore.selected.month = parseInt(params.month as string);
	}
	if (params.year) {
		ReceiptStore.selected.year = parseInt(params.year as string);
	}

	ReceiptStore.loadCategories();
})
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
		<div class="">
			<div class="receipt-controls mx-2 sticky top-0 bg-white p-2">
				<VueTextField class="w-full" @changed="(value: string) => updateReceiptFilter(value)" name="search-name" placeholder="Search" clearable/>
				<MonthDropdown v-model="ReceiptStore.selected.month" @update:model-value="(value: number) => setHeaderValue(value, 'month')"/>
				<YearDropdown v-model="ReceiptStore.selected.year" @update:model-value="(value: number) => setHeaderValue(value, 'year')" />
				<ReceiptCategoryDropdown
					:items="ReceiptStore.categories"
					ref="categoryFilterInput"
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