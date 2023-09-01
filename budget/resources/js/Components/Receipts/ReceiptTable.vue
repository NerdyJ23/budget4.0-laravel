<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { defineComponent, ref, nextTick, onMounted, reactive } from 'vue';
import ReceiptDialog from '@/Components/Receipts/Dialog/ReceiptDialog.vue';
import moment from 'moment';

import { BiFileEarmarkText } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { usePage } from '@inertiajs/vue3';
import { useGenericStore } from '@/store/genericPiniaStore';

const GenericStore = useGenericStore();
const showDialog = ref(false);

const receiptProp = ref(usePage().props.receipts);
const receipts: Array<Receipt> = reactive(receiptProp.value.sort((a: Receipt, b: Receipt) => {
	if (moment(a.date).isBefore(moment(b.date))) {
		return -1;
	} else if (moment(a.date).isSame(moment(b.date))) {
		return 0;
	} else {
		return 1;
	}
}))
const dialog = ref<InstanceType<typeof ReceiptDialog> | null>();
const reload = () => { window.location.reload() }; //Lazy hack instead of soft-reloading the page

const showReceipt = (receipt: Receipt) => {
	showDialog.value = true;
	nextTick(() => {
		const newReceipt = JSON.parse(JSON.stringify(receipt));
		dialog.value?.setReceipt(newReceipt);
		dialog.value?.show();
	})
}
const dialogDestroyed = () => {
	showDialog.value = false;
}

const readableDate = (date: string):string  => {
	const dateObj = new Date(date);
	return `${dateObj.getDate()}${dateOrdinal(dateObj.getDate())} ${GenericStore.months[dateObj.getMonth()]}`;
}
const dateOrdinal = (number: number):string => {
	if (number > 3 && number < 21) return "th";
  switch (number % 10) {
    case 1:
      return "st";
    case 2:
      return "nd";
    case 3:
      return "rd";
    default:
      return "th";
  }
}

addIcons(BiFileEarmarkText);
defineExpose({reload, receipts});
</script>
<script lang="ts">export default defineComponent({name: 'ReceiptTable'});</script>
<template>
	<div class="mx-2">
		<div class="table-header rounded-t-md">
			<span class="table-header-text">Date</span>
			<span class="table-header-text">Reference Number</span>
			<span class="table-header-text">Store</span>
			<span class="table-header-text">Location</span>
			<span class="table-header-text">Cost</span>
			<span class="table-header-text">Category</span>
		</div>
		<div v-for="receipt of receipts" class="table-row" @click="showReceipt(receipt)">
			<span>{{ readableDate(receipt.date) }}</span>
			<span class="inline-flex flex-row">
				<span v-if="receipt.documents.length" class="self-start"><VIcon  name="bi-file-earmark-text" /></span>
				<span class="mx-auto">{{ receipt.reference ?? '-' }}</span>
			</span>
			<span>{{ receipt.store ?? '-' }}</span>
			<span>{{ receipt.location ?? '-' }}</span>
			<span>${{ receipt.cost?.toFixed(2) }}</span>
			<span>{{ receipt.category}}</span>
		</div>
	</div>
	<ReceiptDialog v-if="showDialog" ref="dialog" @destroy="dialogDestroyed"/>
</template>
<style lang="scss" scoped>
.table {
	&-header {
		@apply grid grid-cols-6 font-semibold text-center bg-amber-500/80;
		&-text {
			@apply cursor-pointer hover:bg-amber-500 py-2 first-of-type:rounded-tl-md last-of-type:rounded-tr-md;
		}
	}
	&-row {
		@apply grid grid-cols-6 cursor-pointer hover:bg-slate-300 text-center odd:bg-gray-200 even:bg-neutral-100;
	}
}

</style>