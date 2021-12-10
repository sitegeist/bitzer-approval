
import * as React from "react";
import cx from "classnames";

type CheckIndicatorProps = {
	isChecked: boolean;
	look?: "checkbox"|"radiobutton";
	className?: string;
}
export const CheckIndicator = (props: CheckIndicatorProps): JSX.Element => (
	<div className={(cx(
		"relative w-[20px] h-[20px]",
		"bg-light border border-brand",
		props.look === "checkbox" && "rounded-[2px]",
		props.look === "radiobutton" && "rounded-full",
		props.className,
	))}>
		<span className={cx(
			"absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-brand transition-all",
			props.look === "checkbox" && "rounded-[2px]",
			props.look === "radiobutton" && "rounded-full",
			props.isChecked ? "w-[70%] h-[70%]" : "w-0 h-0"
		)}></span>
	</div>
);

CheckIndicator.defaultProps = {
	look: "checkbox",
};
