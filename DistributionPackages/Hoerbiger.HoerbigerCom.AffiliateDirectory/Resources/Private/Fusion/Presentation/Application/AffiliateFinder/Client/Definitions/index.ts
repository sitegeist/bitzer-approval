export type LabelGetter = (key: string, fallback?: string) => string;

export type Labels = {
	[key: string]: string
}

export interface Affiliate {
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
	affiliates: Affiliate[];
	googleMapsApiKey: string;
	getLabelFor: LabelGetter;
}

