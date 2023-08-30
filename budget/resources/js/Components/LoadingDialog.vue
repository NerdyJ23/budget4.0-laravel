<script setup lang="ts">
import { addIcons } from "oh-vue-icons";
import { RiLoader5Fill } from "oh-vue-icons/icons";
import { computed, ref, onMounted } from 'vue';

const props = withDefaults(defineProps<{
	text?: string
}>(), {
	text: 'Loading'
});

let ellipsis = {
	current: 0,
	max: 3
};

let loadingText = ref("");

const setLoadingText = () => {
	if (ellipsis.current > ellipsis.max) {
		ellipsis.current = 0;
	}

	let ellipsisText = '';
	for (let i = 0; i < ellipsis.current; i++) {
		ellipsisText += '.';
	}

	loadingText.value =  `${props.text}${ellipsisText}`;
	ellipsis.current++;
	setTimeout(() => setLoadingText(), 800);

};
onMounted(() => setLoadingText());
addIcons(RiLoader5Fill);
</script>
<template>
	<div class="flex w-full">
		<div class="mx-auto inline-flex flex-col">
			<slot name="icon">
				<VIcon name="ri-loader-5-fill" animation="spin" class="mx-auto" scale="4"/>
			</slot>
			<slot name="text">
				<span class="text-center text-lg">{{ loadingText }}</span>
			</slot>
		</div>
	</div>
</template>