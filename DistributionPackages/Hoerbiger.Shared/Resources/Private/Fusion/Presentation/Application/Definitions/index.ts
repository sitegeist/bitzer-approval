/* FORMS */
export interface SelectboxOption {
	key: string;
	value?: string;
}

/* NEOS */
export type NeosEvent = Event & {
	detail: {
		node: {
			identifier: string,
		},
		element: HTMLElement
	}
}
