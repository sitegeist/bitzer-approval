import {RequestData} from "../Definitions";
import {FormFieldConfig} from "../Config/FormFields";

export const cleanupRequestData = (
	getFieldConfigFor: (key: string) => FormFieldConfig,
	formData: {[key: string]: any},
	language: string
): RequestData => {
	const requestData = Object.entries(formData).reduce((memo, [key, value]) => {
		const config = getFieldConfigFor(key);

		if (config.validate || (value && value !== "_")) {
			return {
				...memo,
				[key]: value
			};
		}
		return memo;
	}, {} as Partial<RequestData>);

	return {
		language,
		...requestData
	} as RequestData;
};
