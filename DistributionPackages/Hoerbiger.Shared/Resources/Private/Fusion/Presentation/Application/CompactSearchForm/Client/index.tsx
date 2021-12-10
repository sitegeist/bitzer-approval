import * as React from "react";
import cx from "classnames";

import {StampedeIcon} from "../../Components";

type ClientProps = {
	targetUri: string;
}
export const Client = ({targetUri}: ClientProps): JSX.Element => {
	const [isExpanded, setIsExpandend] = React.useState(false);
	const isRedirecting = React.useRef(false);
	const [query, setQuery] = React.useState("");
	const close = () => setIsExpandend(false);
	const onSubmit = (e: React.FormEvent) => {
		e.preventDefault();

		if (query.length && !isRedirecting.current) {
			isRedirecting.current = true;

			const url = new URL(targetUri);
			url.searchParams.append("searchTerm", query);

			close();
			window.location.href = url.toString();
		}
	};

	return (
		<div
			onMouseLeave={close}
			className="w-full h-full relative"
		>
			<div
				onClick={() => setIsExpandend(state => !state)}
				className="text-neutral-contrast w-full h-full grid place-items-center z-10"
			>
				<StampedeIcon iconName="search" size={1.5}/>

			</div>
			{
				isExpanded && (
					<form
						onSubmit={onSubmit}
						className={cx(
							"absolute z-20 top-0 bottom-0 right-0 w-[400%]",
							"border-brand border-b-4",
							"grid grid-cols-4"
						)}
					>
						<div className="col-span-3 bg-light flex items-center">
							<input
								type="text"
								autoFocus
								value={query}
								onChange={(e: React.ChangeEvent<HTMLInputElement>) => setQuery(e.currentTarget.value)}
								className="border-none w-[80%] mx-auto focus:outline-none"
								placeholder="TL: Suchbegriff eingeben"
							/>
						</div>
						<div
							onClick={onSubmit}
							className="w-full h-full cursor-pointer outline-brand"
						>
						</div>
					</form>
				)
			}
		</div>
	);
};
