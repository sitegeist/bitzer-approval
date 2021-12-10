import * as React from "react";
import {LabelGetter} from "../Definitions";

type SummaryProps = {
	countResults: number;
	getLabelFor: LabelGetter;
};
export const Summary = (props: SummaryProps): JSX.Element => {
	return (
		<span>
			{
				props.countResults > 1
					? props.getLabelFor("label.summary.hasResults").replace("###", props.countResults.toString())
					: props.getLabelFor("label.summary.noResults")
			}
		</span>
	);
};
