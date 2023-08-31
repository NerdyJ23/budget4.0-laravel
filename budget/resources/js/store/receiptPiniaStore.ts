import receiptApi from "@/services/Receipts/receiptApi";
import { defineStore } from 'pinia';

//types
import { ReceiptItemCategory } from "@/types/Receipts/receiptItemCategory";

const today = new Date;
export const useReceiptStore = defineStore('receiptStore', {
	state: () => ({
		selected: {
			month: today.getMonth() as number,
			year: today.getFullYear() as number
		},
		categories: [] as ReceiptItemCategory[],
		filter: {
			category: "" as string,
			receipt: "" as string
		}
	}),
	actions: {
		async loadCategories() {
			const response = await receiptApi.loadCategories();
			if (response.status === 200) {
				for (const cat of response.data.result) {
					const category: ReceiptItemCategory = {
						id: cat.id,
						name: cat.name,
						archived: cat.archived
					};
					this.categories.push(category);
				}
			} else {
				console.error('categories failed to load');
			}
		}
	}
})