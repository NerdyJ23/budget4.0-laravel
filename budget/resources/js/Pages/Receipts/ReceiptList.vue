<script setup lang="ts">
import { defineComponent, ref } from 'vue';

import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';

const matches = route('receipts') == window.location.origin + window.location.pathname;
const location = window.location.href;
defineExpose({matches, location});
</script>
<script lang="ts">
export default defineComponent({
	name: 'ReceiptList',
	components: {Head, ReceiptTable}
})
</script>

<template>
	<Head title="Receipts" />
	<AuthenticatedLayout>
		<div class="table-container">
			<ReceiptTable />
		</div>
	</AuthenticatedLayout>
</template>
<style lang="scss">
.table-container {
	min-height: 200px;
	// @apply border border-solid border-neutral-800;
}
</style>