import receiptApi from "@/services/Receipts/receiptApi";
import { defineStore } from 'pinia';

//types
import { ReceiptItemCategory } from "@/types/Receipts/receiptItemCategory";
import { Receipt } from "@/types/Receipts/receipt";

const today = new Date;
export const useReceiptStore = defineStore('receiptStore', {
	state: () => ({
		selected: {
			month: today.getMonth() as number,
			year: today.getFullYear() as number
		},
		receipts: [] as Receipt[],
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
		},
		filterReceipts(): Array<Receipt> {
			let filteredReceipts: Array<Receipt> = JSON.parse(JSON.stringify(this.receipts));
			if (this.filter.receipt != '') {
				//FILTER RECEIPT
				filteredReceipts = filteredReceipts.filter((receipt) => {
					return receipt.store?.toLowerCase().includes(this.filter.receipt) ||
					receipt.location?.toLowerCase().includes(this.filter.receipt) ||
					receipt.date.toLowerCase().includes(this.filter.receipt) ||
					receipt.cost?.toString().toLowerCase().includes(this.filter.receipt) ||
					receipt.createdUtc?.toString().toLowerCase().includes(this.filter.receipt);
				});
			}

			if (this.filter.category != '') {
				filteredReceipts = filteredReceipts.filter((receipt) => {
					return receipt.category == this.filter.category;
				})
			}
			return filteredReceipts;
		},
		loadReceipts() {

		},
		setReceipts(receipts: Receipt[]) {
			this.receipts = receipts;
			this.receipts.sort((a: Receipt, b: Receipt) => {
				return new Date(a.date).getTime() - new Date(b.date).getTime();
			})
		}
	}
})