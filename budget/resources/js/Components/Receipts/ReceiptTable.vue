<script setup lang="ts">
import { defineComponent } from 'vue';

const reload = () => {};

defineExpose({reload});
</script>
<script lang="ts">
export default defineComponent({name: 'ReceiptTable'})
</script>
<template>
	<div class="mx-2">
		<div class="table-header rounded-t-md">
			<span class="table-header-text">Reference Number</span>
			<span class="table-header-text">Store</span>
			<span class="table-header-text">Location</span>
			<span class="table-header-text">Cost</span>
			<span class="table-header-text">Category</span>
		</div>
		<div v-for="receipt of $page.props.receipts" class="table-row">
			<span>{{ receipt.reference }}</span>
			<span>{{ receipt.store }}</span>
			<span>{{ receipt.location }}</span>
			<span>${{ receipt.cost.toFixed(2) }}</span>
			<span>{{ receipt.category}}</span>
		</div>
	</div>
</template>
<style lang="scss" scoped>
.table {
	&-header {
		@apply grid grid-cols-5 font-semibold text-center bg-amber-500/80;
		&-text {
			@apply cursor-pointer hover:bg-amber-500 py-2 first-of-type:rounded-tl-md last-of-type:rounded-tr-md;
		}
	}
	&-row {
		@apply grid grid-cols-5 cursor-pointer hover:bg-slate-300 text-center odd:bg-gray-200 even:bg-neutral-100;
	}
}

</style>