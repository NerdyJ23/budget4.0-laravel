<script setup lang="ts">
import { defineComponent } from 'vue';
import HeaderBar from '@/Components/Navigation/HeaderBar.vue';
import SideBar from '@/Components/SideBar.vue';
import Breadcrumb from '@/Components/Navigation/Breadcrumb.vue';
import PlainButtonLink from '@/Components/Navigation/PlainButtonLink.vue';
</script>
<script lang="ts">
export default defineComponent({
	name: 'BasicLayout',
	components: {
	}
})
</script>
<template>
	<div class="h-screen flex flex-col">
		<header-bar></header-bar>
		<div class="inline-flex flex-row h-full">
			<div class="basis-1/6">
				<slot name="sidebar">
					<SideBar class="border border-solid border-slate-800">
						<PlainButtonLink text="Receipts" :link="route('receipts')"></PlainButtonLink>
					</SideBar>
				</slot>
			</div>
			<div class="basis-5/6">
				<Breadcrumb />
				<slot></slot>
			</div>
		</div>
	</div>
</template>