import * as React from "react";
import { FieldMetaState } from "react-final-form";

export const Error = (meta: FieldMetaState<string>): JSX.Element|null =>
	(meta.error || meta.submitError) && meta.touched
		? (
			<span className="absolute top-full left-0 text-[red] px-ch font-sans-bold">
				{meta.error || meta.submitError}
			</span>
		)
		: null;
