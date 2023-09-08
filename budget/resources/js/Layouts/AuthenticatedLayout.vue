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
					<SideBar class="border border-solid border-slate-800 flex flex-col sticky top-0 h-screen max-h-screen min-h-screen md:w-48">
						<PlainButtonLink class="w-full p-2" :link="route('receipts')" :current="currentPage('receipts')">
							<span class="w-full text-xl text-left">Receipts</span>
						</PlainButtonLink>
						<PlainButtonLink class="w-full p-2" :link="route('receipts.graphs')" :current="currentPage('receipts.graphs')">
							<span class="w-full text-xl text-left">Graphs</span>
						</PlainButtonLink>
						<template v-if="currentPage('receipts.graphs')">
							<PlainButtonLink class="pl-1" @click="$emit('clicked', 'monthly')"><span class="w-full text-left border-l-4 border-solid border-neutral-400">&nbsp;Monthly</span></PlainButtonLink>
							<PlainButtonLink class="pl-1" @click="$emit('clicked', 'yearly')"><span class="w-full text-left border-l-4 border-solid border-neutral-400">&nbsp;Yearly</span></PlainButtonLink>
						</template>
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