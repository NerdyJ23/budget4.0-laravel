<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import VueButton from '@/Components/Inputs/VueButton.vue';
import LoginDialog from '@/Components/Login/LoginDialog.vue';

// const dialog = ref<InstanceType<typeof LoginDialog> | null>(null);
</script>

<script lang="ts">
import loginApi from '@/services/Login/loginApi';

export default defineComponent({
	name: 'HeaderBar',
	components: { VueButton, LoginDialog },
	methods: {
		openDialog(): void {
			(this.$refs.dialog as any).dialog.show();
		},
		async logout(): Promise<void> {
			const response = await loginApi.logout();

			if (response.status == 200) {
				this.home();
			}
		},
		home() {
			window.location.href = route('home');
		}
	},
	computed: {
		loggedIn(): boolean {
			return this.$page.props.auth.user != null;
		}
	}
})
</script>
<template>
	<div class="flex flex-row align-center w-full bg-orange-400">
		<div id="logo" class="pl-2 self-center text-xl cursor-pointer" @click="home">Budgeting</div>
		<div class="flex-grow ml-auto"></div>
		<template v-if="$page.props.auth.user">
			<span class="self-center">
				Hello {{ $page.props.auth.user[0].firstName }}
			</span>
			<VueButton label="Logout" @click="logout" class="px-3 m-2 rounded-sm"></VueButton>
		</template>
		<VueButton v-else @click="openDialog" class="px-3 m-2 rounded-sm" label="Login"></VueButton>
	</div>
	<LoginDialog ref="dialog" />
</template>
