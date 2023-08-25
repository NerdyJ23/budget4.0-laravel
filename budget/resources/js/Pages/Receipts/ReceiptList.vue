<script setup lang="ts">
import { defineComponent, ref, onMounted } from 'vue';

import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';
import ConfirmButton from '../../Components/Inputs/ConfirmButton.vue';
import ReceiptDialog from '../../Components/Receipts/Dialog/ReceiptDialog.vue';
import store from '@/store';

const matches = route('receipts') == window.location.origin + window.location.pathname;
const location = window.location.href;
const receiptTable = ref<InstanceType<typeof ReceiptTable> | null>(null);

const reload = () => {
	receiptTable.value?.reload();
};

onMounted(() => {
	store.dispatch('loadCategories');
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
		<div class="m-2">
			<div class="receipt-controls mx-2">
				<ConfirmButton class="ml-auto text-sm py-1" @click="openCreateReceiptDialog">Create Receipt</ConfirmButton>
			</div>
			<div class="table-container pt-2">
				<ReceiptTable ref="receiptTable"/>
			</div>
		</div>
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