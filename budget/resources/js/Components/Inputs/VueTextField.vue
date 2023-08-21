<template>
	<div class="mt-2 grid grid-cols-2">
		<label :for="id" class="align-center" >{{ label }}</label>
		<input :type="type" :id="id" class="border-solid border-2 border-zinc-400 rounded-sm p-0" />
	</div>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';

withDefaults(defineProps<{
	label: string,
	type?: string
}>(), {
	type: 'textfield'
});
</script>
<script lang="ts">
export default defineComponent({
	name: 'TextField',
	computed: {
		id() {
			return `${crypto.randomUUID()}--textfield`;
		}
	}
})
</script>