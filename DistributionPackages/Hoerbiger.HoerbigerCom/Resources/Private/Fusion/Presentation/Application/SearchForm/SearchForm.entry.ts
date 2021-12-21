import * as React from "react";
import * as ReactDom from "react-dom";

import {Client, ApplicationData} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $applicationRoot = $node.querySelector("[data-select=applicationRoot]");
		const {labels, ...applicationData} = JSON.parse($node.querySelector("script[data-select=applicationData]")?.textContent ?? "{}");

		if ($applicationRoot instanceof HTMLElement) {
			ReactDom.render(
				React.createElement(
					Client,
					{
						...applicationData,
						getLabelFor: (key: string, fallback: string): string =>
							labels[key] ?? fallback ?? key,
						searchTerm: new URL(window.location.href).searchParams.get("searchTerm") ?? ""
					} as ApplicationData
				),
				$applicationRoot
			);
		}
	}
};

