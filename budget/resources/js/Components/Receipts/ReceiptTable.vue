<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { defineComponent, ref, nextTick } from 'vue';
import ReceiptDialog from '@/Components/Receipts/Dialog/ReceiptDialog.vue';

import { BiFileEarmarkText } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { usePage } from '@inertiajs/vue3';
import receiptApi from '@/services/Receipts/receiptApi';
const showDialog = ref(false);

const receipts = ref(usePage().props.receipts);

const dialog = ref<InstanceType<typeof ReceiptDialog> | null>();
const reload = () => { window.location.reload() };
const showReceipt = (receipt: Receipt) => {
	showDialog.value = true;
	nextTick(() => {
		dialog.value?.setReceipt(receipt);
		dialog.value?.show();
	})
}
const dialogDestroyed = () => {
	showDialog.value = false;
}
addIcons(BiFileEarmarkText);
defineExpose({reload});
</script>
<script lang="ts">export default defineComponent({name: 'ReceiptTable'});</script>
<template>
	<div class="mx-2">
		<div class="table-header rounded-t-md">
			<span class="table-header-text">Reference Number</span>
			<span class="table-header-text">Store</span>
			<span class="table-header-text">Location</span>
			<span class="table-header-text">Cost</span>
			<span class="table-header-text">Category</span>
		</div>
		<div v-for="receipt of receipts" class="table-row" @click="showReceipt(receipt)">
			<span class="inline-flex flex-row">
				<span v-if="receipt.documents.length" class="self-start"><VIcon  name="bi-file-earmark-text" /></span>
				<span class="mx-auto">{{ receipt.reference ?? '-' }}</span>
			</span>
			<span>{{ receipt.store ?? '-' }}</span>
			<span>{{ receipt.location ?? '-' }}</span>
			<span>${{ receipt.cost.toFixed(2) }}</span>
			<span>{{ receipt.category}}</span>
		</div>
	</div>
	<ReceiptDialog v-if="showDialog" ref="dialog" @destroy="dialogDestroyed"/>
</template>
<style lang="scss" scoped>
.table {
	&-header {
		@apply grid grid-cols-5 font-semibold text-center bg-amber-500/80;
		&-text {
			@apply cursor-pointer hover:bg-amber-500 py-2 first-of-type:rounded-tl-md last-of-type:rounded-tr-md;
		}
	}
	&-row {
		@apply grid grid-cols-5 cursor-pointer hover:bg-slate-300 text-center odd:bg-gray-200 even:bg-neutral-100;
	}
}

</style>