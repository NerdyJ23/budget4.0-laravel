<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import Breadcrumb from '@/Components/Navigation/Breadcrumb.vue';

</script>
<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
		<div class="grid grid-cols-2 gap-x-2">

		</div>
    </AuthenticatedLayout>
</template>
