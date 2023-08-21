import axios from 'axios';
import GenericStore from "@/store/genericStore";

function api() {
	const apiUrl = GenericStore.state.api;
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