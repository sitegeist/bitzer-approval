
import * as React from "react";
import cx from "classnames";

import {ApplicationData, SearchRequest, SearchRequestParams, SearchResponse} from "./Definitions";
import {requestSearchApi} from "./Utils/requestSearchApi";
import {mostWantedApi} from "./Utils/mostWantedApi";
import {Filter, SearchInput, Sorter, Summary, Paginator} from "./Components";
import {StampedeIcon} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components";

export {ApplicationData};

export const Client = (props: ApplicationData): JSX.Element => {
	const isInitialized = React.useRef(false);
	const $results = React.useRef<HTMLDivElement>(null);
	const [isFetching, setIsFetching] = React.useState(false);
	const [requestParams, setRequestParams] = React.useState<SearchRequestParams>({
		language: props.language,
		searchTerm: props.searchTerm ?? null,
		sortingProperty: props.sortingPropertyOptions[0] ?? "",
		period: null,
		page: 1,
		types: [],
	});
	const request = React.useRef<SearchRequest>(requestSearchApi(props.endpointUri));
	const [response, setResponse] = React.useState<SearchResponse|null>(null);
	const [areFiltersExpanded, setAreFiltersExpanded] = React.useState(false);
	const countActiveFilter = requestParams.types.length + (requestParams.period?.length ? 1 : 0);

	React.useEffect(() => {
		let isActive = true;

		(async () => {
			setIsFetching(true);
			const response = await request.current(requestParams);

			if (isActive) {
				isInitialized.current = true;
				setResponse(response);
				setIsFetching(false);
			}
		})();

		return () => {
			isActive = false;
		};
	}, [requestParams]);

	const onUpdateRequestParams = (update: Partial<SearchRequestParams>) =>
		setRequestParams(state => ({
			...state,
			...update,
			page: update.page ?? 1
		}));

	const onClickReset = (e: React.MouseEvent<HTMLElement>) => {
		e.stopPropagation();
		onUpdateRequestParams({
			period: null,
			types: [],
		});
	};

	React.useLayoutEffect(() => {
		if ($results.current instanceof HTMLElement) {
			if ($results.current.getBoundingClientRect().top < 0) {
				$results.current.scrollIntoView({
					behavior: "smooth",
					block: "start",
				});
			}
		}
	}, [response]);

	const registerForMostWanted = React.useCallback((identifier: string) => {
		mostWantedApi({
			api: props.mostWantedEndpointUri,
			csrfProtectionToken: props.csrfProtectionToken,
			language: props.language,
			nodeAggregateIdentifier: identifier
		});
	}, [props.mostWantedEndpointUri]);

	return isInitialized.current && response
		? (
			<div className="flex flex-col space-y-md">
				<div className="flex flex-col space-y-sm">
					<SearchInput
						searchTerm={requestParams.searchTerm}
						onChange={onUpdateRequestParams}
						getLabelFor={props.getLabelFor}
					/>
					<div className="flex flex-col lg:flex-row justify-between space-y-md md:space-y-sm lg:space-y-0">
						<div className={cx(
							"flex flex-col md:flex-row justify-between lg:justify-start space-y-md md:space-y-0",
							"md:flex-row md:items-center md:space-x-sm"
						)}>
							<Summary
								countResults={response.totalNumberOfResults}
								getLabelFor={props.getLabelFor}
							/>
							<div>
								<Sorter
									sortingProperty={requestParams.sortingProperty}
									sortingProperties={props.sortingPropertyOptions}
									onChange={onUpdateRequestParams}
									getLabelFor={props.getLabelFor}
								/>
							</div>
						</div>
						<div
							onClick={() => setAreFiltersExpanded(state => !state)}
							className={cx(
								"flex items-center justify-end space-x-ch text-sm font-sans-bold uppercase cursor-pointer",
								countActiveFilter > 0 && "text-brand",
							)}
						>
							<span>{props.getLabelFor("label.toggleFilters")}</span>
							{
								countActiveFilter > 0
									&& (
										<>
											<span>{`(${countActiveFilter})`}</span>
											<span onClick={onClickReset}>
												<StampedeIcon iconName="bin"/>
											</span>
										</>
									)
							}
							<StampedeIcon
								iconName="chevronDown"
								spin={areFiltersExpanded}
							/>
						</div>
					</div>
					{
						areFiltersExpanded && (
							<Filter
								types={requestParams.types}
								typeOptions={response.typeOptions}
								period={requestParams.period}
								periodOptions={props.periodOptions}
								onChange={onUpdateRequestParams}
								getLabelFor={props.getLabelFor}
							/>
						)
					}
				</div>

				{/* RESULTS */}
				<div
					ref={$results}
					style={{scrollMarginTop: "100px"}}
					className={cx(
						"grid grid-rows-1 gap-sm",
						isFetching && "invisible"
					)}
				>
					{
						response?.results.map(result => (
							<div
								onClick={() => registerForMostWanted(result.identifier)}
								key={result.identifier}
								dangerouslySetInnerHTML={{__html: result.content}}
							/>
						))
					}
				</div>
				{
					response.numberOfPages > 1
						? (
							<Paginator
								currentPage={requestParams.page}
								numberOfPages={response.numberOfPages}
								onChange={onUpdateRequestParams}
								getLabelFor={props.getLabelFor}
							/>
						)
						: null
				}
			</div>
		)
		: (
			!isInitialized.current
				? <div>{props.getLabelFor("label.isInitializing")}</div>
				: <div>{props.getLabelFor("label.requestFailed")}</div>
		);
};
