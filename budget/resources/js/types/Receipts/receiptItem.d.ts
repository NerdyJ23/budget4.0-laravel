import { ReceiptItemCategory } from "./receiptItemCategory";

export interface ReceiptItem {
	name: string;
	count: number;
	cost: number;
	category: ReceiptItemCategory | string;
	id?: string;
}