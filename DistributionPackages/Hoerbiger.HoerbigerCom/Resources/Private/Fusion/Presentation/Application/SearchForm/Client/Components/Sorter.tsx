import * as React from "react";
import {Form} from "react-final-form";
import {Label, RadioButtonGroup} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";

import {LabelGetter, SearchRequestParams} from "../Definitions";

type SorterParams = {
	sortingProperty: string;
	sortingProperties: string[];
	onChange: (update: Partial<SearchRequestParams>) => void;
	getLabelFor: LabelGetter;
};
export const Sorter = (props: SorterParams): JSX.Element => (
	<Form onSubmit={props.onChange}>
		{({handleSubmit}) => (
			<form
				onSubmit={handleSubmit}
				className="flex flex-col md:flex-row md:items-center space-y-sm md:space-x-ch md:space-y-0"
			>
				<Label label={props.getLabelFor("label.sorting")}/>
				<div>
					<RadioButtonGroup
						name="sortingProperty"
						options={props.sortingProperties.map(identifier => ({
							key: identifier,
							value: props.getLabelFor(`sortingProperty.${identifier}`)
						}))}
						initialValue={props.sortingProperty}
						onChange={(sortingProperty: string) => props.onChange({sortingProperty})}
					/>
				</div>
			</form>
		)}
	</Form>
);
