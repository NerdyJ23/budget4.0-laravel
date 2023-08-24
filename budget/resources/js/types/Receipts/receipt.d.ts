import { ReceiptItem } from "./receiptItem";

export interface Receipt {
	id?: string;
	store: string;
	date: string;
	location: string;
	reference: string;
	cost?: number;
	category?: string;
	createdUtc?: Date;
	editedUtc?: Date;
	items?: ReceiptItem;
}