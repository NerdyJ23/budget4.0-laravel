<template>
	<div class="container mx-auto p-2">
		<SideBarItem text="test222" link="test">
			<template #text>
				text here
			</template>
		</SideBarItem>
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