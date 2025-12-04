import * as React from "react";

import {
	ApplicationData,
	AvailableFilter,
	EventFinderRequestParams,
	EventFinderSearchRequest,
	EventFinderSearchResponse,
} from "./Definitions";

import { requestEventsFinderApi } from "./Request/requestEventsFinderApi";

import cx from "classnames";
import {Paginator} from "./Components/Paginator";
import {FilterForms} from "./Components/FilterForms";


export const EventFinderClient = (props: ApplicationData): JSX.Element => {
	const isInitialized = React.useRef(false);
	const [isFetching, setIsFetching] = React.useState(false);
	const $results = React.useRef<HTMLDivElement>(null);
	const [archived, setArchived] = React.useState<boolean>(false);

	const [requestParams, setRequestParams] = React.useState<EventFinderRequestParams>({
		locale: props.locale,
		activeFilters: [],
		page: 1,
		archived: false
	});

	const onUpdateRequestParams = (update: Partial<EventFinderRequestParams>) => {
		setRequestParams(state => ({
			...state,
			...update,
			page: update.page ?? 1
		}));
	};

	const request = React.useRef<EventFinderSearchRequest>(requestEventsFinderApi(props.endpointUri));
	const [response, setResponse] = React.useState<EventFinderSearchResponse|null>(null);

	const [availableFilters, setAvailableFilters] = React.useState<AvailableFilter[]>([]);

	React.useEffect(() => {
		let isActive = true;

		(async () => {
			setIsFetching(true);
			const response = await request.current(requestParams);

			if (isActive) {
				isInitialized.current = true;
				setResponse(response);
				if (response?.availableFilters) {
					setAvailableFilters(response.availableFilters);
				}
				setIsFetching(false);
			}
		})();

		return () => {
			isActive = false;
		};
	}, [requestParams]);

	return (
		isInitialized.current && response
			?(
				<div>
					<FilterForms
						totalNumberOfResults={response.totalNumberOfResults}
						onSubmit={onUpdateRequestParams}
						availableFilter={availableFilters}
						getLabelFor={props.getLabelFor}
					/>

					{/* Results */}
					<div
						className={cx(
							"grid grid-rows-1 gab-sm",
							!isFetching && "hidden"
						)}
					>
						{
							<div>{props.getLabelFor("label.isFetching")}</div>
						}
					</div>

					<div
						ref={$results}
						style={{scrollMarginTop: "100px"}}
						className={cx(
							"grid w-full mt-space32",
							(response?.results.length == 1) && "grid-cols-1 mx-auto md:max-w-[66%] lg:max-w-[33%]",
							(response?.results.length == 2) && "grid-cols-1 gap-xl md:grid-cols-2 md:gap-x-sm md:gap-y-md mx-auto lg:max-w-[66%]",
							(response?.results.length > 2) && "grid-cols-1 gap-xl md:grid-cols-2 md:gap-x-sm md:gap-y-md lg:grid-cols-3",
							isFetching && "hidden"
						)}
					>
						{
							response?.results.map(result => (
								<div
									key={result.identifier}
									className="h-full"
									dangerouslySetInnerHTML={{__html: result.content}}
								>
								</div>
							))
						}
						{
							response?.totalNumberOfResults === 0
								? (
									<div>
										<div>{props.getLabelFor("label.noResults")}</div>
									</div>
								) : null
						}
					</div>

					{/* Paging */}
					{
						response.numberOfPages > 1
							? (
								<div className="pt-sm">
									<Paginator
										currentPage={requestParams.page}
										numberOfPages={response.numberOfPages}
										onChange={onUpdateRequestParams}
										getLabelFor={props.getLabelFor}
									/>
								</div>
							) : null
					}

				</div>
			): (
				!isInitialized.current
					? <div>{props.getLabelFor("label.initialize")}</div>
					: <div>{props.getLabelFor("label.error")}</div>
			)
	);
};
