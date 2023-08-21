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
export default defineComponent({
    name: 'LoginDialog',
    methods: {
    },
    components: { ConfirmButton, BasicDialog, VueTextField }
})
</script>

<template>
	<BasicDialog ref="dialog">
		<div class="grid grid-cols-1">
			<VueTextField label="Username:" />
			<VueTextField label="Password:" type="password" />
		</div>

		<div class="flex flex-row mt-auto">
			<ConfirmButton label="Login" />
			<CancelButton label="Cancel" />
		</div>
	</BasicDialog>
</template>