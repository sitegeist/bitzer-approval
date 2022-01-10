import * as React from "react";
import {Form} from "react-final-form";

import {Selectbox} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";
import {Countries, Country, LabelGetter } from "../Definitions";

type CountryFormProps = {
	onSubmit: (formData: any) => void;
	countries: Countries;
	currentCountry: Country|null;
	getLabelFor: LabelGetter;
};
export const CountryForm = ({onSubmit, countries, currentCountry, getLabelFor}: CountryFormProps): JSX.Element => {
	const formChange = React.useRef<any>();

	React.useEffect(() => {
		console.log(currentCountry);
		if (formChange.current && currentCountry?.name) {
			formChange.current?.call(null, "activeCountry", currentCountry?.name);
		}

	}, [currentCountry]);

	return (
		<Form
			onSubmit={onSubmit}
		>
			{({handleSubmit, form: {change}}) => {
				formChange.current = change;

				return (
					<form
						onSubmit={handleSubmit}
						className="flex justify-center"
					>
						<div className="w-full max-w-[400px]">
							<Selectbox
								name="activeCountry"
								label={getLabelFor("label.addressCountry")}
								initialValue={currentCountry?.name ?? ""}
								options={[
									{key: "", value: getLabelFor("input.activeCountry.all")},
									...Object.keys(countries).map(key => ({key}))
								]}
								onChange={() => handleSubmit()}
							/>
						</div>
					</form>
				);
			}}
		</Form>
	);
};
