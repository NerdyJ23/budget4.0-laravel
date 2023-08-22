<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BarGraph from '@/Components/Graphs/BarGraph.vue';
import { defineComponent } from 'vue';
</script>
<script lang="ts">
export default defineComponent({
	name: "Dashboard",
	components: { BarGraph }
})
</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>

		<div>
			Graph test:

		</div>
    </AuthenticatedLayout>
</template>
