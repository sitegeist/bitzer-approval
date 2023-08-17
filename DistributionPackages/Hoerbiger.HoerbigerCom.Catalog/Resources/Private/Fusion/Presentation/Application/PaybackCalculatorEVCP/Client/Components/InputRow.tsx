import * as React from "react";
import cx from "classnames";

import {InputRowParams, LabelGetter} from "../Defintions";

export const InputRow = (props: InputRowParams): JSX.Element => {
	const onChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		const value = e.currentTarget.value;
		props.onChange(value, props.inputName);
	};

	return (
		<div className={cx(
			"grid grid-cols-2 items-center h-touch",
			"border-b-[1px] border-light-contrast"
		)}>
			<div>
				{props.label}
			</div>
			<div className="flex items-center">
				<div className="">
					<input
						name={props.inputName}
						type="number"
						value={props.inputValue ?? 0}
						className="bg-light focus:outline-brand w-[72px] text-right"
						onChange={onChange}
					/>
				</div>
				<span>
					{props.unit}
				</span>
				(--)
			</div>
		</div>
	);
};
