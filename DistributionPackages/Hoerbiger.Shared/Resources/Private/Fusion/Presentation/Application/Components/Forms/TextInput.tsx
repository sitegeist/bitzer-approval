import * as React from "react";
import {Field, FieldRenderProps} from "react-final-form";

import {Error, Label} from "./";

type TextInputProps = {
	label: React.ReactNode;
	placeholder?: string;
	name: string;
	initialValue?: string;
	validate?: (val: string) => string|undefined;
};
export const TextInput = (props: TextInputProps): JSX.Element => (
	<Field
		name={props.name}
		initialValue={props.initialValue}
		validate={props.validate}
	>
		{({input, meta}: FieldRenderProps<string>) => (
			<div className="w-full grid grid-rows-form-field relative z-form-field">
				<Label label={props.label} isForRequired={Boolean(props.validate)} />
				<input
					className="max-h-touch min-h-touch bg-light border border-brand rounded-ch-xs px-ch focus:outline-brand"
					{...input}
					placeholder={props.placeholder}
					type="text"
				/>
				<Error {...meta}/>
			</div>
		)}
	</Field>
);
