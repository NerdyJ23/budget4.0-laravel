export interface TableHeader {
	name: string,
	options?: TableHeaderOptions
}
export interface TableHeaderOptions {
	sortable: boolean
}

export interface TableItem {
	key: string,
	item: Object,
	options?: TableItemOptions
};
export interface TableItemOptions {

}
export interface TableSort {
	key: string | null,
	direction: 'asc' | 'desc'
}