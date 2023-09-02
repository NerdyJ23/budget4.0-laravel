<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/ReceiptGraph.vue';
import { defineComponent } from 'vue';
import Breadcrumb from '@/Components/Navigation/Breadcrumb.vue';

</script>
<template>
    <Head title="Graphs" />
    <AuthenticatedLayout>
		<div class="grid grid-cols-2 gap-x-2 px-2">
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Monthly Overview
				</span>
				<ReceiptGraph />
			</div>
			<div class="w-full">
				Yearly Overview
			</div>
		</div>
    </AuthenticatedLayout>
</template>
