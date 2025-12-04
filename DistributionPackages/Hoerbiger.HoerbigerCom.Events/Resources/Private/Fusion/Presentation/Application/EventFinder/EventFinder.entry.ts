import * as React from "react";
import * as ReactDOM from "react-dom";
import {ApplicationData} from "./Client/Definitions";
import {EventFinderClient} from "./Client";

export default (el: HTMLElement): void => {
	if (el instanceof HTMLElement) {
		const applicationData = JSON.parse(el.dataset.applicationData ?? "{}");

		if (applicationData.locale && applicationData.endpointUri) {
			ReactDOM.render(
				React.createElement(
					EventFinderClient,
					{
						...applicationData,
						getLabelFor: (key: string, fallback: string): string =>
							applicationData.labels[key] ?? fallback ?? key,
					} as ApplicationData
				),
				el
			);
		}
	}
};
