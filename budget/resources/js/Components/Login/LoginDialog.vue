<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import ConfirmButton from '../Inputs/ConfirmButton.vue';
import BasicDialog from '../BasicDialog.vue';
import CancelButton from '../Inputs/CancelButton.vue';
import VueTextField from '../Inputs/VueTextField.vue';
import { Form } from 'vee-validate';

const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
defineExpose({ dialog })

</script>
<script lang="ts">
import loginApi from '@/services/Login/loginApi';

export default defineComponent({
    name: 'LoginDialog',
    components: { ConfirmButton, BasicDialog, VueTextField, VForm: Form },
	data() {
		return {
			username: '',
			password: '',
			is: {
				loading: false,
			},
			error: {
				message: null,
				active: false
			}
		}
	},
    methods: {
		async login(valid = true) {
			if (!valid) {
				return;
			}
			this.is.loading = true;
			this.error.active = false;
			const response = await loginApi.login(this.username, this.password);

			if (response.status === 200) {
				window.location.href = route('dashboard');
			} else {
				this.error.active = true;
				this.error.message = response.data.message;
			}
			this.is.loading = false;
		},
		validateUsername(value: string) {
			return value?.length < 5 ? 'Username must be 5 characters long' : true;
		},
		validatePassword(value: string) {
			return value?.length < 5 ? 'Password must be 5 characters long' : true;
		}
    }
})
</script>

<template>
	<BasicDialog ref="dialog" persistent>
		<span class="text-xl">Login</span>
		<VForm ref="form" v-slot="{ meta }">
			<div class="grid grid-cols-1">
				<VueTextField :rules="validateUsername" name="username" @update:text="(n) => username = n" label="Username:" :disabled="is.loading" v-on:keyup.enter="login(meta.dirty && meta.valid)"/>
				<VueTextField :rules="validatePassword" name="password" @update:text="(n) => password = n" label="Password:" type="password" :disabled="is.loading" v-on:keyup.enter="login(meta.dirty && meta.valid)"/>
			</div>

			<div class="flex flex-row mt-auto pt-4">
				<ConfirmButton @click="login" label="Login" />
				<CancelButton @click="dialog?.hide" label="Cancel" />
			</div>
		</VForm>
		<span v-if="error.active" class="italic text-red-500 text-xs">
			{{ error.message }}
		</span>
	</BasicDialog>
</template>