<script setup lang="ts">
import { defineComponent, ref, onMounted, nextTick, reactive } from 'vue';
import { Receipt } from '@/types/Receipts/receipt';
import { Head, usePage } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';

//Inputs
import MonthDropdown from '@/Components/Inputs/MonthDropdown.vue';
import YearDropdown from '@/Components/Inputs/YearDropdown.vue';
import ConfirmButton from '@/Components/Inputs/ConfirmButton.vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import FilterDropdownMenu from '@/Components/Search/FilterDropdownMenu.vue';

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

ReceiptStore.setup();
if (ReceiptStore.receipts.length == 0) {
	ReceiptStore.setReceipts(usePage().props.receipts);
}
let receipts: Array<Receipt> = reactive(JSON.parse(JSON.stringify(ReceiptStore.receipts)));

const updateCategory = (category: string) => {
	ReceiptStore.filter.category = category.toUpperCase();
	receipts.length = 0;
	for(const receipt of ReceiptStore.filterReceipts()) {
		receipts.push(receipt);
	}}

const updateReceiptFilter = (value: string) => {
	ReceiptStore.filter.receipt = value;
	receipts.length = 0;
	for(const receipt of ReceiptStore.filterReceipts()) {
		receipts.push(receipt);
	}
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
			categoryFilterInput.value.updateFilterValue((params.category as string).toUpperCase())
		}
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
		<div class="overflow-x-hidden">
			<div class="receipt-controls mx-2 sticky top-0 bg-white">
				<VueTextField class="w-full self-center" @changed="(value: string) => updateReceiptFilter(value)" name="search-name" placeholder="Search" clearable/>
				<ReceiptCategoryDropdown
					:items="ReceiptStore.categories"
					ref="categoryFilterInput"
					placeholder="Category"
					name="search-category"
					key="search-category"
					class="self-center pl-1 min-w-max hidden md:inline"
					v-model="ReceiptStore.filter.category"
					@changed="(category: string) => updateCategory(category)"
					clearable
				/>
				<FilterDropdownMenu class="p-2">
					<span class="text-md py-1 px-2 flex w-full border-b border-slate-400/50">Filter By</span>
					<div class="flex flex-row p-2">
						<MonthDropdown class="ml-2 rounded-none rounded-l-xl border border-r-slate-50/100" v-model="ReceiptStore.selected.month" @update:model-value="(value: number) => setHeaderValue(value, 'month')"/>
						<YearDropdown class="mr-2 rounded-none rounded-r-xl" v-model="ReceiptStore.selected.year" @update:model-value="(value: number) => setHeaderValue(value, 'year')" />
					</div>
					<ReceiptCategoryDropdown
						:items="ReceiptStore.categories"
						ref="categoryFilterInput"
						placeholder="Category"
						name="search-category"
						key="search-category"
						class="self-center px-1 min-w-max inline md:hidden"
						v-model="ReceiptStore.filter.category"
						@changed="(category: string) => updateCategory(category)"
						clearable
					/>
				</FilterDropdownMenu>
				<ConfirmButton class="ml-auto self-center min-w-max py-1 text-sm lg:text-base" @click="openCreateReceiptDialog">Create <span class="hidden md:inline">Receipt</span></ConfirmButton>
			</div>
			<div class="table-container pt-2">
				<ReceiptTable key="receiptTable" :receipts="receipts"/>
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