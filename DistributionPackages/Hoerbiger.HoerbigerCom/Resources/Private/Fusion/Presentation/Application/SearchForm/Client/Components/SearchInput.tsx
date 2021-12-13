import * as React from "react";

import {LabelGetter, SearchRequestParams} from "../Definitions";

type SearchInputParams = {
	searchTerm: string|null;
	onChange: (update: Partial<SearchRequestParams>) => void;
	getLabelFor: LabelGetter;
};
export const SearchInput = (props: SearchInputParams): JSX.Element => {
	const onChange = (e: React.ChangeEvent<HTMLInputElement>) =>
		props.onChange({"searchTerm": e.currentTarget.value});

	return (
		<div className="w-full grid grid-rows-form-field relative z-form-field">
			<input
				type="text"
				onChange={onChange}
				value={props.searchTerm ?? ""}
				placeholder={props.getLabelFor("placeholder.searchTerm")}
				className="max-h-touch min-h-touch bg-light border border-brand rounded-ch-xs px-ch focus:outline-brand"
			/>
		</div>
	);
};
