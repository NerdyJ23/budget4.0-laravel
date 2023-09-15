<script setup lang="ts">
import { Receipt } from '@/types/Receipts/receipt';
import { ref, nextTick } from 'vue';
import ReceiptDialog from '@/Components/Receipts/Dialog/ReceiptDialog.vue';

import { BiFileEarmarkText } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { useGenericStore } from '@/store/genericPiniaStore';

const props = defineProps<{
	receipts: Array<Receipt>
}>();

const GenericStore = useGenericStore();
const showDialog = ref(false);

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

const readableDate = (date: string):any  => {
	const dateObj = new Date(date);
	return {
		date: dateObj.getDate(),
		ordinal: dateOrdinal(dateObj.getDate()),
		month: GenericStore.months[dateObj.getMonth()]
	};
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
defineExpose({reload});
</script>
<template>
	<div class="mx-0 lg:mx-2">
		<div class="table-header rounded-none lg:rounded-t-md">
			<span class="table-header-text">Date</span>
			<span class="table-header-text hidden md:inline col-span-2">Reference</span>
			<span class="table-header-text hidden md:inline">Store</span>
			<span class="table-header-text">Location</span>
			<span class="table-header-text">Cost</span>
			<span class="table-header-text">Category</span>
		</div>
		<div v-for="receipt of receipts" class="table-row" @click="showReceipt(receipt)" :key="receipt.id">
			<span class="inline md:hidden col-span-full font-semibold">{{ receipt.store ?? '-' }}</span>
			<span class="pl-1 lg:pl-2">
				{{ readableDate(receipt.date).date }}<span class="hidden md:inline">{{ readableDate(receipt.date).ordinal }}
				{{ (readableDate(receipt.date).month).slice(0,3) }}<span class="hidden lg:inline">{{ (readableDate(receipt.date).month).slice(3 - readableDate(receipt.date).month.length) }}</span></span><span class="md:hidden">/{{ new Date(receipt.date).getMonth() + 1 }}</span>
			</span>
			<span class="md:inline-flex flex-row hidden col-span-2">
				<span v-if="receipt.documents.length" class="self-start"><VIcon  name="bi-file-earmark-text" /></span>
				<span class="mx-auto">{{ receipt.reference ?? '-' }}</span>
			</span>
			<span class="hidden md:inline">{{ receipt.store ?? '-' }}</span>
			<span>{{ receipt.location ?? '-' }}</span>
			<span>${{ receipt.cost?.toFixed(2) }}</span>
			<span class="break-all text-xs md:text-sm lg:text-base pr-2">{{ receipt.category}}</span>

		</div>
	</div>
	<ReceiptDialog v-if="showDialog" ref="dialog" @destroy="dialogDestroyed"/>
</template>
<style lang="scss" scoped>
.table {
	&-header {
		@apply grid grid-cols-4 md:grid-cols-7 font-semibold text-sm md:text-base text-center bg-amber-500/80;
		&-text {
			@apply cursor-pointer hover:bg-amber-500 my-auto;
		}
	}
	&-row {
		@apply grid grid-cols-4 md:grid-cols-7 text-sm md:text-base cursor-pointer hover:bg-slate-300 text-center odd:bg-gray-200 even:bg-neutral-100;
	}
}

</style>