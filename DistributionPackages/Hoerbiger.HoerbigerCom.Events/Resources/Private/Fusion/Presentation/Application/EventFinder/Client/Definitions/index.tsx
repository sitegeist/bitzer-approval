export type LabelGetter = (key: string, fallback?: string) => string;

export interface ApplicationData {
	locale: string;
	getLabelFor: LabelGetter;
	endpointUri: string;
	applicationRoot: HTMLElement;
	showArchived: boolean;
}

export interface ActiveFilters {
	name: string;
	value: string;
}

export interface FilterFormProps {
	totalNumberOfResults: number;
	onSubmit: (update: Partial<EventFinderRequestParams>) => void;
	availableFilter: AvailableFilter[];
	getLabelFor: LabelGetter;
}

export interface EventFinderRequestParams {
	locale: string;
	activeFilters: ActiveFilters[];
	page: number;
	archived: boolean;
}

export type EventFinderSearchRequest = (params: EventFinderRequestParams)
	=>  Promise<EventFinderSearchResponse|null>

export interface AvailableFilter {
	name: string;
	options: FilterOption[];
}

export interface FilterOption {
	value: string;
	totalNumberOfResults: number;
}

export interface AvailableFilters {
	year: FilterOption[];
}

export interface EventFinderSearchResult {
	identifier: string;
	content: string;
}

export interface EventFinderSearchResponse {
	numberOfPages: number;
	results: EventFinderSearchResult[];
	totalNumberOfResults: number;
	availableFilters: AvailableFilter[]
}

export interface DynamicFilters {
	selectedFilters: Map<string, string>,
}
