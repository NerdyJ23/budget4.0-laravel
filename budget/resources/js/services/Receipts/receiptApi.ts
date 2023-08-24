import { api } from '@/services/api';

export default {
	listReceipts(month: number, year: number) {
		const response = api().get(`/receipt/?month=${month}&year=${year}`)
		.catch((error) =>  {
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
	}
}