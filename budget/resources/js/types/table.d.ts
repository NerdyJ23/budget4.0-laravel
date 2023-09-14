export interface TableHeader {
	name: string,
	options?: TableHeaderOptions
}
export interface TableHeaderOptions {
	sortKey?: string,
	finance?: {
		operator: '$',
		currency?: 'AUD',
		decimals: number
	}
}

export interface TableItem {
	key: string,
	item: any,
	options?: TableItemOptions
};
export interface TableItemOptions {

}
export interface TableSort {
	key: string | null,
	direction: 'asc' | 'desc'
}