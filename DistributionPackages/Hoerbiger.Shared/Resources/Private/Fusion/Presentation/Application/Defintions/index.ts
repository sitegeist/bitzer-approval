/* NEOS */
export type NeosEvent = Event & {
	detail: {
		node: {
			identifier: string,
		},
		element: HTMLElement
	}
}
