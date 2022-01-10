import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {ActiveState, ActiveStateAction, Affiliate, ApplicationData, Countries, Country} from "./Definitions";
import {Map} from "./Components/Map";
import {AffiliatesList} from "./Components/AffiliatesList";
import {CountryForm} from "./Components/CountryForm";

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

export const Client = ({affiliates, googleMapsApiKey, language, getLabelFor}: ApplicationData): JSX.Element => {
	const [state, dispatch] = React.useReducer((state: ActiveState, action: ActiveStateAction) => {
		switch (action.type) {
		case "selectCountry":
			return {...state, country: action.country};
		case "selectGeoIdentifier":
			return {...state, country: action.country, geoIdentifier: action.geoIdentifier};
		case "reset":
			return {...state, country: null, geoIdentifier: null};
		case "unselectGeoIdentifier":
			return {...state, geoIdentifier: null};

		}
	}, {country: null, geoIdentifier: null});

	const countries = React.useRef<Countries>(groupAffiliatesByCountries(affiliates));
	const onSubmit = (formData: any) => {
		const country = countries.current[formData?.activeCountry];

		dispatch(country
			? {type: "selectCountry", country}
			: {type: "reset"}
		);
	};
	const isMapToBeRendered = useMediaQuery("(min-width: 980px)");
	const hasAffiliatesList = isMapToBeRendered
		? state.country && state.country.count > 1
		: state.country && state.country.count > 0;

	return (
		<div className="flex flex-col space-y-sm">
			<CountryForm
				onSubmit={onSubmit}
				countries={countries.current}
				currentCountry={state.country}
				getLabelFor={getLabelFor}
			/>
			<div className={cx(
				"grid",
				isMapToBeRendered && hasAffiliatesList && "grid-cols-affiliates-map-list"
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
						</div>
					)
				}
				{

					hasAffiliatesList && state.country
						? (
							<AffiliatesList
								affiliates={state.country?.affiliates}
								hasPages={isMapToBeRendered && state.country?.count > 2}
							/>
						)
						: null
				}
			</div>
		</div>
	);

};
