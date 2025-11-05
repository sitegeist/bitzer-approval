import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {ActiveState, ActiveStateAction, Affiliate, ApplicationData, Countries} from "./Definitions";
import {Map} from "./Components/Map";
import {AffiliatesList} from "./Components/AffiliatesList";
import {CountryForm} from "./Components/CountryForm";
import {StampedeIcon} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components";
import { AffiliateCard } from "./Components/AffiliateCard";

const groupAffiliatesByCountries = (affiliates: Affiliate[]): Countries => affiliates
	.reduce((memo: Countries, affiliate: Affiliate) => {
		const geoIdentifier = `${affiliate.longitude}_${affiliate.latitude}`;

		memo[affiliate.addressCountry] = memo[affiliate.addressCountry] ?? {
			name: affiliate.addressCountry,
			affiliates: [],
			affiliateGroups: {},
			count: 0,
		};

		return {
			...memo,
			[affiliate.addressCountry]: {
				name: memo[affiliate.addressCountry].name,
				affiliates: memo[affiliate.addressCountry].affiliates.concat(affiliate),
				affiliateGroups: {
					...memo[affiliate.addressCountry].affiliateGroups,
					[geoIdentifier]: (memo[affiliate.addressCountry].affiliateGroups[geoIdentifier] ?? [])
						.concat(affiliate),
				},
				count: memo[affiliate.addressCountry].count + 1
			}
		};
	}, {});

const initMapsFallBack = (): boolean => {
	let response = true;
	if (typeof window.CCM == "undefined") {
		response = false;
	} else {
		window.CCM.acceptedEmbeddings.forEach((element: { name: string; }) => {
			if (element.name === "Google Maps") {
				response = false;
			}
		});
	}

	return response;
};

export const Client = ({affiliates, googleMapsApiKey, language, getLabelFor}: ApplicationData): JSX.Element => {
	const [mapsFallback, setMapsFallBack] = React.useState<boolean>(initMapsFallBack());

	const countries = React.useRef<Countries>(groupAffiliatesByCountries(affiliates));
	const [state, dispatch] = React.useReducer((state: ActiveState, action: ActiveStateAction) => {
		switch (action.type) {
		case "selectCountry":
			return {...state, country: action.country, geoIdentifier: Object.keys(action.country.affiliateGroups)[0]};
		case "selectGeoIdentifier":
			return {...state, country: action.country, geoIdentifier: action.geoIdentifier};
		case "reset":
			return {...state, country: null, geoIdentifier: null};
		case "unselectGeoIdentifier":
			return {...state, geoIdentifier: null};
		default:
			return state;
		}
	}, {country: null, geoIdentifier: null});

	const onSubmit = (formData: any) => {
		const country = countries.current[formData?.activeCountry];

		dispatch(country
			? {type: "selectCountry", country}
			: {type: "reset"}
		);
	};
	const isMapToBeRendered = useMediaQuery("(min-width: 980px)");
	const hasAffiliatesList = state.country && state.country.count > 0;

	return (
		<div className="flex flex-col space-y-sm">
			{
				mapsFallback
					? <div
						className="flex flex-col font-sans-regular"
					>
						<div>
							{getLabelFor("maps.hintOne")}
						</div>
						<div>
							{getLabelFor("maps.hintTwo")}
						</div>
						<div>
							<div
								className="cursor-pointer whitespace-nowrap overflow-ellipsis flex justify-center items-center space-x-[1em] min-h-touch max-h-touch font-semibold font-sans-bold px-0 [&amp;_svg]:pt-[3px]"
								onClick={
									() => {
										window.CCM.openWidget();
									}
								}
							>
								<div
									className="[Layout.Grid (iconLeft)] grid w-full gap-x-ch-xs grid-cols-2-max-1fr items-center"
								>
									<svg
										className="w-[1em] h-[1em]"
										viewBox="0 0 14 9"
										fill="none"
										xmlns="http://www.w3.org/2000/svg"
									>
										<path
											fillRule="evenodd"
											clipRule="evenodd"
											d="M8.85355 0.146484L13.2071 4.50004L8.85355 8.85359L8.14645 8.14648L11.2929 5.00004H0V4.00004H11.2929L8.14645 0.853591L8.85355 0.146484Z"
											fill="currentColor"
										></path>
									</svg>
									{getLabelFor("maps.hintButton")}
								</div>
							</div>
						</div>
					</div>
					: <div className={cx(
						"grid",
						"grid-cols-1 xl:grid-cols-affiliates-map-list gap-sm"
					)}>
						{
							isMapToBeRendered && (
								<div className="relative w-full h-0 pt-ratio-google-map">
									<div className="absolute inset-0">
										<Map
											{...{googleMapsApiKey, language, state, dispatch}}
											countries={countries.current}
										/>
									</div>
									{
										state.country
											? (
												<div
													className="absolute top-xs left-xs bg-decorative text-decorative-contrast p-ch rounded-ch cursor-pointer"
													onClick={() => dispatch({type: "reset"})}
												>
													<StampedeIcon iconName="globe" size={2}/>
												</div>
											)
											: null
									}
								</div>
							)
						}
						<div className="flex flex-col space-y-sm w-full">
							<CountryForm
								onSubmit={onSubmit}
								countries={countries.current}
								currentCountry={state.country}
								getLabelFor={getLabelFor}
							/>
							{
								hasAffiliatesList && state.country
									? (
										<AffiliatesList
											affiliateGroups={state.country?.affiliateGroups}
											hasPages={isMapToBeRendered}
											selectedGeoIdentifier={state.geoIdentifier}
											onSelectGeoIdentifier={(geoIdentifier: string) => {
												if (state.country) {
													dispatch({
														type: "selectGeoIdentifier",
														country: state.country,
														geoIdentifier
													});
												}
											}}
										/>
									)
									: null
							}
						</div>
					</div>

			}
		</div>
	);

};
