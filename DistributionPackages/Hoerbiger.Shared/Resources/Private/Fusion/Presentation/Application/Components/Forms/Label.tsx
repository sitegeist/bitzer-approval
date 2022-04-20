import * as React from "react";

type LabelProps = {
	label: React.ReactNode;
	isForRequired?: boolean;
};
export const Label = (props: LabelProps): JSX.Element => (
	<label className="uppercase text-neutral-darker  text-sm flex items-center md:font-sans-bold leading-tight">
		{props.label}{props.isForRequired ? " *" : ""}
	</label>
);
