<script setup lang="ts">
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import { defineComponent, ref } from 'vue';

import BasicDialog from '@/Components/BasicDialog.vue';
import { Form } from 'vee-validate';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
import ReceiptDialogItem from './ReceiptDialogItem.vue';

import { addIcons } from "oh-vue-icons";
import { IoCloseOutline } from "oh-vue-icons/icons";

const items: ReceiptItem[] = [];
const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
addIcons(IoCloseOutline);
defineExpose({dialog});
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
 <BasicDialog ref="dialog" persistent blur>
	<div class="flex flex-row">
		<span class="header-text mr-auto">Create Receipt</span>
		<span class="icon-button hover:animate-hop-once" @click="($refs.dialog as typeof BasicDialog).hide()">
			<VIcon name="io-close-outline" label="Close" scale="1.3"></VIcon>
		</span>
	</div>
	<div>
		<VForm>
			<div class="inline-flex flex-row">
				<VueTextField placeholder="Receipt Number" name="receiptnumber" />
				<VueTextField placeholder="Store Name" name="store" />
				<VueTextField placeholder="Location" name="location" />
			</div>
			<div class="flex flex-col">
				<ReceiptDialogItem></ReceiptDialogItem>
			</div>
		</VForm>
	</div>

 </BasicDialog>
</template>