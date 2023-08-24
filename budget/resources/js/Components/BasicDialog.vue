<script setup lang="ts">
import { defineComponent, ref, reactive } from 'vue';
import { addIcons } from "oh-vue-icons";
import { RiLoader5Fill  } from "oh-vue-icons/icons";

const props = defineProps<{
	persistent?: boolean,
	blur?: boolean
}>();

const is = reactive({
	open: false,
	shake: false,
	loading: false
});

const show = () => { is.open = true; toggleOverflow();}
const hide = () => { is.open = false; toggleOverflow();}
const setLoading = (isLoading: boolean) =>{
	is.loading = isLoading;
	setTimeout(() => is.loading = false, 10000); //In case of errors stop loading 10s in
}
addIcons(RiLoader5Fill);
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

defineExpose({is, show, hide, setLoading});
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

			<!-- Loading Overlay -->
			<div v-if="is.loading" class="h-full w-full backdrop-blur-sm absolute top-0 left-0">
				<div class="grid h-full justify-items-center content-center">
					<VIcon class="opacity-50" name="ri-loader-5-fill" animation="spin" label="Close" scale="8"></VIcon>
				</div>
			</div>
		</dialog>
	</div>
</template>
<style lang="css">
dialog {
	transition: box-shadow 0.5s ease-in;
}
</style>