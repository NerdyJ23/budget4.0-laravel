<template>
	<div :class="`grid grid-cols-${label ? '2' : '1'}`">
		<label v-if="label" :for="id" class="self-center">{{ label }}</label>
		<div class="inline-flex flex-col">
			<div class="inline-flex flex-row">
				<VField ref="input"
					:type="type"
					v-bind="$attrs"
					:name="name"
					:rules="rules"
					v-slot="{ field, meta }"
					validate-on-blur
					:validate-on-model-update="false"
				>
					<input
						v-bind="field"
						:disabled="disabled"
						:type="type"
						:placeholder="placeholder"
						:id="id"
						:class="[`
							border-solid border border-zinc-400 rounded-sm p-1 disabled:bg-zinc-300 w-full`,
							{'border-red-400': meta.dirty && !meta.valid},
						]"
					/>
					<span v-if="required" class="text-red-500">*</span>
				</VField>
			</div>
			<ErrorMessage :class="[{'italic': italic}, 'text-red-500 text-xs']" :name="name"></ErrorMessage>
		</div>
	</div>
</template>
<script setup lang="ts">
import { defineComponent, ref, computed } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';

const id = computed(() => `${crypto.randomUUID()}--textfield`);
withDefaults(defineProps<{
	name: string,
	label?: string,
	rules?: any,
	disabled?: boolean,
	type?: string,
	placeholder?: string,
	required?: boolean,
	italic?: boolean
}>(), {
	type: 'textfield',
	disabled: false,
	italic: true
});
const input = ref<InstanceType<typeof Field> | null>(null);
defineExpose({input});

</script>
<script lang="ts">
export default defineComponent({
	name: 'TextField',
	components: {
		VField: Field,
		ErrorMessage
	},
})
</script>