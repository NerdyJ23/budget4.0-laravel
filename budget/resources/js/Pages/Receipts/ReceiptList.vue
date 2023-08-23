<script setup lang="ts">
import { defineComponent, ref } from 'vue';

import { Head } from '@inertiajs/vue3';
import HeaderBar from '@/Components/Navigation/HeaderBar.vue';
import ReceiptTable from '@/Components/Receipts/ReceiptTable.vue';
</script>
<script lang="ts">
export default defineComponent({
	name: 'ReceiptList',
	components: {Head, HeaderBar, ReceiptTable}
})
</script>

<template>
	<Head title="Receipts" />
	<HeaderBar />

	<ReceiptTable />
</template>