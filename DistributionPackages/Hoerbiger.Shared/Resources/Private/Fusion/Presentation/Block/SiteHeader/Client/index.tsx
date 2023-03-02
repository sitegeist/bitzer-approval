import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {StampedeIcon} from "../../../Application/Components";
import {useHandlerOnOuterClick} from "../../../Application/Utils/Hooks";

type ClientProps = {
	targetUri: string;
	labels: {
		[key: string]: string;
	}
	disableItems: NodeListOf<HTMLElement>
}
export const Client = ({targetUri, labels, disableItems}: ClientProps): JSX.Element => {
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
			disableItems.forEach((element) => {
				element.classList.add("menuToggle:flex");
			});
		}
	};

	const onClick = React.useMemo(() =>
		isTooSmallForExpandedView
			? redirectToSearchPage
			: () => {
				setIsExpandend(state => !state);
				disableItems.forEach((element) => {
					element.classList.remove("menuToggle:flex");
				});
			}
	, [isTooSmallForExpandedView]);

	return (
		<div
			ref={node}
			className="w-full h-full relative"
		>
			<div
				onClick={onClick}
				className={cx(
					"w-full h-full grid place-items-center z-40 cursor-pointer relative",
					isExpanded && !isTooSmallForExpandedView
						? "text-standard"
						: "text-light"
				)}
			>
				<StampedeIcon iconName="search" size={1.5}/>
			</div>
			{
				(isExpanded && !isTooSmallForExpandedView) && (
					<form
						onSubmit={onSubmit}
						className={cx(
							"absolute z-50 top-[25%] bottom-0 right-0 w-[400%] h-[50px] rounded-full",
							"grid grid-cols-4 bg-light text-standard"
						)}
					>
						<div className="col-span-3 flex items-center">
							<input
								type="text"
								autoFocus
								value={query}
								onChange={(e: React.ChangeEvent<HTMLInputElement>) => setQuery(e.currentTarget.value)}
								className="border-none w-[80%] mx-auto focus:outline-none text-standard font-bold"
								placeholder={labels["placeholder.searchInput"] ?? ""}
							/>
						</div>
						<div
							onClick={onSubmit}
							className="w-full h-full cursor-pointer grid place-items-center text-standard"
							title={labels["title.submit"] ?? ""}
						>
							<StampedeIcon iconName="search" size={1.5}/>
						</div>
					</form>
				)
			}
		</div>
	);
};
