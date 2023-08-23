<script setup lang="ts">
import { defineComponent, ref } from 'vue';

import { Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';
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
		<div class="w-full">
			<ReceiptTable />
		</div>
	</AuthenticatedLayout>

</template>