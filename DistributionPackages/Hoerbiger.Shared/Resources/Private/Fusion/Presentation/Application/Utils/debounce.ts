type Callback = (...args: any[]) => void;

export const debounce = (func: Callback, delay: number): Callback => {
	let inDebounce: number;

	return (...args: any[]) => {
		window.clearTimeout(inDebounce);
		inDebounce = window.setTimeout(() => func.apply(this, args), delay);
	};
};
