<script setup lang="ts">
import { defineComponent } from 'vue';
import HeaderBar from '@/Components/Navigation/HeaderBar.vue';
import SideBar from '@/Components/SideBar.vue';
import Breadcrumb from '@/Components/Navigation/Breadcrumb.vue';
import PlainButtonLink from '@/Components/Navigation/PlainButtonLink.vue';

const currentPage = (name: string):boolean =>{
	return route(name) == window.location.origin + window.location.pathname;
}
</script>
<script lang="ts">
export default defineComponent({
	name: 'AuthenticatedLayout',
	components: {
	}
})
</script>
<template>
	<div class="flex flex-col h-screen min-h-screen max-h-screen">
		<header-bar></header-bar>
		<div class="inline-flex flex-row">
			<div>
				<slot name="sidebar">
					<SideBar class="border border-solid border-slate-800 sticky top-0 h-screen max-h-screen min-h-screen md:w-48">
						<PlainButtonLink class="w-full" text="Receipts" :link="route('receipts')" :current="currentPage('receipts')"></PlainButtonLink>
						<PlainButtonLink class="w-full" text="Graphs" :link="route('receipts.graphs')" :current="currentPage('receipts.graphs')"></PlainButtonLink>
					</SideBar>
				</slot>
			</div>
			<div class="w-full overscroll-y-contain">
				<Breadcrumb v-if="false"/>
				<slot></slot>
			</div>
		</div>
	</div>
</template>