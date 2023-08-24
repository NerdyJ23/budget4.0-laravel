import { ReceiptItemCategory } from "../Receipts/receiptItemCategory";

export interface ReceiptStoreState {
	selectedMonth: number;
	selectedYear: number;
	categories: ReceiptItemCategory[];
}