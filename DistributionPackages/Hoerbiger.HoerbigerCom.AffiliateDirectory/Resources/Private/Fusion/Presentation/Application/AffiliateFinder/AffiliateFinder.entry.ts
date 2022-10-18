import * as React from "react";
import * as ReactDom from "react-dom";

import {Client} from "./Client";
import {ApplicationData} from "./Client/Definitions";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const {labels, ...applicationData} = JSON.parse($node.querySelector("script[data-select=applicationData]")?.textContent ?? "null");

		if (applicationData) {
			const head = document.getElementsByTagName("head")[0];
			const insertBefore = head.insertBefore;
			if (head instanceof HTMLHeadElement) {
				// eslint-disable-next-line @typescript-eslint/ban-ts-comment
				// @ts-ignore
				head.insertBefore = function (newElement, referenceElement) {
					if (newElement instanceof HTMLLinkElement) {
						if (newElement.href && newElement.href.indexOf("//fonts.googleapis.com/css?family=") > -1) {
							return;
						}
					}
					insertBefore.call(head, newElement, referenceElement);
				};
			}

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
