import { api } from '@/services/api';

export default {
	login(username: String, password: String) {
		const response = api().get(`/marketplace/items/`)
		.catch((error) => {
			return error.response;
		});
		return response;
	}
}