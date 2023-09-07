import * as React from "react";
import * as ReactDom from "react-dom";

import {PaybackCalculatorEVCP} from "./Client";
import {ApplicationData, CalculationFields, InputFields} from "./Client/Defintions";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const applicationRoot = $node.querySelector("[data-select=applicationRoot]") as HTMLElement;
		const applicationData = JSON.parse($node.dataset.applicationData ?? "{}");

		ReactDom.render(
			React.createElement(
				PaybackCalculatorEVCP,
				{
					locale: "en-US",
					applicationRoot,
					inputFields: {...applicationData.inputFields} as InputFields,
					calculationFields: {...applicationData.calculationFields} as CalculationFields,
					getLabelFor: (key: string, fallback: string): string =>
						applicationData.labels[key] ?? fallback ?? key,
				} as ApplicationData
			),
			applicationRoot
		);
	}
};
