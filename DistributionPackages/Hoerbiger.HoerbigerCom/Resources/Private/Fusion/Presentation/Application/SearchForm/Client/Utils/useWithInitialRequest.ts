import * as React from "react";

import {SearchRequest, SearchRequestParams, SearchResponse} from "../Definitions";

export const useWithInitialRequest = (request: SearchRequest, requestParams: SearchRequestParams): {
	isInitialized: boolean;
	initialData: SearchResponse|null;
} => {
	const isInitialized = React.useRef(false);
	const [initialData, setInitialData] = React.useState<any>({});

	React.useEffect(() => {
		(async () => {
			const response = await request(requestParams);

			if (response) {
				isInitialized.current = true;

				console.log(333, response);
				setInitialData(response);
			}
		})();
	}, []);

	return {
		isInitialized: isInitialized.current,
		initialData
	};
};
