type MostWandedProps = {
	api: string;
	language: string;
	csrfProtectionToken: string;
	nodeAggregateIdentifier: string;
}

export const mostWantedApi = ({api, language, csrfProtectionToken, nodeAggregateIdentifier}: MostWandedProps): Promise<any> =>
	// Fire and forget.
	fetch(
		api,
		{
			method: "POST",
			credentials: "include",
			headers: {
				"Content-Type": "application/json",
				"X-CSRF-TOKEN": csrfProtectionToken,
			},
			body: JSON.stringify({
				language,
				nodeAggregateIdentifier
			}),
		});
