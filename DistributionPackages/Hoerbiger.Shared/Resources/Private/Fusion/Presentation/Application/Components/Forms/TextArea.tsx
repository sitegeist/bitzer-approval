import * as React from "react";
import {Field, FieldRenderProps} from "react-final-form";

import {Error, Label} from "./";

type TextAreaProps = {
	label: React.ReactNode;
	placeholder?: string;
	name: string;
	initialValue?: string;
	validate?: (val: any) => string|undefined;
};
export const TextArea = (props: TextAreaProps): JSX.Element => (
	<Field
		name={props.name}
		initialValue={props.initialValue}
		validate={props.validate}
	>
		{({input, meta}: FieldRenderProps<string>) => (
			<div className="w-full grid grid-rows-form-field relative z-form-field">
				<Label label={props.label} isForRequired={Boolean(props.validate)}/>
				<textarea
					className="w-full border bg-light border-brand rounded-ch-xs p-ch focus:outline-brand min-h-touch"
					{...input}
					placeholder={props.placeholder}
				/>
				<Error {...meta}/>
				{(meta.error || meta.submitError) && meta.touched && (
					<span className="absolute top-full left-0 text-brand uppercase px-ch font-sans-bold">
						{meta.error || meta.submitError}
					</span>
				)}
			</div>
		)}
	</Field>
);
