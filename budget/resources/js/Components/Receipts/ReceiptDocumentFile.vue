<script setup lang="ts">
import DocumentFile from '../DocumentFile.vue';
import { ReceiptDocument } from '@/types/Receipts/receiptDocument';
import { Receipt } from '@/types/Receipts/receipt';
import { computed } from 'vue';

const props = defineProps<{
	file: ReceiptDocument | File,
	editing: boolean,
	receipt?: Receipt,
}>();

const size = computed(() => {
	if (props.file instanceof File) {
		return props.file.size;
	}
	return;
});

const url = computed(() => {
	if (!(props.file instanceof File)) {
		return `/receipts/${props.receipt!.id}/documents/${props.file.id}`;
	}
	return;
})
</script>
<template>
	<DocumentFile :filename="file.name" :filesize="size" :url="url" @delete="$emit('delete')"/>
</template>
