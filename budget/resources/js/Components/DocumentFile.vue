<script setup lang="ts">
import { ReceiptDocument } from '@/types/Receipts/receiptDocument';
import { defineComponent, computed } from 'vue';
import { addIcons } from "oh-vue-icons";
import { ViFileTypePdf, BiFileEarmarkImage, MdInsertdrivefileOutlined, BiFileEarmarkZip, MdClose } from "oh-vue-icons/icons";

const props = defineProps<{
	file: ReceiptDocument | File,
	editing: boolean
}>();

const filename = computed(() => {
	console.log(props.file);
	return props.file instanceof File ? (props.file as File).name : (props.file as ReceiptDocument).filename;
});

const extension = computed((): string => {
	const i = filename.value.split('.').pop();
	return i ? i.toLowerCase() : ''
})
const sizeMeasurement = computed(() => {
	if (props.file instanceof File) {
		const size = props.file.size;
		if (size <= 1000) {
			return 'b';
		} else if (size <= 100000) {
			return 'kb';
		} else if (size > 1000000) {
			return 'mb';
		}
	}
	return null;
})

const filesize = computed((): number | null => {
	if (props.file instanceof File) {
		switch(sizeMeasurement.value) {
			case 'b':
				return props.file.size;
			case 'kb':
				return props.file.size / 1000;
			case 'mb':
			default:
				return props.file.size / 1000000;
		}
	}
	return null;
});
const icon = computed((): string => {
	if (extension) {
		switch(extension.value) {
			case "pdf":
				return "vi-file-type-pdf";
			case "jpg":
			case "jpeg":
			case "png":
			case "gif":
			case "webp":
			case "svg":
			case "avif":
				return "bi-file-earmark-image";
			case "zip":
			case "rar":
				return "bi-file-earmark-zip";
		}
	}
	return "md-insertdrivefile-outlined";
})

const newTab = computed(():boolean => { return icon.value === "bi-file-earmark-image" || icon.value === "vi-file-type-pdf"})
const openFile = () => {
	if (!(props.file instanceof File)) {
		const link = document.createElement('a');
		link.setAttribute('href', `${props.file.url}?method=${newTab ? '_blank' : 'download'}`);
		newTab ? link.setAttribute('target', '_blank') : link.setAttribute('download', props.file.filename);
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
		URL.revokeObjectURL(props.file.url);
	}
}
addIcons(ViFileTypePdf, BiFileEarmarkImage, MdInsertdrivefileOutlined, BiFileEarmarkZip, MdClose)
</script>

<script lang="ts">
	export default defineComponent({name: "DocumentFile"})
</script>
<template>
	<div class="flex flex-row items-center">
		<div v-if="editing" class="icon-button self-center">
			<VIcon name="md-close" class="fill-red-500 m-0.5" scale="1.2" @click="$emit('delete')" />
		</div>
		<VIcon :name="icon"></VIcon>
		<span>
			<span :class="[{'cursor-pointer' :!editing}]" @click="openFile"> {{ filename }} </span>
			<span class="text-xs">{{ filesize?.toFixed(2) }}{{ sizeMeasurement }}</span>
		</span>
	</div>
</template>