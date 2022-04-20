
import * as React from "react";
import cx from "classnames";
import { StampedeIcon } from "../StampedeIcon";

type CheckIndicatorProps = {
	isChecked: boolean;
	look?: "checkbox"|"radiobutton";
	className?: string;
}
export const CheckIndicator = (props: CheckIndicatorProps): JSX.Element => (
	<div className={(cx(
		"relative w-[20px] h-[20px]",
		"bg-light border border-brand flex-shrink-0",
		props.look === "checkbox" && "rounded-[2px]",
		props.look === "radiobutton" && "rounded-full",
		props.className,
	))}>
		{
			props.look === "checkbox"
				? (
					<span
						className={cx(
							"absolute inset-0 text-brand transition-all",
							props.isChecked ? "opacity-100" : "opacity-0"
						)}
					>
						<StampedeIcon iconName="check" size={100}/>
					</span>
				)
				: (
					<span className={cx(
						"absolute transition-all top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand",
						props.isChecked ? "w-[70%] h-[70%]" : "w-0 h-0",
					)}>
					</span>

				)
		}

	</div>
);

CheckIndicator.defaultProps = {
	look: "checkbox",
};
