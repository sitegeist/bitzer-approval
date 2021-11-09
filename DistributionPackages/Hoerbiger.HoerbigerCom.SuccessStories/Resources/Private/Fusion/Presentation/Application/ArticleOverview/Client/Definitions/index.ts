export interface Labels {
	[key: string]: string;
}

export interface Article {
	datePublished: string;
	image: {
		src: string;
		alt: string;
		title: string;
	};
	headline: string;
	abstract: string;
	applicationWorld: string;
	url: string;
}

export interface ApplicationData {
	labels: Labels;
	articles: Article[];
}
