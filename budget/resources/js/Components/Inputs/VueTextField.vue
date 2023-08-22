<template>
	<div class="mt-2 grid grid-cols-2">
		<label :for="id" class="self-center">{{ label }}</label>
		<VField :type="type" :name="name" :rules="rules" v-slot="{ field, meta }" validate-on-blur :validate-on-model-update="false">
			<input
				@input="$emit('update:text', text)"
				v-model="text"
				v-bind="field"
				:disabled="disabled"
				:type="type"
				:id="id"
				:class="[`
					border-solid border border-zinc-400 rounded-sm p-1
					disabled:bg-zinc-300
				`, {'border-red-400': meta.dirty && !meta.valid}]"
			/>
		</VField>
		<span></span>
		<ErrorMessage class="text-red-500 text-xs italic" :name="name"></ErrorMessage>
	</div>
</template>
<script setup lang="ts">
import { defineComponent } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';

withDefaults(defineProps<{
	label: string,
	name: string,
	rules?: any,
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
	components: {
		VField: Field,
		ErrorMessage
	},
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