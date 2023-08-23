<template>
	<div class="p-4">
		<div class="table-header">
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
			<span>${{ receipt.cost }}</span>
			<span>{{ receipt.category }}</span>
		</div>
	</div>
</template>
<style lang="scss" scoped>
.table-header {
	@apply grid grid-cols-5 font-semibold text-center;
	&-text {
		@apply cursor-pointer hover:bg-slate-200;
	}
}

.table-row {
	@apply grid grid-cols-5 cursor-pointer hover:bg-slate-100 text-center;
}
</style>