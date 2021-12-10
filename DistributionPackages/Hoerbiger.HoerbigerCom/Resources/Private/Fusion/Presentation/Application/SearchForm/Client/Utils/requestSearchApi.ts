import {SearchRequestParams, SearchRequest, SearchResponse} from "../Definitions";

const CACHE: {
	[url: string]: SearchResponse;
} = {};

export const requestSearchApi = (api: string): SearchRequest =>
	(params: SearchRequestParams) => {
		return new Promise<SearchResponse|null>((resolve) => {
			const url = new URL(api);

			url.searchParams.append("query", JSON.stringify(params));

			if (CACHE[url.toString()]) {
				resolve(CACHE[url.toString()]);
			}

			(async () => {
				const response = await fetch(url.toString());

				if (response.status === 200) {
					const data = await response.json() as SearchResponse;
					CACHE[url.toString()] = data;
					resolve(data);
				}
				resolve(null);
			})();
		});
	};
