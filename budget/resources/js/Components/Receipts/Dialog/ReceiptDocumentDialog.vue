<script setup lang="ts">
import { Receipt } from "@/types/Receipts/receipt";
import { ReceiptDocument } from "@/types/Receipts/receiptDocument";
import BasicDialog from "@/Components/BasicDialog.vue";
import ReceiptDocumentFile from "@/Components/Receipts/ReceiptDocumentFile.vue";
import ConfirmButton from "@/Components/Inputs/ConfirmButton.vue";

import { defineComponent, ref, reactive } from 'vue';
import { BiFileEarmarkPlus } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import receiptApi from "@/services/Receipts/receiptApi";
import { onMounted } from "vue";
const props = withDefaults(defineProps<{
	newReceipt: boolean,
	receipt: Receipt
}>(), {
	newReceipt: false
});

const is = reactive({
	editing: props.newReceipt
});

const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
const fileHover = ref(false);


const show = () => { dialog.value?.show(); }
const removeFile = (index: number) => {	props.receipt.documents.splice(index, 1) }

const dragAndDrop = (event: DragEvent) => {
	event.preventDefault();
	event.stopPropagation();
	if (event.dataTransfer) {
		let localFiles = event.dataTransfer.files;
		for(const file of localFiles) {
			props.receipt.documents.push(file);
		}
	}
}

const fileInputChange = () => {
	if (fileInput.value && fileInput.value.files) {
		for (const file of fileInput.value.files) {
			props.receipt.documents.push(file);
		}
		fileInput.value.value = "";
	}
}

const saveAndHide = () => {
	dialog.value?.hide();
}

addIcons(BiFileEarmarkPlus);
const emit = defineEmits<{
	(e: 'save', value: File[]): void
}>();
defineExpose({ show });
</script>
<script lang="ts">export default defineComponent({name: "ReceiptDocumentDialog"});</script>
<template>
<BasicDialog ref="dialog" :title="`${newReceipt ? 'Attach' : ''} Documents`.trim()" :persistent="newReceipt">
	<div class="flex flex-col h-full"  style="min-width: 30vw">
		<div
		v-if="is.editing"
			@dragenter="$event.preventDefault(); fileHover = true;"
			@dragover="$event.preventDefault(); fileHover = true;"
			@dragleave="$event.preventDefault(); fileHover = false;"
			@drop.prevent="dragAndDrop($event); fileHover = false;"
		>
			<div :class="`select-none cursor-pointer border-2 border-dashed flex flex-col h-full rounded-lg
				${fileHover ? `border-slate-600` : `border-zinc-400`}`"
				style="min-height: 20vh"
				@click="($refs.fileInput as HTMLInputElement).click()"
			>
				<div class="my-auto mx-auto text-center">
					<VIcon name="bi-file-earmark-plus" scale="5"
						:class="`${fileHover ? `fill-slate-600` : `fill-zinc-400 dropfield`} pointer-events-none
						`"
					/>
					<div :class="`${fileHover ? `text-slate-700` : `text-zinc-500`} font-semibold dropfield pointer-events-none`">
						Drag Document(s) here
					</div>
				</div>
			</div>
			<input type="file" ref="fileInput" multiple class="hidden" @change="fileInputChange"/>
		</div>
		<div v-for="(file, index) in receipt.documents">
			<ReceiptDocumentFile :file="file" @delete="removeFile(index)" :editing="is.editing" :receipt="receipt"/>
		</div>
		<div v-if="receipt.documents.length == 0" class="text-lg italic w-full text-center">
			No Files
		</div>
		<ConfirmButton v-if="is.editing" class="ml-auto mt-2" @click="saveAndHide">Save</ConfirmButton>
	</div>
</BasicDialog>
</template>
