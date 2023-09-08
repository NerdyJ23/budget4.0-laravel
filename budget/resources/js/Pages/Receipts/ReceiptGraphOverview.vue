<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ReceiptGraph from '@/Components/Receipts/Graphs/ReceiptGraph.vue';
import ReceiptYearlyGraph from '@/Components/Receipts/Graphs/ReceiptYearlyGraph.vue';
import YearlyReceiptCategories from '@/Components/Receipts/Statistics/YearlyReceiptCategories.vue';
import YearlyReceiptStores from '@/Components/Receipts/Statistics/YearlyReceiptStores.vue';

import { useReceiptStore } from '@/store/receiptPiniaStore';

const ReceiptStore = useReceiptStore();

</script>
<template>
    <Head title="Graphs" />
    <AuthenticatedLayout>
		<div class="grid grid-cols-2 gap-x-2 px-2">
			<div class="border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					Monthly View
				</span>
				<ReceiptGraph />
			</div>
			<div class="flex flex-col border border-solid border-zinc-300 rounded-lg my-2 p-2 w-full">
				<span class="text-xl font-semibold">
					This Year
				</span>
				<ReceiptYearlyGraph />
				<YearlyReceiptCategories :year="ReceiptStore.selected.year" />
				<YearlyReceiptStores :year="ReceiptStore.selected.year" class="mt-2"/>
			</div>

		</div>
    </AuthenticatedLayout>
</template>
