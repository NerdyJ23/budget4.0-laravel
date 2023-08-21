<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { defineComponent } from 'vue';

import SideBar from '@/Components/SideBar.vue';
import BasicLayout from '@/Layouts/BasicLayout.vue';

</script>

<script lang="ts">
export default defineComponent({
	name: "Home"
})
</script>

<template>
	<Head title="Budgeting" />
	<BasicLayout>
		<template #header>
			<header class="container mx-auto">
				header bar
			</header>
		</template>
		<template #sidebar>
			<SideBar />
		</template>
		<template #content>
			<div class="container mx-auto">
				test
			</div>
		</template>
	</BasicLayout>
</template>