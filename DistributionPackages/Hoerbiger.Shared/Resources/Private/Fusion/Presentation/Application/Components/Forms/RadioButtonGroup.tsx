import * as React from "react";
import cx from "classnames";

import {Field, FieldInputProps, FieldRenderProps} from "react-final-form";
import {CheckIndicator} from "./CheckIndicator";

type RadioButtonGroupProps = {
	name: string;
	options: {
		key: string;
		value?: string;
	}[];
	initialValue?: string;
	onChange?: (value: string) => void;
	className?: string;
};
export const RadioButtonGroup = (props: RadioButtonGroupProps): JSX.Element => {
	const onChange = (input: FieldInputProps<string, HTMLElement>, value: string) => {
		input.onChange(value);
		props.onChange && props.onChange(value);
	};

	return (
		<Field
			name={props.name}
			initialValue={props.initialValue}
		>
			{({input}: FieldRenderProps<string>) => (
				<div className={cx(
					"relative flex flex-wrap mt-minus-md ml-minus-md md:mt-minus-sm md:ml-minus-sm",
					props.className
				)}>
					{
						props.options.map(({key, value}) => (
							<div
								onClick={() => onChange(input, key)}
								key={key}
								className="flex items-center relative cursor-pointer mt-md ml-md md:mt-sm md:ml-sm"
							>
								<input
									type="radio"
									name={props.name}
									value={key}
									onChange={() => onChange(input, key)}
									checked={input.value === key}
									className="absolute left-[3px] top-[3px] opacity-0 z-0 peer"
								/>
								<CheckIndicator
									isChecked={input.value === key}
									className="peer-focus:drop-shadow-brand"
									look="radiobutton"
								/>
								<span className="ml-ch uppercase translate-y-[1px] text-sm font-sans-bold">{value ?? key}</span>
							</div>
						))
					}
				</div>
			)}
		</Field>
	);
};
