import receiptApi from "@/services/Receipts/receiptApi";

//types
import { ReceiptItemCategory } from "@/types/Receipts/receiptItemCategory";
import { ReceiptStoreState } from "@/types/Stores/receiptStore";

const today = new Date;
const state: ReceiptStoreState = {
	selectedMonth: today.getMonth(),
	selectedYear: today.getFullYear(),
	categories: [],
	filter: {
		category: "",
		receipt: ""
	}
}

const getters = {
}
const actions = {
	async loadCategories({ state }:any ) {
		const response = await receiptApi.loadCategories();
		if (response.status === 200) {
			for (const cat of response.data.result) {
				const category: ReceiptItemCategory = {
					id: cat.id,
					name: cat.name,
					archived: cat.archived
				};
				state.categories.push(category);
			}
		} else {
			console.error('categories failed to load');
		}
	}
}
export default {
	state,
	actions,
	getters
}