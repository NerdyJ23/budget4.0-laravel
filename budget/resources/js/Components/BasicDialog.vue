<script setup lang="ts">
import { defineComponent, ref, reactive } from 'vue';
const props = defineProps<{
	persistent?: boolean,
	blur?: boolean
}>();

const is = reactive({
	open: false,
	shake: false
});

const show = () => { is.open = true; toggleOverflow();}
const hide = () => { is.open = false; toggleOverflow();}
const hideSelf = () => {
	is.shake = false;
	if (!props.persistent) {
		hide();
	} else {
		is.shake = true;
		setTimeout(() => is.shake = false, 1000);
	}
}
const toggleOverflow = () => {
	document.querySelector('html')?.classList.toggle('overflow-y-hidden');
};

defineExpose({is, show, hide, hideSelf});
</script>
<script lang="ts">
export default defineComponent({name: 'BasicDialog'})
</script>

<template>
	<div
		v-if="is.open"
		:class="[
			{'backdrop-blur-sm': blur},
			`w-full bg-neutral-600/60 absolute object-center inset-0`
		]"
		@mousedown.self="hideSelf"
	>
		<dialog
			v-bind="$attrs"
			:open="is.open"
			:class="[
				{'animate-shake-horizontal': is.shake},
				'object-center shadow-md  px-5 py-3 inset-0 rounded-md'
			]"
		>
		<slot></slot>
		</dialog>
	</div>
</template>
<style lang="css">
dialog {
	transition: box-shadow 0.5s ease-in;
}
</style>