<script setup lang="ts">
import { defineComponent, ref } from 'vue';
import ConfirmButton from '../Inputs/ConfirmButton.vue';
import BasicDialog from '../BasicDialog.vue';
import CancelButton from '../Inputs/CancelButton.vue';
import VueTextField from '../Inputs/VueTextField.vue';

const dialog = ref<InstanceType<typeof BasicDialog> | null>(null);
defineExpose({ dialog })

</script>
<script lang="ts">
import loginApi from '@/services/Login/loginApi';

export default defineComponent({
    name: 'LoginDialog',
	data() {
		return {
			username: '',
			password: '',
			is: {
				loading: false
			}
		}
	},
    methods: {
		async login() {
			this.is.loading = true;
			const response = await loginApi.login(this.username, this.password);

			if (response.status === 200) {
				console.log('logged in');
			} else {
				console.error('uh oh');
			}
		},
		text(t: any) {
			console.log(t);
		}
    },
    components: { ConfirmButton, BasicDialog, VueTextField }
})
</script>

<template>
	<BasicDialog ref="dialog" class="isolate sticky">
		<div class="grid grid-cols-1">
			<VueTextField @update:text="(n) => username = n" label="Username:" :disabled="is.loading"/>
			<VueTextField @update:text="(n) => password = n" label="Password:" type="password" :disabled="is.loading"/>
		</div>

		<div class="flex flex-row mt-auto">
			<ConfirmButton @click="login" label="Login" />
			<CancelButton @click="dialog?.hide" label="Cancel" />
		</div>
	</BasicDialog>
</template>