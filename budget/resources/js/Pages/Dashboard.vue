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
		<div class="grid grid-cols-3">
			<BarGraph :info="[1,2,3,4]" :keys="['test','2','aaaa','aeffea']"/>
		</div>
    </AuthenticatedLayout>
</template>
