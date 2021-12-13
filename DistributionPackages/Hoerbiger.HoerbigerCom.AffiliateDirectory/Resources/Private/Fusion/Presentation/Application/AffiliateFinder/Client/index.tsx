import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {ApplicationData} from "./Definitions";

export const Client = (props: ApplicationData): JSX.Element => {
	const isExtendedMapView = useMediaQuery("(min-width: 768px)");

	return isExtendedMapView
		? (
			<div>
				MAP GOES HERE
			</div>
		)
		: (
			<div>MOBILE GOES HERE</div>
		);
};
