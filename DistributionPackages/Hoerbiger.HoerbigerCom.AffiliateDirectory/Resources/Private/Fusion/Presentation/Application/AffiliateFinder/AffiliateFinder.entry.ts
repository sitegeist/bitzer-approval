import * as React from "react";
import * as ReactDom from "react-dom";

import {Client} from "./Client";
import {ApplicationData} from "./Client/Definitions";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const {labels, ...applicationData} = JSON.parse($node.querySelector("script[data-select=applicationData]")?.textContent ?? "null");

		if (applicationData) {
			ReactDom.render(
				React.createElement(
					Client,
					{
						...applicationData,
						getLabelFor: (key: string, fallback: string): string =>
							labels[key] ?? fallback ?? key,
					} as ApplicationData
				),
				$node
			);
		}
	}
};
