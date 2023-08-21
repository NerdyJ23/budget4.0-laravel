<script setup lang="ts">
import { defineComponent } from 'vue';
import VueButton from '@/Components/Inputs/VueButton.vue';
</script>

<script lang="ts">
export default defineComponent({
	name: 'HeaderBar',
	components: {
		VueButton
	}
})
</script>
<template>
	<div class="flex flex-row w-full">
		<div id="logo">LOGO HERE</div>
		<div class="flex-grow ml-auto"></div>
		<VueButton class="px-2 m-2 rounded-sm" text="Login"></VueButton>
	</div>
</template>