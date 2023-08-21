import { ReceiptItemCategory } from "./receiptItemCategory";

export interface ReceiptItem {
	id: string;
	name: string;
	count: number;
	cost: number;
	category: ReceiptItemCategory;
}