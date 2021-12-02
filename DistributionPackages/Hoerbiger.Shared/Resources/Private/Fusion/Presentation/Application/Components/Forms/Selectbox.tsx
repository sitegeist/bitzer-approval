import * as React from "react";
import cx from "classnames";
import {Field, FieldInputProps, FieldRenderProps} from "react-final-form";

import {Error, Label} from "./";
import {StampedeIcon} from "../";
import {SelectboxOption} from "../../Definitions";
import {Hooks} from "../../Utils";

type SelectboxProps = {
	name: string;
	label: string;
	placeholder?: string;
	options?: SelectboxOption[];
	initialValue?: string;
	validate?: (value: any) => string|undefined;
};
export const Selectbox = (props: SelectboxProps): JSX.Element => {
	const [isOpen, setIsOpen] = React.useState(false);

	const toggle = () => setIsOpen(state => !state);
	const close = () => setIsOpen(false);

	const refNode: React.RefObject<HTMLDivElement> = Hooks.useHandlerOnOuterClick(close);
	const onSelect = (input: FieldInputProps<string, HTMLElement>, key: string) => () => {
		input.onChange(key);
		close();
	};

	const options = [
		!props.validate && props.placeholder && {key: "", value: props.placeholder},
		...(props.options ?? [])
	].filter(Boolean) as SelectboxOption[];

	return (
		<Field
			name={props.name}
			initialValue={props.initialValue}
			validate={props.validate}
		>
			{({input, meta}: FieldRenderProps<string>) => (
				<div
					ref={refNode}
					className={cx(
						"w-full grid grid-rows-form-field relative z-form-field",
						isOpen && "z-form-field-elevated"
					)}
				>
					<Label label={props.label} isForRequired={Boolean(props.validate)}/>
					<div
						onClick={toggle}
						className={cx(
							"max-h-touch min-h-touch border border-brand bg-light rounded-ch-xs px-ch focus:outline-brand",
							"flex items-center justify-between cursor-pointer",
						)}
					>
						<div className="">
							<span className={cx(
								input.value === "" && "text-neutral-darker",
							)}>
								{
									(() => options
										.find(({key}) => key === input.value)
										?.value
									)()
										?? props.placeholder
										?? ""
								}
							</span>
						</div>
						<StampedeIcon
							iconName="chevronDown"
							spin={isOpen}
						/>
					</div>
					<Error {...meta}/>
					{
						isOpen && (
							<div className={cx(
								"bg-light absolute top-full left-0 right-0 border border-brand rounded-b-ch-xs border-t-0 pt-2 -translate-y-2",
								"overflow-y-auto max-h-[300px]"
							)}>
								{
									options.map(({key, value}) => (
										<div
											key={key}
											onClick={onSelect(input, key)}
											className={cx(
												"min-h-touch flex items-center px-ch hover:bg-brand/20 cursor-pointer",
												key === "" && "text-neutral-darker",
												input.value === "" && key === "" && "hidden",
											)}
										>
											{value ?? key}
										</div>
									))
								}
							</div>
						)
					}
				</div>
			)}
		</Field>
	);
};
