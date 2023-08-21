<template>
	<div class="mt-2 grid grid-cols-2">
		<label :for="id" class="align-center" >{{ label }}</label>
		<input
			@input="$emit('update:text', text)"
			v-model="text"
			:disabled="disabled"
			:type="type"
			:id="id"
			class="border-solid border border-zinc-400 rounded-sm p-0 disabled:bg-zinc-300"
		/>
	</div>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label: string,
	disabled?: boolean,
	type?: string
}>(), {
	type: 'textfield',
	disbled: false
});
</script>
<script lang="ts">
export default defineComponent({
	name: 'TextField',
	computed: {
		id() {
			return `${crypto.randomUUID()}--textfield`;
		}
	},
	data() {
		return {
			text: ''
		}
	}
})
</script>