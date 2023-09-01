<script setup lang="ts">
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

</script>
<template>
	<select v-model="value">
		<option
			v-for="(year, index) in ['2022', '2023']"
			:value="year"
		>
			{{ year }}
		</option>
	</select>
</template>