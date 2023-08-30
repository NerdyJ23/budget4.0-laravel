export interface BarItem {
	key: string;
	value: number;
	color?: BarColor; //rgba(r, g, b, a)
	text?: string; //text when bar is hovered
}

export interface BarColor {
	r: number;
	g: number;
	b: number;
	a: number;
}