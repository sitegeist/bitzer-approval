import * as React from "react";
import cx from "classnames";

import {InputRowParams, LabelGetter} from "../Defintions";

export const InputRow = (props: InputRowParams): JSX.Element => {
	const onChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		const value = e.currentTarget.value;
		props.onChange(value, props.inputName);
	};

	return (
		<div
			className={cx(
				"grid grid-cols-2 items-center min-h-touch",
				"border-b-[1px] border-light-contrast"
			)}
		>
			<div
				className={cx(
					props.errorMessage && "text-[red]"
				)}
			>
				{props.label}
			</div>
			<div
				className={cx(
					"flex flex-col",
					props.errorMessage && "gap-[5px] pt-[5px] pb-[5px]"
				)}
			>
				<div className="flex items-center">
					<div className="">
						<input
							name={props.inputName}
							type="number"
							value={props.inputValue ?? 0}
							className="bg-light focus:outline-brand w-[72px] text-right"
							step={props.step}
							min={props.min}
							max={props.max}
							onChange={onChange}
						/>
					</div>
					<span>
						{props.unit}
					</span>
					(--)
				</div>
				{
					props.errorMessage &&
						<div className="text-[red]">
							{props.errorMessage}
						</div>
				}
			</div>
		</div>
	);
};
