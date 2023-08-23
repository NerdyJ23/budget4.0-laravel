<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/ReceiptGraph.vue';
import { defineComponent } from 'vue';
</script>
<script lang="ts">
export default defineComponent({
	name: "Dashboard",
	components: { ReceiptGraph },
})
</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
		<div class="grid grid-cols-3">
		</div>
		<ReceiptGraph />
    </AuthenticatedLayout>
</template>
