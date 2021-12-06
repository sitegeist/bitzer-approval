import {RequestData} from "../Definitions";
import {NULLABLE_VALUES} from "../Config/FormFields";

export const cleanupRequestData = (
	formData: {[key: string]: any},
	language: string
): RequestData => {
	const requestData = Object.entries(formData).reduce((memo, [key, value]) => {
		if (value && !NULLABLE_VALUES.includes(value)) {
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
