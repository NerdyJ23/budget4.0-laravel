import { ReceiptItem } from "./receiptItem";
import { ReceiptDocument } from "./receiptDocument";
export interface Receipt {
	id?: string;
	store?: string;
	date: string;
	location?: string;
	reference?: string;
	cost?: number;
	category?: string;
	createdUtc?: Date;
	editedUtc?: Date;
	items: ReceiptItem[];
	documents: Array<ReceiptDocument | File>;
}