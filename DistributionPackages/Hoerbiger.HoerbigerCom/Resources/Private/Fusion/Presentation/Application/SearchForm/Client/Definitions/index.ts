export type Labels = {
	[key: string]: string;
}

export interface ApplicationData {
	endpointUri: string;
	sortingPropertyOptions: string[];
	periodOptions: string[];
	labels: Labels;
	language: string;
	mostWantedEndpointUri: string;
}

export interface SearchRequestParams {
	language: string;
	searchTerm: string|null;
	sortingProperty: string|null;
	period: string|null;
	page: number;
	types: string[];
}

export interface SearchResult {
	identifier: string;
	content: string;
}

export interface Pagination {
	numberOfPages: number;
	totalNumberOfResults: number;
}

export interface SearchResponse extends Pagination {
	results: SearchResult[];
	typeOptions: {
		identifier: string;
		totalNumberOfResults: number;
	}[];
}

export type SearchRequest = (params: SearchRequestParams)
	=> Promise<SearchResponse|null>
