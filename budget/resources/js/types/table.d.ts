export interface TableItem {
	key: string,
	item: Object,
	options?: Object
};

export interface TableSort {
	key: string | null,
	direction: 'asc' | 'desc' | 'ASC' | 'DESC'
}