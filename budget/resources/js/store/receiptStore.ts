import receiptApi from "@/services/Receipts/receiptApi";

//types
import { Receipt } from '@/types/Receipts/receipt';

const today = new Date;
const state = {
	selectedMonth: today.getMonth(),
	selectedYear: today.getFullYear()
}

const getters = {
}
const actions = {
}
export default {
	state,
	actions,
	getters
}