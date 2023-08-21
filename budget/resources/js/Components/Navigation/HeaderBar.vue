<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import VueButton from '@/Components/Inputs/VueButton.vue';
import LoginDialog from '@/Components/Login/LoginDialog.vue';

// const dialog = ref<InstanceType<typeof LoginDialog> | null>(null);
</script>

<script lang="ts">
export default defineComponent({
	name: 'HeaderBar',
	components: { VueButton, LoginDialog },
	methods: {
		openDialog(): void {
			(this.$refs.dialog as any).dialog.show();
		}
	}
})
</script>
<template>
	<div class="flex flex-row align-center w-full bg-orange-400">
		<div id="logo" class="pl-2 self-center text-xl cursor-pointer" @click="route('home')">Budgeting</div>
		<div class="flex-grow ml-auto"></div>
		<VueButton @click="openDialog" class="px-3 m-2 rounded-sm" label="Login"></VueButton>
	</div>
	<LoginDialog ref="dialog" />
</template>
