export * as Definitions from "./Definitions";

import * as React from "react";
import {Form} from "react-final-form";
import createDecorator from "final-form-focus";
import cx from "classnames";

import {ApplicationData, RequestData} from "./Definitions";
import {initFormFields} from "./Config/FormFields";
import {Selectbox, TextInput, TextArea} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";

const focusOnErrors = createDecorator();

export const Client = (props: ApplicationData): JSX.Element => {
	const getFieldConfigFor = initFormFields(props.labels, props.options);

	const onSubmit = (requestData: any) => {
		console.log("submit", requestData);

		if (requestData.salutation === "_") {
			const {salutation: _, ...rest} = requestData;
			requestData = rest;
		}

		console.log("submit cleaned", requestData);
	};


	return (
		<Form
			decorators={[focusOnErrors]}
			onSubmit={onSubmit}
		>
			{({handleSubmit}) => (
				<form
					className=""
					onSubmit={handleSubmit}
				>
					<div className="w-full text-sm text-neutral-darker text-right mb-xs">
						{props.labels["form.content.fillRequiredFields"] ?? ""}
					</div>
					<div className={cx("w-full grid grid-cols-1 md:grid-cols-2 gap-lg sm:gap-md md:gap-sm")}>
						<Selectbox {...getFieldConfigFor("customerGroup")} />
						<Selectbox {...getFieldConfigFor("applicationWorld")} />
						<Selectbox {...getFieldConfigFor("salutation")} />
						<TextInput {...getFieldConfigFor("givenName")} />
						<TextInput {...getFieldConfigFor("familyName")} />
						<TextInput {...getFieldConfigFor("emailAddress")} />
						<TextInput {...getFieldConfigFor("worksFor")} />
						<TextInput {...getFieldConfigFor("telephone")} />
						<TextInput {...getFieldConfigFor("streetAddress")} />
						<Selectbox {...getFieldConfigFor("addressCountry")} />

						<div className={cx(
							"grid grid-cols-1 gap-sm col-start-1",
							"sm:grid-cols-2-max-1fr",
							"md:col-span-2",
							"lg:col-span-1",
						)}>
							<div className="md:max-w-[30%]">
								<TextInput {...getFieldConfigFor("postalCode")} />
							</div>
							<TextInput {...getFieldConfigFor("addressLocality")} />
						</div>



						<div className="md:col-start-1 md:col-span-2">
							<TextArea {...getFieldConfigFor("message")} />
						</div>
					</div>
					<div>
						<button type="submit">TL:ABSENDEN</button>
					</div>
				</form>
			)}
		</Form>
	);
};
