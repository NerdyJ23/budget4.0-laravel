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

const show = () => { is.open = true; }
const hide = () => { is.open = false; }
const hideSelf = () => {
	is.shake = false;
	if (!props.persistent) {
		hide();
	} else {
		is.shake = true;
		setTimeout(() => is.shake = false, 1000);
	}
}
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
		@click.self="hideSelf"
	>
		<dialog
			v-bind="$attrs"
			:open="is.open"
			:class="[
				{'animate-shake-horizontal shadow-red-600': is.shake},
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