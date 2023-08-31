import { defineStore } from 'pinia';
export const useGenericStore = defineStore('genericStore', {
	state: () => ({
		api: '/api'
	}),
	getters: {
		months: () => ['January','February','March','April','May','June','July','August','September','October','November','December'],
		weekdays: () => ['Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
	}
})