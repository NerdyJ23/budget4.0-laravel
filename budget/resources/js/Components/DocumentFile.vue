<script setup lang="ts">
import { defineComponent, computed } from 'vue';
import { addIcons } from "oh-vue-icons";
import { ViFileTypePdf, BiFileEarmarkImage, MdInsertdrivefileOutlined, BiFileEarmarkZip, MdClose } from "oh-vue-icons/icons";

const props = defineProps<{
	filename: string,
	filesize?: number,
	url?: string,
	editing?: boolean
}>();

const extension = computed((): string => {
	const i = props.filename.split('.').pop();
	return i ? i.toLowerCase() : ''
})

const displayFileSize = computed(() => {
	if (props.filesize) {
		const size = props.filesize;
		if (size <= 1000) {
			return `${size} b`;
		} else if (size <= 100000) {
			return `${(size / 1000).toFixed(2)} kb`;
		} else if (size > 1000000) {
			return `${(size / 1000000).toFixed(2)} mb`;
		}
	}
})

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
	if (props.url) {
		const link = document.createElement('a');
		link.setAttribute('href', `${props.url}?method=${newTab.value ? '_blank' : 'download'}`);
		link.setAttribute('target', '_blank');
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
		URL.revokeObjectURL(props.url);
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
			<span class="text-xs">{{ displayFileSize }}</span>
		</span>
	</div>
</template>