<script setup lang="ts">
import { defineComponent } from 'vue';
import VueTextField from '@/Components/Inputs/VueTextField.vue';
</script>

<script lang="ts">
defineComponent({
	name: "ReceiptDialogItem"
})
</script>
<template>
	<div class="table mt-4 px-2 pt-1">
		<div class="table-head grid grid-cols-5 gap-2 mb-2">
			<span class="table-head-text">Name</span>
			<span class="table-head-text text-center">Count</span>
			<span class="table-head-text text-center">Cost</span>
			<span class="table-head-text">Total</span>
			<span class="table-head-text">Category</span>
		</div>
		<div class="grid grid-cols-5 gap-2">
			<VueTextField class="self-center" placeholder="Item Name" name="item_name" />
			<VueTextField class="self-center mx-auto w-[75px]" name="item_count" />
			<div class="inline-flex flex-row w-[75px] self-center justify-self-center">
				<span class="text-center text-xl">$</span>
				<VueTextField placeholder="Cost" name="item_cost" />
			</div>
			<span class="text-xl self-center align-self-center">$ total</span>
			<div class="self-center">
				<span>
					Category List here
				</span>
				<div class="inline-flex flex-row">
					Delete
					Edit
				</div>
			</div>
		</div>
	</div>
</template>
<style lang="scss">
.table {
	&-head {
		@apply bg-slate-200;
		&-text {
			@apply text-lg font-semibold;
		}
	}
}
</style>