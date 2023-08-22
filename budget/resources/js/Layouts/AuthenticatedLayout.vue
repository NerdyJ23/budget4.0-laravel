<script setup lang="ts">
import { defineComponent } from 'vue';
import HeaderBar from '@/Components/Navigation/HeaderBar.vue';
</script>
<script lang="ts">
export default defineComponent({
	name: 'BasicLayout',
	components: {
	}
})
</script>
<template>
	<header-bar></header-bar>
	<div class="flex flex-column">
		<div class="basis-1/6">
			<slot name="sidebar"></slot>

		</div>
		<div class="flex flex-column">
			<slot></slot>
		</div>
	</div>
</template>