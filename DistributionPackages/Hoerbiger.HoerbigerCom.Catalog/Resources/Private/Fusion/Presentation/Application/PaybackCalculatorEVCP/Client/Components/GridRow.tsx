import * as React from "react";
import {GridRowParams} from "../Defintions";

export const GridRow = (props: GridRowParams) => {
	return (
		<>
			<div className="font-sans-bold">
				{props.label}
			</div>
			<div>
				{props.value}
			</div>
		</>
	);
};
