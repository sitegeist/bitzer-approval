export interface Labels {
	[key: string]: string;
}

export interface FormOptions {
	salutations: string[];
	addressCountries: string[];
	applicationWorlds: string[];
	customerGroups: string[];
}

export interface ApplicationData {
	csrfProtectionToken: string;
	language: string;
	targetUri: string;
	labels: Labels;
	options: FormOptions;
	onSuccess: () => void;
	onFail: () => void;
}

export interface RequestData {
	language: string;
	salutation?: string;
	customerGroup?: string;
	applicationWorld?: string;
	givenName: string;
	familyName: string;
	emailAddress: string;
	worksFor?: string;
	telephone?: string;
	streetAddress?: string;
	postalCode?: string;
	addressLocality?: string;
	addressCountry?: string;
	message: string;
}
