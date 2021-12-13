import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {StampedeIcon} from "../../Components";
import {useHandlerOnOuterClick} from "../../Utils/Hooks";

type ClientProps = {
	targetUri: string;
	labels: {
		[key: string]: string;
	}
}
export const Client = ({targetUri, labels}: ClientProps): JSX.Element => {
	const isTooSmallForExpandedView = useMediaQuery("(max-width: 780px)");
	const [isExpanded, setIsExpandend] = React.useState(false);
	const isRedirecting = React.useRef(false);
	const [query, setQuery] = React.useState("");

	const close = () => setIsExpandend(false);
	const node = useHandlerOnOuterClick(close);

	const redirectToSearchPage = () => {
		if (!isRedirecting.current) {
			isRedirecting.current = true;

			const url = new URL(targetUri);

			if (query.length) {
				url.searchParams.append("searchTerm", query);
			}

			close();
			window.location.href = url.toString();
		}
	};

	const onSubmit = (e: React.FormEvent) => {
		e.preventDefault();

		if (query.length) {
			redirectToSearchPage();
		} else {
			close();
		}
	};

	const onClick = React.useMemo(() =>
		isTooSmallForExpandedView
			? redirectToSearchPage
			: () => setIsExpandend(state => !state)
	, [isTooSmallForExpandedView]);

	return (
		<div
			onMouseLeave={close}
			ref={node}
			className="w-full h-full relative"
		>
			<div
				onClick={onClick}
				className="text-neutral-contrast w-full h-full grid place-items-center z-10 cursor-pointer"
			>
				<StampedeIcon iconName="search" size={1.5}/>
			</div>
			{
				(isExpanded && !isTooSmallForExpandedView) && (
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
								placeholder={labels["placeholder.searchInput"] ?? ""}
							/>
						</div>
						<div
							onClick={onSubmit}
							className="w-full h-full cursor-pointer outline-brand"
							title={labels["title.submit"] ?? ""}
						>
						</div>
					</form>
				)
			}
		</div>
	);
};
