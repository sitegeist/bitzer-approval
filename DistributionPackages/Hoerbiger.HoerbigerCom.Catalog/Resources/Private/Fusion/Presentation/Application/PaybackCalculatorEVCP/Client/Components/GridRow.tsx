import * as React from "react";
import {GridRowParams} from "../Defintions";
import {InfoIcon} from "./Icons";

export const GridRow = (props: GridRowParams) => {
	return (
		<>
			<div className="font-sans-bold">
				{props.label}
			</div>
			<div className="flex">
				<div>
					{props.value}
				</div>
				{
					props.tooltip &&
						<div>
							<InfoIcon />
						</div>
				}
			</div>
		</>
	);
};
