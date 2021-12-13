import * as React from "react";
import {Field, FieldInputProps, FieldRenderProps} from "react-final-form";

import {CheckIndicator} from "./CheckIndicator";

type CheckboxProps = {
	name: string;
	label: React.ReactNode;
	initialValue?: boolean;
	onChange?: (value: boolean) => void;
};
export const Checkbox = (props: CheckboxProps): JSX.Element => {
	const onChange = (input: FieldInputProps<boolean, HTMLElement>) => {
		input.onChange(!input.value);
		props.onChange && props.onChange(!input.value);
	};

	return (
		<Field
			name={props.name}
			initialValue={props.initialValue}
		>
			{({input}: FieldRenderProps<boolean>) => (
				<div
					onClick={() => onChange(input)}
					className="flex items-center relative cursor-pointer"
				>
					<input
						type="checkbox"
						checked={input.value}
						onChange={() => onChange(input)}
						className="absolute left-[3px] top-[3px] opacity-0 z-0 peer"
					/>
					<CheckIndicator
						isChecked={input.value}
						className="peer-focus:drop-shadow-brand"
					/>
					<span className="ml-ch uppercase translate-y-[2px] text-sm font-sans-bold">{props.label}</span>
				</div>
			)}
		</Field>
	);
};
