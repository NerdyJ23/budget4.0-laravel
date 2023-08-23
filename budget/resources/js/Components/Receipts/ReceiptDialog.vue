<script setup lang="ts">
import ReceiptItem from '@/types/Receipts/ReceiptItem';
import BasicDialog from '../BasicDialog.vue';
import { defineComponent, ref } from 'vue';
import { Form } from 'vee-validate';
import VueTextField from '@/Components/Inputs/VueTextField.vue';

const items: ReceiptItem[] = [];
const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);

defineExpose({dialog});
</script>
<script lang="ts">
defineComponent({
	name: 'ReceiptDialog',
	components: {VForm: Form, VueTextField}
})
</script>
<template>
 <BasicDialog ref="dialog" persistent>
	<VForm>
		<VueTextField label="test" />
	</VForm>
 </BasicDialog>
</template>