<template>
	<div :class="`grid grid-cols-${label ? '2' : '1'}`">
		<label v-if="label" :for="id" class="self-center">{{ label }}</label>
		<div class="inline-flex flex-col">
			<div class="inline-flex flex-row relative">
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
						ref="selfInput"
						v-bind="field"
						:disabled="disabled"
						:type="type"
						:list="list"
						:placeholder="placeholder"
						:id="id"
						@focus="$emit('focused')"
						@input="(e: Event) => inputChanged(e)"
						:class="[`
							border-solid border border-zinc-400 rounded-sm p-1 disabled:bg-zinc-300 w-full`,
							{'border-red-400': meta.dirty && !meta.valid},
							{'uppercase': uppercase}
						]"
					/>
					<div v-if="inputValue != '' && clearable" class="icon-button absolute right-1 text-center self-center">
						<VIcon name="io-close-outline" @click="clearInput"></VIcon>
					</div>
					<span v-if="required" class="text-red-500">*</span>
				</VField>
			</div>
			<ErrorMessage :class="[{'italic': italic}, 'text-red-500 text-xs']" :name="name"></ErrorMessage>
		</div>
	</div>
</template>
<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import { Field, ErrorMessage } from 'vee-validate';
import { addIcons } from "oh-vue-icons";
import { IoCloseOutline } from "oh-vue-icons/icons";

const id = `${crypto.randomUUID()}--textfield`;
const input = ref<InstanceType<typeof Field> | null>(null);
const selfInput = ref<HTMLInputElement | null>(null);

const inputValue = ref('');
const inputChanged = (e: Event) => {
	storeInputValue((e.target as HTMLInputElement).value);
	emit('changed', (e.target as HTMLInputElement).value);
}

withDefaults(defineProps<{
	name: string,
	label?: string,
	rules?: any,
	disabled?: boolean,
	type?: string,
	placeholder?: string,
	required?: boolean,
	italic?: boolean,
	list?: string,
	uppercase?: boolean,
	clearable?: boolean
}>(), {
	type: 'textfield',
	disabled: false,
	italic: true
});

const storeInputValue = (value: string) => {
	inputValue.value = value;
}
const clearInput = () => {
	if (selfInput.value) {
		storeInputValue('');
		input.value?.reset();
		emit('changed', '');
	}
}
const emit = defineEmits<{
	(e: 'changed', value: string):void,
	(e: 'focused'): void
}>();
defineExpose({input});
addIcons(IoCloseOutline);
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