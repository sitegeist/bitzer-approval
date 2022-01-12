export type LabelGetter = (key: string, fallback?: string) => string;

export type Labels = {
	[key: string]: string
}

export interface Affiliate {
	identifier: string;
	legalName: string;
	streetAddress:  string;
	postalCode: string;
	addressLocality: string;
	addressCountry: string;
	email: string;
	latitude: number;
	longitude: number;
	tags: string[];
	telephone: string;
}

export interface ApplicationData {
	// Injected.
	affiliates: Affiliate[];
	googleMapsApiKey: string;
	language: string;
	// Computed.
	getLabelFor: LabelGetter;
}

export type AffiliateGroups = {
	[geoIdentifier: string]: Affiliate[]
};

export type Country = {
	name: string;
	affiliates: Affiliate[];
	affiliateGroups: AffiliateGroups;
	count: number;
}

export type Countries = {
	[key: string]: Country;
}

export type ActiveState = {
	country: Country|null;
	geoIdentifier: string|null;
}

export type ActiveStateAction =
| {type: "selectCountry", country: Country,}
| {type: "selectGeoIdentifier", country: Country, geoIdentifier: string,}
| {type: "unselectGeoIdentifier",}
| {type: "reset",};
