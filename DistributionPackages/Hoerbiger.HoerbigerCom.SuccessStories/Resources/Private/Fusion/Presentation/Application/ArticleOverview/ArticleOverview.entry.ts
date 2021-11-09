import * as React from "react";
import * as ReactDOM from "react-dom";

import {Client, Definitions} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement && $node.querySelector("script")) {
		const applicationData = JSON.parse($node.querySelector("script")?.textContent ?? "{}");

		ReactDOM.render(
			React.createElement(
				Client,
				applicationData as Definitions.ApplicationData
			),
			$node
		);
	}
};
