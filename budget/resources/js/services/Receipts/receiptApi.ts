import { api } from '@/services/api';
import { Receipt } from '@/types/Receipts/receipt';
import { ReceiptItem } from '@/types/Receipts/receiptItem';

export default {
	listReceipts(month: number, year: number) {
		const response = api().get(`/receipt/?month=${month}&year=${year}`)
		.catch((error) =>  {
			return error.response;
		});
		return response;
	},

	createReceipt(receipt: Receipt) {
		const form = new FormData();
		form.append('name', receipt.store);
		form.append('date', receipt.date);
		form.append('location', receipt.location);
		form.append('reference', receipt.reference);
		form.append('items', JSON.stringify(receipt.items));

		const response = api().post(`/receipt`, form).catch((error) => {
			return error.response;
		});
		return response;
	},
	//Categories
	loadCategories(archived = false) {
		const response = api().get(`/receipt/category?archived=${archived ? 1 : 0}`)
		.catch((error) => {
			return error.response;
		});
		return response;
	},

	//Documents
	uploadDocument(file: File, receipt: Receipt) {
		const formData = new FormData();
		formData.append('file', file);
		const response = api().post(`/receipt/${receipt.id}/documents`, formData, {
			headers: {
				'Content-Type': 'multipart/form-data'
			}
		}).catch((error) => {
			return error.response;
		});
		return response;
	}
}