<script setup lang="ts">
import { ReceiptItem } from '@/types/Receipts/receiptItem';
import BasicDialog from '../BasicDialog.vue';
import { defineComponent, ref } from 'vue';
import { Form } from 'vee-validate';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
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
 <BasicDialog ref="dialog" persistent>
	<div class="flex flex-row">
		<span class="header-text mr-auto">Create Receipt</span>
		<span class="icon-button hover:animate-hop-once" @click="($refs.dialog as typeof BasicDialog).hide()">
			<VIcon name="io-close-outline" label="Close"></VIcon>
		</span>
	</div>
	<VForm>
		<VueTextField label="test" name="test" />
	</VForm>
 </BasicDialog>
</template>