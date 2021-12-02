export * as Definitions from "./Definitions";

import * as React from "react";
import {Form} from "react-final-form";
import createDecorator from "final-form-focus";
import cx from "classnames";

import {ApplicationData} from "./Definitions";
import {initFormFields} from "./Config/FormFields";
import {Selectbox, TextInput, TextArea, Label} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Components/Forms";
import {cleanupRequestData} from "./Utils/cleanupRequestData";

const focusOnErrors = createDecorator();

export const Client = (props: ApplicationData): JSX.Element => {
	const [formState, setFormState] = React.useState<"USERINPUT"|"SUBMITTING"|"SUBMITTED">("USERINPUT");
	const getFieldConfigFor = initFormFields(props.labels, props.options);

	const onSubmit = (requestData: any) => {
		if (formState !== "SUBMITTING") {
			setFormState("SUBMITTING");
			(async () => {
				const {status} = await fetch(props.targetUri, {
					method: "POST",
					credentials: "include",
					headers: {
						"Content-Type": "application/json",
						"X-CSRF-TOKEN": props.csrfProtectionToken,
					},
					body: JSON.stringify(cleanupRequestData(requestData, props.language)),
				});

				if (status === 200) {
					props.onSuccess();
				} else {
					props.onFail();
				}

				setFormState("SUBMITTED");
			})();
		}
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
					<div className="mb-xs w-full flex justify-end">
						<Label label={props.labels["form.content.fillRequiredFields"] ?? ""}/>
					</div>
					<div className={cx("w-full grid grid-cols-1 md:grid-cols-2 gap-md md:gap-sm")}>
						<Selectbox {...getFieldConfigFor("customerGroup")} />
						<Selectbox {...getFieldConfigFor("applicationWorld")} />

						<Selectbox {...getFieldConfigFor("salutation")} />
						<div className="hidden md:block" />

						<TextInput {...getFieldConfigFor("givenName")} />
						<TextInput {...getFieldConfigFor("familyName")} />

						<TextInput {...getFieldConfigFor("emailAddress")} />
						<TextInput {...getFieldConfigFor("telephone")} />

						<TextInput {...getFieldConfigFor("worksFor")} />
						<TextInput {...getFieldConfigFor("streetAddress")} />

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

						<Selectbox {...getFieldConfigFor("addressCountry")} />

						<div className="md:col-start-1 md:col-span-2">
							<TextArea {...getFieldConfigFor("message")} />
						</div>
					</div>

					<button
						className="mt-lg md:mt-md px-8 rounded-full cursor-pointer whitespace-nowrap flex justify-center items-center space-x-[1em] min-h-touch max-h-touch bg-brand text-brand-contrast font-semibold"
						type="submit"
						disabled={formState !== "USERINPUT"}
					>
						{props.labels["form.submit"] ?? ""}
					</button>
				</form>
			)}
		</Form>
	);
};
