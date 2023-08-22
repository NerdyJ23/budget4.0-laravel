import { api } from '@/services/api';

export default {
	login(username: string, password: string) {
		let form = new FormData();
		form.append('username', username);
		form.append('password', password);
		const response = api().post(`/user/login/`, form, {
			withCredentials: true
		}).catch((error) => {
			return error.response;
		});
		return response;
	},
	logout() {
		const response = api().get('/user/logout').catch((error) => {
			return error.response;
		});
		return response;
	}
}