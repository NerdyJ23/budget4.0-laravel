
const state = {
	api: '/api',
	months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
	weekday: ['Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'],
  }

const getters = {
	getMonthAsString({state}, value) {
		return state.months[value];
	},
	getWeekdayAsString({state}, value) {
		return state.weekday[value];
	},
	getMonthListAsStrings(state) {
		return state.months;
	},
	getWeekdayListAsString(state) {
		return state.weekday;
	}
  }
const actions = {
}
export default {
	state,
	actions,
	getters
}