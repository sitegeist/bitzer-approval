import {SelectboxOption} from "@hoerbiger/shared/Resources/Private/Fusion/Presentation/Application/Definitions";
import {Labels, FormOptions} from "../Definitions";

export type FormFieldConfig = {
	name: string;
	label: string;
	placeholder?: string;
	options?: SelectboxOption[];
	initialValue?: string;
	validate?: (val: string) => string|undefined;
};

const REGEXP_EMAIL = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
const required = (error: string, pattern?: RegExp) => (val: string) =>
	val && (!pattern || pattern.test(val))
		? undefined
		: error;

const NULLABLE_SELECT_OPTION = "_nullable_";
export const NULLABLE_VALUES = [NULLABLE_SELECT_OPTION];

export const initFormFields = (labels: Labels, formOptions: FormOptions): (fieldName: string) => FormFieldConfig => {
	const getLabelFor = (key: string) => {
		let label = labels[key];

		if (!label) {
			console.warn(`Missing label ${key}`);
			label = key;
		}

		return label;
	};

	const labelFor = (key: string) => getLabelFor(`form.label.${key}`);
	const placeholderFor = (key: string) => getLabelFor(`form.placeholder.${key}`);
	const errorFor = (key: string) => getLabelFor(`form.error.${key}`);

	return (fieldName: string) => {
		const FIELDS: {[fieldName: string]: () => FormFieldConfig} = {
			givenName: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				validate: required(errorFor(fieldName)),
			}),
			familyName: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				validate: required(errorFor(fieldName)),
			}),
			emailAddress: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				validate: required(errorFor(fieldName), REGEXP_EMAIL),
			}),
			worksFor: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
			}),
			telephone: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
			}),
			streetAddress: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
			}),
			postalCode: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
			}),
			addressLocality: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
			}),
			message: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				validate: required(errorFor(fieldName)),
			}),
			salutation: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				options: [
					...formOptions.salutations.map(key => ({
						key,
						value: getLabelFor(`${fieldName}.${key}`),
					})),
					{
						key: NULLABLE_SELECT_OPTION,
						value: getLabelFor(`${fieldName}.none`),
					},
				],
				initialValue: formOptions.salutations[0],
			}),
			customerGroup: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				options: formOptions.customerGroups.map(key => ({
					key,
					value: getLabelFor(`${fieldName}.${key}`),
				})).concat({
					key: NULLABLE_SELECT_OPTION,
					value: getLabelFor(`form.dontknowoption.${fieldName}`)
				}),
				validate: required(errorFor(fieldName)),
				initialValue: "",
			}),
			applicationWorld: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				placeholder: placeholderFor(fieldName),
				options: formOptions.applicationWorlds.map(key => ({
					key,
					value: getLabelFor(`${fieldName}.${key}`),
				})).concat({
					key: NULLABLE_SELECT_OPTION,
					value: getLabelFor(`form.dontknowoption.${fieldName}`)
				}),
				validate: required(errorFor(fieldName)),
				initialValue: "",
			}),
			addressCountry: () => ({
				name: fieldName,
				label: labelFor(fieldName),
				options: formOptions.addressCountries.map(key => ({
					key,
					value: getLabelFor(`${fieldName}.${key}`),
				})),
				initialValue: formOptions.addressCountries[0] ?? "",
				validate: required(errorFor(fieldName)),
			}),
		};

		try {
			return FIELDS[fieldName]() as FormFieldConfig;
		} catch (e) {
			console.error(e);
			throw new Error(`Didn't find a form field config for ${fieldName}.`);
		}
	};
};
