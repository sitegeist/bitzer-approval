import * as React from "react";
import cx from "classnames";

type StampedeIconProps = {
	collectionName?: string;
	iconName: string;
	className?: string;
	size?: number;
	spin?: boolean;
}
export const StampedeIcon = (props: StampedeIconProps): JSX.Element => {
	const url = `/stampede/svgsprite?collection=${props.collectionName}#${props.iconName}`;

	return (
		<svg
			version="1.1"
			xmlns="http://www.w3.org/2000/svg"
			xmlnsXlink="http://www.w3.org/1999/xlink"
			className={cx(
				"fill-current",
				props.size === 1 && "w-[1em] h-[1em]",
				props.size === 2 && "w-[2em] h-[2em]",
				props.size === 3 && "w-[3em] h-[3em]",
				props.size === 4 && "w-[4em] h-[4em]",
				props.spin && "rotate-180 origin-center transition-transform",
				props.className
			)}
		>
			<use xlinkHref={url} href={url}/>
		</svg>
	);
};

StampedeIcon.defaultProps = {
	collectionName: "shared",
	size: 1,
};
