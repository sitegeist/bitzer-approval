import * as React from "react";
import { Labels, SearchRequestParams } from "../Definitions";

type FilterParams = {
	requestParams: SearchRequestParams;

};
export const Filter = (props: FilterParams): JSX.Element => {
	console.log(props);
	return (
		<div>
			FILTER HERE
		</div>
	);
};
