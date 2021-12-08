import {SearchRequestParams, SearchRequest, SearchResponse} from "../Definitions";

export const requestSearchApi = (api: string): SearchRequest =>
	(params: SearchRequestParams) => {
		return new Promise<SearchResponse|null>((resolve) => {
			const url = new URL(api);

			url.searchParams.append("query", JSON.stringify(params));

			(async () => {
				const response = await fetch(url.toString());

				if (response.status === 200) {
					const data = await response.json() as SearchResponse;
					console.log({data});
					resolve(data);
				}
				resolve(null);
			})();
		});
	};
