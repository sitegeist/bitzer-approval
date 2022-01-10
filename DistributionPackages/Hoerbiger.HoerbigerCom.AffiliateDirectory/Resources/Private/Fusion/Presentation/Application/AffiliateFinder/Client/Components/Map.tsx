import * as React from "react";
import GoogleMapReact from "google-map-react";
import cx from "classnames";

import {ActiveState, Countries, Country} from "../Definitions";

const MIN_ZOOM = 2;
const MAX_ZOOM = 7;
const CENTER = {lat: 30.145127183376133, lng: 22.5};
const MAP_ID = "6cb5b319efce68f9"; // Stored for styles in google.cloud.
const BG_COLOR = "#F0F0F0"; // Background of Application. => tailwind "neutral-lighter"

type MapViews = {
	[countryKey: string]: {
		center: google.maps.LatLngLiteral;
		bounds?:google.maps.LatLngBounds;
	};
};

const initMapViews = (countries: Countries): MapViews =>
	Object.entries(countries).reduce((mapViews, [countryKey, country]) => {
		let bounds: google.maps.LatLngBounds | undefined;

		if (country.count > 1) {
			bounds = new google.maps.LatLngBounds();

			Object.values(country.affiliateGroups).forEach(affiliateGroup =>
				affiliateGroup.forEach(({latitude: lat, longitude: lng}) => {
					bounds?.extend({lat, lng});
				})
			);
		}

		return {
			...mapViews,
			[countryKey]: {
				bounds,
				center: bounds
					? bounds.getCenter().toJSON()
					: {
						lat: Object.values(country.affiliateGroups)[0][0].latitude,
						lng: Object.values(country.affiliateGroups)[0][0].longitude,
					}
			}
		};
	}, {} as MapViews);

type MapProps = {
	googleMapsApiKey: string;
	language: string;
	countries: Countries;
	state: ActiveState;
	dispatch: React.Dispatch<any>;
};
export const Map = ({state, dispatch, ...props}: MapProps): JSX.Element => {
	const [isReadyForMarker, setIsReadyForMarker] = React.useState(false);
	const mapRef = React.useRef<google.maps.Map|null>(null);
	const mostOuterBoundsRef = React.useRef<google.maps.LatLngBounds|null>(null);
	const mapViewsOfCountries = React.useRef<MapViews>({});

	const onGoogleApiLoaded = React.useCallback(({map}: {map: google.maps.Map}) => {
		mapRef.current = map;
		mapViewsOfCountries.current = initMapViews(props.countries);
		mostOuterBoundsRef.current = Object.values(props.countries).reduce((bounds, {affiliateGroups}) => {
			Object.values(affiliateGroups).forEach(affiliates => {
				affiliates.forEach(({latitude: lat, longitude: lng}) => {
					bounds.extend({lat, lng});
				});
			});
			return bounds;
		}, new google.maps.LatLngBounds);

		setIsReadyForMarker(true);
	}, []);

	const onChange = React.useCallback(() => {
		if (isReadyForMarker && mapRef.current) {
			const mapView = mapViewsOfCountries.current[state.country?.name ?? ""];

			if (mapView) {
				if (mapView.bounds) {
					mapRef.current.fitBounds(mapView.bounds);
				} else {
					mapRef.current.setCenter(mapView.center);
					mapRef.current.setZoom(MAX_ZOOM);
				}
			} else if (mostOuterBoundsRef.current) {
				mapRef.current.fitBounds(mostOuterBoundsRef.current);
				mapRef.current.setZoom(MIN_ZOOM);
			}
		}
	}, [isReadyForMarker, state.country]);

	const onCountrySelect = (countryKey: string) => {
		if (!state.country && props.countries[countryKey]) {
			dispatch({type: "selectCountry", country: props.countries[countryKey]});
		}
	};

	const onAffiliateSelect = (geoIdentifier: string, {country}: MarkerProps) => {
		if (geoIdentifier && country) {
			dispatch({type: "selectCountry", country, geoIdentifier});
		}
	};

	React.useEffect(onChange, [isReadyForMarker, state.country]);

	return (
		<GoogleMapReact
			onChange={onChange}
			bootstrapURLKeys={{
				key: props.googleMapsApiKey,
				language: props.language,
			}}
			options={{
				mapId: MAP_ID,
				minZoom: MIN_ZOOM,
				maxZoom: MAX_ZOOM,
				// backgroundColor: BG_COLOR,
				disableDefaultUI: true,
				disableDoubleClickZoom: true,
				fullscreenControl: false,
				gestureHandling: "none",
				keyboardShortcuts: false,
				panControl: false,
			} as GoogleMapReact.MapOptions}
			yesIWantToUseGoogleMapApiInternals
			defaultCenter={CENTER}
			defaultZoom={MIN_ZOOM}
			onGoogleApiLoaded={onGoogleApiLoaded}
			onChildClick={state.country ? onAffiliateSelect : onCountrySelect}
			// onChildMouseEnter (func)
		>
			{
				isReadyForMarker
					? state.country
						? Object.values(props.countries).map(country =>
							Object.entries(country.affiliateGroups).map(([geoIdentifier, [firstAffiliateAtLocation]]) => (
								<Marker
									key={geoIdentifier}
									country={country}
									lat={firstAffiliateAtLocation.latitude}
									lng={firstAffiliateAtLocation.longitude}
									isActive={firstAffiliateAtLocation.addressCountry === state.country?.name}
								></Marker>
							))
						)
						: Object.entries(mapViewsOfCountries.current).map(([countryKey, {center}]) => (
							<Marker
								key={countryKey}
								{...center}
							/>
						))
					: null
			}
		</GoogleMapReact>
	);
};

type MarkerProps = {
	lat: number;
	lng: number;
	isActive?: boolean;
	country?: Country;
};
const Marker = (props: MarkerProps): JSX.Element => (
	<div
		className={cx(
			"-translate-x-1/2 -translate-y-1/2 rounded-full transition-all duration-1000 border border-neutral",
			props.isActive
				? "bg-decorative w-4 h-4"
				: "bg-brand w-3 h-3"
		)}
	></div>
);
