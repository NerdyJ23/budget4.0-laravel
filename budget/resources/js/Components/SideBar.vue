<template>
	<div class="mx-auto h-full">
		<slot></slot>
	</div>
</template>
<script lang="ts">
import { defineComponent } from 'vue';
import SideBarItem from './SideBarItem.vue';

export default defineComponent({
	nane: "SideBar",
	components: {
		SideBarItem
	}
});
</script>