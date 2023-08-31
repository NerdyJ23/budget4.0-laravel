import axios from 'axios';
import { useGenericStore } from '@/store/genericPiniaStore';

function api() {
	const store = useGenericStore();
	const apiUrl = store.api;
    const api = axios.create({
        baseURL: apiUrl,
        headers: {
            'Access-Control-Allow-Methods': 'GET, POST, PUT, PATCH, DELETE',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    });
    return api;
}

export {
	api
}