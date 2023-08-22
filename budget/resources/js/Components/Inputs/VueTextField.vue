<template>
	<div class="mt-2 grid grid-cols-2">
		<label :for="id" class="self-center">{{ label }}</label>
		<VField :type="type" :name="name" :rules="rules" v-slot="{ field, meta }" validate-on-blur :validate-on-model-update="false">
			<input
				ref="input"
				v-model="value"
				v-bind="$attrs"
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
import { defineComponent, ref, computed } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';

const id = computed(() => `${crypto.randomUUID()}--textfield`);
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
const value = ref(null);
const input = ref<HTMLInputElement | null>(null);
defineExpose({value, input});

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
			return ;
		}
	}
})
</script>