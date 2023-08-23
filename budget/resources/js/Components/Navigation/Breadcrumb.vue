<script setup lang="ts">
import { computed } from 'vue';
const breadcrumb = computed(() => {
	return window.location.pathname.split('/');
});

defineExpose({breadcrumb});
</script>
<template>
	<div class="p-2">
		Path:
		<template v-for="(value, key, index) in breadcrumb">
			<template v-if="value != ''">
				<span>/</span>
				<a v-if="breadcrumb.length - 1 != key" :href="route(value)">{{ value }}</a>
				<span v-else>{{ value }}</span>

			</template>
		</template>
	</div>
</template>