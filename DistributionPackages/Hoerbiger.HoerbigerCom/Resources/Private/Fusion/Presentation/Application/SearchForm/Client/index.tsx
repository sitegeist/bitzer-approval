import * as React from "react";
import {ApplicationData, SearchRequest, SearchRequestParams, SearchResult} from "./Definitions";

export {ApplicationData};

import {useWithInitialRequest} from "./Utils/useWithInitialRequest";
import {requestSearchApi} from "./Utils/requestSearchApi";
import {Filter} from "./Components/Filter";

export const Client = (props: ApplicationData): JSX.Element => {
	const [requestParams, setRequestParams] = React.useState<SearchRequestParams>({
		language: props.language,
		searchTerm: null,
		sortingProperty: props.sortingPropertyOptions[0] ?? "",
		period: null,//props.periodOptions.pop() ?? "",
		page: 1,
		types: []
	});
	const request = React.useRef<SearchRequest>(requestSearchApi(props.endpointUri));
	const {isInitialized, initialData} = useWithInitialRequest(request.current, requestParams);
	const typeOptions = React.useRef<string[]>([]);
	const [results, setResults] = React.useState<SearchResult[]>([]);


	React.useEffect(() => {
		if (isInitialized && initialData) {
			typeOptions.current = initialData.typeOptions
				.map(({identifier}) => identifier);
			setResults(initialData.results);
		}
	}, [isInitialized]);

	React.useEffect(() => {
		let isActive = true;

		(async () => {
			const response = await request.current(requestParams);

			if (isActive && response) {
				setResults(response.results);
			}
		})();

		return () => {
			isActive = false;
		};
	}, [requestParams]);

	const onUpdateRequestParams = (update: Partial<SearchRequestParams>) =>
		setRequestParams(state => ({...state, ...update}));

	return isInitialized
		? (
			<div className="flex flex-col space-y-4">
				<Filter {...{
					requestParams,
					onChange: onUpdateRequestParams,
					labels: props.labels,
					availableOptions: {
						sortingProperties: props.sortingPropertyOptions,
						periods: props.periodOptions,
						types: typeOptions.current
					}
				}}/>
				<div className="grid grid-rows-1 gap-sm">
					{
						results.map(result => (
							<div
								key={result.identifier}
								dangerouslySetInnerHTML={{__html: result.content}}
							/>
						))
					}
				</div>
			</div>
		)
		: (
			<div>TL:Initialisiere ...</div>
		);
};
