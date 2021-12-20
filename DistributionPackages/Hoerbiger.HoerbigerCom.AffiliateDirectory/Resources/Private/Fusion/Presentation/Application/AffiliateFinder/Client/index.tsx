import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import {Form} from "react-final-form";
import cx from "classnames";

import {ActiveState, ActiveStateAction, Affiliate, ApplicationData, Countries, Country} from "./Definitions";
import {Map} from "./Components/Map";
import {Selectbox} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";
import { AffiliateCard } from "./Components/AffiliateCard";

const groupAffiliatesByCountries = (affiliates: Affiliate[]): Countries => affiliates
	.reduce((memo: Countries, affiliate: Affiliate) => {
		const geoIdentifier = `${affiliate.longitude}_${affiliate.latitude}`;

		memo[affiliate.addressCountry] = memo[affiliate.addressCountry] ?? {
			name: affiliate.addressCountry,
			affiliates: {},
			count: 0,
		};

		return {
			...memo,
			[affiliate.addressCountry]: {
				name: memo[affiliate.addressCountry].name,
				affiliates: {
					...memo[affiliate.addressCountry].affiliates,
					[geoIdentifier]: (memo[affiliate.addressCountry].affiliates[geoIdentifier] ?? [])
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

	const isExtendedMapView = useMediaQuery("(min-width: 980px)");
	const countries = React.useRef<Countries>(groupAffiliatesByCountries(affiliates));
	const onSubmit = (formData: any) => {
		const country = countries.current[formData?.activeCountry];
		dispatch(country
			? {type: "selectCountry", country}
			: {type: "reset"}
		);
	};
	const hasListViewForAffiliates = state.country && state.country.count > 1;

	return (
		<div className="flex flex-col space-y-sm">
			<Form onSubmit={onSubmit}>
				{({handleSubmit}) => (
					<form
						onSubmit={handleSubmit}
						className="flex justify-center"
					>
						<div className="w-full max-w-[400px]">
							<Selectbox
								name="activeCountry"
								label=""
								initialValue={state.country?.name ?? ""}
								options={[
									{key: "", value: getLabelFor("input.activeCountry.all")},
									...Object.keys(countries.current).map(key => ({key}))
								]}
								onChange={() => handleSubmit()}
							/>
						</div>
					</form>
				)}
			</Form>
			<div className={cx(
				"grid",
				isExtendedMapView && hasListViewForAffiliates && "grid-cols-affiliates-map-list"
			)}>
				{
					isExtendedMapView && (
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
					hasListViewForAffiliates
						? (
							<div className={cx(
								"flex flex-col space-y-xs p-xs bg-light"
							)}>
								{
									// Render two cards with different addresses (explicite case)
									state.country?.count == 2 && Object.keys(state.country?.affiliates).length == 2
										? Object.values(state.country?.affiliates).map(affiliateGroup =>
											affiliateGroup.map(affiliate => (
												<AffiliateCard key={affiliate.identifier} {...affiliate}/>
											))
										)
										: null

								}
							</div>
						)
						: null
				}
			</div>
		</div>
	);

};
