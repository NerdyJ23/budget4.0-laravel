<script setup lang="ts">
import { useGenericStore } from '@/store/genericPiniaStore';
import { computed } from 'vue';

const props = defineProps(['modelValue']);
const emit = defineEmits(['update:modelValue']);

const value = computed({
	get() {
		return props.modelValue;
	},
	set(value) {
		emit('update:modelValue', value);
	}
});

const store = useGenericStore();
</script>
<template>
	<select v-model="value">
		<option
			v-for="(month, index) in store.months"
			:value="index + 1"
		>
			{{ month }}
		</option>
	</select>
</template>