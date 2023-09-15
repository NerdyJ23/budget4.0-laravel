<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import HeaderBar from '@/Components/Navigation/HeaderBar.vue';
import SideBar from '@/Components/SideBar.vue';
import Breadcrumb from '@/Components/Navigation/Breadcrumb.vue';
import PlainButtonLink from '@/Components/Navigation/PlainButtonLink.vue';
import { BiArrowRightShort } from "oh-vue-icons/icons";
import { addIcons } from "oh-vue-icons";
import { onClickOutside } from '@vueuse/core';

const sidebar = ref<HTMLElement | null>(null);
const show = reactive({
	sidebar: false
});

const currentPage = (name: string):boolean =>{
	return route(name) == window.location.origin + window.location.pathname;
}
const toggleSidebar = () => {
	console.log(`sidebar: ${show.sidebar}`)
	show.sidebar = !show.sidebar
	console.log(`sidebar is now: ${show.sidebar}`);
};

onMounted(() => {
	onClickOutside(sidebar.value, (event) => {
		show.sidebar = false;
	});
})
addIcons( BiArrowRightShort );
</script>
<template>
	<div class="flex flex-col h-screen min-h-screen max-h-screen">
		<header-bar></header-bar>
		<div class="inline-flex flex-row relative">
			<div>
				<slot name="sidebar">
					<SideBar ref="sidebar"
						:class="`transition-all ease-in-out flex flex-col absolute lg:sticky top-0 h-screen max-h-screen min-h-screen lg:w-48 ${show.sidebar ? 'w-48 force-front' : 'w-0'} bg-white border-r border-slate-800`">
						<PlainButtonLink :class="`w-full p-2 ${show.sidebar ? '' : 'invisible lg:visible'}`" :link="route('receipts')" :current="currentPage('receipts')">
							<span class="w-full text-md lg:text-lg text-left">Receipts</span>
						</PlainButtonLink>
						<PlainButtonLink :class="`w-full p-2 ${show.sidebar ? '' : 'invisible lg:visible'}`" :link="route('receipts.graphs')" :current="currentPage('receipts.graphs')">
							<span class="w-full text-md lg:text-lg text-left">Graphs</span>
						</PlainButtonLink>
						<div class="h-full absolute inset-y-0 -right-7 flex flex-col lg:invisible visible">
							<div class="bg-white rounded-r-xl rounded-l-sm my-auto py-auto align-center hover:bg-slate-200 border-y border-r border-slate-800">
								<VIcon name="bi-arrow-right-short" :class="`${show.sidebar ? 'rotate-180' : ''}`" scale="1.5" @click="toggleSidebar"/>
							</div>
						</div>
					</SideBar>
				</slot>
			</div>
			<div :class="`w-full overscroll-y-contain ${show.sidebar ? 'grayscale blur-[1px]' : ''}`">
				<Breadcrumb v-if="false"/>
				<slot></slot>
			</div>
		</div>
	</div>
</template>