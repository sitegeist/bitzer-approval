import * as React from "react";
import * as ReactDom from "react-dom";

import {Client} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $applicationRoot = $node.querySelector("[data-select=applicationRoot]");
		const targetUri = $node.dataset.targetUri ?? "";

		if ($applicationRoot instanceof HTMLElement && targetUri) {
			ReactDom.render(
				React.createElement(
					Client,
					{targetUri}
				),
				$applicationRoot
			);
		}
	}
};
