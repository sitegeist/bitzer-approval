import * as React from "react";

import {Labels, SearchRequestParams} from "../Definitions";

type FilterParams = {
	requestParams: SearchRequestParams;
	onChange: (update: Partial<SearchRequestParams>) => void;
	availableOptions: {
		sortingProperties: string[];
		periods: string[];
		types: string[];
	};
	labels: Labels;
};
export const Filter = (props: FilterParams): JSX.Element => {
	console.log(props.availableOptions);
	const onChangeInput = (key: string) => (e: React.ChangeEvent<HTMLInputElement>) => {
		console.log({[key]: e.currentTarget.value});
		props.onChange({[key]: e.currentTarget.value});
	};

	return (
		<div>
			<input
				type="text"
				onChange={onChangeInput("searchTerm")}
				value={props.requestParams.searchTerm}
				placeholder={"TL: SearchTerm"}
			/>
			{
				props.availableOptions.types.length > 1 && (
					<div>
						{
							props.availableOptions.types.map(identifier => (
								<label key={identifier}>
									{identifier}
									<input
										type="radio"
										name="types"
										value={identifier}
										onChange={onChangeInput("types")}
									/>
								</label>
							))
						}
					</div>
				)
			}
		</div>
	);
};
