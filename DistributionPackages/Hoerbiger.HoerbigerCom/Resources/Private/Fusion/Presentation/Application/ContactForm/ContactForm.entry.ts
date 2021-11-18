import * as React from "react";
import * as ReactDom from "react-dom";

import {Client, Definitions} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const applicationData = JSON.parse($node.querySelector("[data-select=applicationData]")?.textContent ?? "{}");

		console.log(applicationData);

		ReactDom.render(
			React.createElement(
				Client,
				applicationData as Definitions.ApplicationData
			),
			$node
		);
	}
};
