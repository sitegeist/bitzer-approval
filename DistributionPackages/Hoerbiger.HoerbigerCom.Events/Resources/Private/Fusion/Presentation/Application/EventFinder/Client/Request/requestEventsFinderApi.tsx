import {
	EventFinderRequestParams,
	EventFinderSearchRequest,
	EventFinderSearchResponse
} from "../Definitions";

const CACHE: {
	[url: string]: EventFinderSearchResponse;
} = {};

export const requestEventsFinderApi = (api: string): EventFinderSearchRequest =>
	(params: EventFinderRequestParams) => {
		return new Promise<EventFinderSearchResponse|null>((resolve) => {
			const url = new URL(api);

			url.searchParams.append("query", JSON.stringify(params));

			if (CACHE[url.toString()]) {
				resolve(CACHE[url.toString()]);
			}

			(async () => {
				const response = await fetch(url.toString());

				if (response.status === 200) {
					const data = await response.json() as EventFinderSearchResponse;
					CACHE[url.toString()] = data;
					resolve(data);
				}
				resolve(null);
			})();
		});
	};
