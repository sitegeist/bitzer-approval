import * as React from "react";
import * as ReactFinalForm from "react-final-form";

import {EditorEnvelope} from "@neos-project/neos-ui-editors";

const KNOWN_RATIOS = {
	ratio_16_9: "16:9",
	ratio_16_10: "16:10",
	ratio_4_3: "4:3"
};

export function* validator(videoConfig: any) {
	if (!videoConfig.platformIdentifier) {
		yield {
			field: "platformIdentifier",
			message: "PlatformIdentifier is required"
		};
	}

	if (!videoConfig.platformIdentifier) {
		yield {
			field: "identifier",
			message: "Identifier is required"
		};
	}

	if (!videoConfig.aspectRatio) {
		yield {
			field: "aspectRatio",
			message: "AspectRatio is required"
		};
	}
}

export const Preview: React.FC<{
	value: any,
	api: any
}> = props => {
	const {IconCard} = props.api;

	const labelAspectRatio = {
		ratio_16_9: "16:9",
		ratio_16_10: "16:10",
		ratio_4_3: "4:3",
	}[props.value?.aspectRatio as string | undefined ?? ""] ?? "";

	return (
		<IconCard
			icon="play"
			title={props.value.platformIdentifier}
			subTitle={`Format: ${labelAspectRatio}, ID: ${props.value.identifier}`}
		/>
	);
};

type VideoConfig = {
	platformIdentifier: "YouTube" | "Vimeo";
	identifier: string;
	aspectRatio: "ratio_16_9" | "ratio_16_10" | "ratio_4_3";
};

const parseUrl = async (input: string) => {

	let videoConfig: Partial<VideoConfig> = {};
	const inputWithEnforcedProtocol = ("https://" + input).replace(/^https:\/\/http(s)*:\/\//g, "https://");
	const url = new URL(inputWithEnforcedProtocol);

	switch (true) {
	case url instanceof URL && /youtube.com/.test(url.hostname) && /shorts/.test(url.pathname):
		videoConfig.platformIdentifier = "YouTube";
		videoConfig.identifier = url.pathname.split('shorts/')[1] || "";
		break;

	case url instanceof URL && /youtube-nocookie.com/.test(url.hostname) && /embed/.test(url.pathname):
		videoConfig.platformIdentifier = "YouTube";
		videoConfig.identifier = url.pathname.split('embed/')[1] || "";
		break;

	case url instanceof URL && /youtube.com/.test(url.hostname):
		videoConfig.platformIdentifier = "YouTube";

		if (url.searchParams.get("v")) {
			videoConfig.identifier = url.searchParams.get("v") ?? "";
		}
		break;

	case url instanceof URL && /youtu.be/.test(url.hostname):
		videoConfig.platformIdentifier = "YouTube";

		videoConfig.identifier = (url.pathname ?? "/").slice(1);
		break;

	case url instanceof URL && /vimeo.com/.test(url.hostname):
		videoConfig.platformIdentifier = "Vimeo";

		videoConfig.identifier = (url.pathname ?? "/").slice(1);
		break;
	default:
		break;
	}

	return videoConfig;
};

export const Form: React.FC<{
	api: any
}> = props => {
	const {Field, Layout, ReactFinalForm: {useForm, useFormState}} = props.api;
	const {change} = useForm();
	const {initialValues} = useFormState();

	const [checkInput, setCheckInput] = React.useState("");
	const [videoConfig, setVideoConfig] = React.useState<Partial<VideoConfig> | null>(null);

	React.useEffect(() => {
		setVideoConfig(initialValues);
	}, [initialValues]);

	React.useEffect(() => {
		let isActive = true;

		if (checkInput?.length) {
			(async () => {
				const videoConfig = await parseUrl(checkInput);
				if (isActive) {
					setVideoConfig(videoConfig);
				}
			})();
		} else {
			setVideoConfig(null);
		}

		return () => {
			isActive = false;
		};
	}, [checkInput]);

	React.useEffect(() => {
		change("platformIdentifier", videoConfig?.platformIdentifier ?? "");
		change("identifier", videoConfig?.identifier ?? "");
	}, [videoConfig]);

	return (
		<Layout.Stack>
			<ReactFinalForm.Form onSubmit={() => {
			}}>
				{(props) => (
					<form
						className="sg-ig-space-y-4"
						style={{maxWidth: "90vw", minWidth: "550px"}}
						onSubmit={props.handleSubmit}
					>
						<div className="sg-ig-space-y-4">
							<ReactFinalForm.Field name="checkInput">
								{({input, meta}: { input: any, meta: any }) => (
									<div className="fix-neos-ui-validation-messages">
										<EditorEnvelope
											identifier={`Sitegeist-InspectorGadget-${input.name}`}
											label="Video-URL (einfach aus dem Browser kopieren)"
											editor="Neos.Neos/Inspector/Editors/TextFieldEditor"
											validationErrors={meta.error ? [meta.error] : []}
											value={checkInput}
											commit={setCheckInput}
										/>
									</div>
								)}
							</ReactFinalForm.Field>
						</div>
					</form>
				)}
			</ReactFinalForm.Form>

			<div className="sg-ig-space-y-4">
				{
					videoConfig?.platformIdentifier && videoConfig?.identifier?.length
						? (
							<>
								<b>Aktuelles/Erkanntes Video:</b>
								<Layout.Columns columns={2}>
									<span>Plattform:</span>
									<span>{videoConfig?.platformIdentifier}</span>
									<span>Video-ID:</span>
									<span>{videoConfig?.identifier}</span>
								</Layout.Columns>
							</>
						)
						: checkInput.length > 0 && (
						<>
							<b>Diese URL ist uns unbekannt und wir können keine Video-Parameter erkennen.</b>
							<div>Versuchen Sie eines der folgenden Schemata:</div>
							<ul>
								<li>www.youtube.com/?v=123</li>
								<li>www.youtube.com/shorts/123</li>
								<li>www.youtube-nocookie.com/embed/123?rel=0</li>
								<li>youtu.be/123</li>
								<li>vimeo.com/123</li>
							</ul>
						</>
					)
				}
			</div>

			<Layout.Columns columns={2}>
				<Field
					name="aspectRatio"
					label="Aspect Ratio"
					editor="Neos.Neos/Inspector/Editors/SelectBoxEditor"
					defaultValue="ratio_16_9"
					editorOptions={{
						values: Object.entries(KNOWN_RATIOS).reduce((memo, [key, label]) => ({
							...memo,
							[key]: {label}
						}), {}),
					}}
				/>
			</Layout.Columns>
		</Layout.Stack>
	);
};
