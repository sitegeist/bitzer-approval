export type Labels = {
	[key: string]: string;
}

export type LabelGetter = (key: string, fallback?: string) => string;

export interface ApplicationData {
	endpointUri: string;
	sortingPropertyOptions: string[];
	periodOptions: string[];
	language: string;
	mostWantedEndpointUri: string;
	csrfProtectionToken: string;
	getLabelFor: LabelGetter;
	searchTerm: string;
}

export interface SearchRequestParams {
	language: string;
	searchTerm: string|null;
	sortingProperty: string;
	period: string|null;
	page: number;
	types: string[];
}

export interface SearchResult {
	identifier: string;
	content: string;
}
export interface TypeOption {
	identifier: string;
	totalNumberOfResults: number;
}

export interface SearchResponse {
	numberOfPages: number;
	totalNumberOfResults: number;
	results: SearchResult[];
	typeOptions: TypeOption[];
}

export type SearchRequest = (params: SearchRequestParams)
	=> Promise<SearchResponse|null>
