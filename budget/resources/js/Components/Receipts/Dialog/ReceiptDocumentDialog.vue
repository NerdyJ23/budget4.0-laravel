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

const props = withDefaults(defineProps<{
	editing: boolean,
	receipt: Receipt
}>(), {
	editing: false
});

const is = reactive({
	editing: props.editing,
	uploading: false
});

const receipt = ref(props.receipt);

const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);
const fileHover = ref(false);


const show = () => { dialog.value?.show(); }
const removeFile = (index: number) => {	receipt.value.documents.splice(index, 1) }

const uploadDocument = async (file: File) => {
	is.uploading = true;
	const response = await receiptApi.uploadDocument(file, receipt.value);
	if (response.status === 201) {
		const result = response.data.result[0];
		const doc: ReceiptDocument = {
			id: result.id,
			name: result.name,
			uploadedUtc: result.uploadedUtc
		};
		is.uploading = false;
		return doc;
	}
	is.uploading = false;
	return response.data.errors;
}

const dragAndDrop = async (event: DragEvent) => {
	event.preventDefault();
	event.stopPropagation();
	if (event.dataTransfer) {
		let localFiles = event.dataTransfer.files;
		for(const file of localFiles) {
			if (receipt.value.id) {
				const result = await uploadDocument(file);
				receipt.value.documents.push(result);
			} else {
				receipt.value.documents.push(file);
			}
		}
	}
}

const fileInputChange = async () => {
	if (fileInput.value && fileInput.value.files) {
		for (const file of fileInput.value.files) {
			if (receipt.value.id) {
				const result = await uploadDocument(file);
				receipt.value.documents.push(result);
			} else {
				receipt.value.documents.push(file);
			}
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
<BasicDialog ref="dialog"
	:title="`${is.editing ? 'Attach' : ''} Documents`.trim()"
	:persistent="is.editing"
	class="min-h-full min-w-full md:min-w-[50vw] md:min-h-[30vh]"
>
	<div class="flex flex-col h-full"  style="min-width: 30vw">
		<div v-if="is.editing" key="drag-drop-area"
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
			<ReceiptDocumentFile :file="file" @delete="removeFile(index)" :editing="is.editing" :receipt="receipt" :key="index"/>
		</div>
		<div v-if="is.uploading">
			Uploading...
		</div>
		<div v-if="receipt.documents.length == 0" class="text-lg italic w-full text-center">
			No Files
		</div>
		<ConfirmButton v-if="is.editing" class="ml-auto mt-2" @click="saveAndHide">Save</ConfirmButton>
	</div>
</BasicDialog>
</template>
