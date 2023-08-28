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
	<div class="h-screen flex flex-col overscroll min-h-screen relative">
		<header-bar></header-bar>
		<div class="inline-flex flex-row h-screen">
			<div class="basis-1/6 sticky">
				<slot name="sidebar">
					<SideBar class="border border-solid border-slate-800">
						<PlainButtonLink text="Receipts" :link="route('receipts')" :current="currentPage('receipts')"></PlainButtonLink>
					</SideBar>
				</slot>
			</div>
			<div class="basis-5/6">
				<Breadcrumb v-if="false"/>
				<slot></slot>
			</div>
		</div>
	</div>
</template>