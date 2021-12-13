import * as React from "react";
import {Form} from "react-final-form";
import {Checkbox, Selectbox, Label} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";

import {LabelGetter, SearchRequestParams, TypeOption} from "../Definitions";

type FilterParams = {
	types: string[];
	typeOptions: TypeOption[];
	period: string|null;
	periodOptions: string[];
	onChange: (update: Partial<SearchRequestParams>) => void;
	getLabelFor: LabelGetter;
};
export const Filter = (props: FilterParams): JSX.Element => {
	const onChangeTypeValue = (identifier: string) => {
		if (props.types.includes(identifier)) {
			props.onChange({types: props.types.filter(type => type !== identifier)});
		} else {
			props.onChange({types: props.types.concat(identifier)});
		}
	};

	const onChangePeriodValue = (identifier: string) =>
		props.onChange({period: identifier});

	const setValuesExternal = React.useRef<any>(null);

	React.useEffect(() => {
		setValuesExternal.current("period", props.period);
	}, [props.period]);

	React.useEffect(() => {
		props.typeOptions.forEach(({identifier}) => {
			setValuesExternal.current(`type_${identifier}`, props.types.includes(identifier));
		});
	}, [props.types]);

	return (
		<Form
			onSubmit={e => e.preventDefault()}
			mutators={{
				setValue: ([field, value], state, {changeValue}) => {
					changeValue(state, field, () => value);
				}
			}}
		>
			{({handleSubmit, form}) => {
				setValuesExternal.current = form.mutators.setValue;

				return (
					<form
						onSubmit={handleSubmit}
						className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md"
					>
						<Selectbox
							name="period"
							label={props.getLabelFor("label.periods")}
							options={[
								{
									key: "",
									value: props.getLabelFor("period.__allPeriods__")
								},
								...props.periodOptions.map(identifier => ({
									key: identifier,
									value: props.getLabelFor(`period.${identifier}`)
								}))
							]}
							onChange={onChangePeriodValue}
						/>
						<div className="lg:col-span-2 w-full grid grid-rows-form-field">
							<Label label={props.getLabelFor("label.types")} />
							<div className="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-3 gap-x-md gap-y-xs">
								{
									props.typeOptions.map(({identifier}) => (
										<Checkbox
											name={`type_${identifier}`}
											label={props.getLabelFor(`type.${identifier}.plural`)}
											key={identifier}
											onChange={() => onChangeTypeValue(identifier)}
										/>
									))
								}
							</div>
						</div>
					</form>
				);
			}}
		</Form>
	);
};
