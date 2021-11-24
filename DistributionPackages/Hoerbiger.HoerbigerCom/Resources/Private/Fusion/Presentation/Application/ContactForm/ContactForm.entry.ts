import * as React from "react";
import * as ReactDom from "react-dom";

import {Client, Definitions} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const applicationData = JSON.parse($node.querySelector("[data-select=applicationData]")?.textContent ?? "{}");
		const $applicationRoot = $node.querySelector("[data-select=applicationRoot]");
		const $successContent = $node.querySelector("[data-select=successContent]");
		const $failContent = $node.querySelector("[data-select=failContent]");

		if (
			$applicationRoot instanceof HTMLElement
			&& $successContent instanceof HTMLElement
			&& $failContent instanceof HTMLElement
		) {
			const onSuccess = () => {
				$successContent.classList.remove("hidden");
				$applicationRoot.classList.add("hidden");
			};

			const onFail = () => {
				$failContent.classList.remove("hidden");
				$applicationRoot.classList.add("hidden");
			};

			ReactDom.render(
				React.createElement(
					Client,
					{
						...(applicationData as Definitions.ApplicationData),
						onSuccess,
						onFail,
					}
				),
				$applicationRoot
			);
		}
	}
};
