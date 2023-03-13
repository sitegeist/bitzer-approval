import * as React from "react";
import {useMediaQuery} from "beautiful-react-hooks";
import cx from "classnames";

import {StampedeIcon} from "../../../Application/Components";

type ClientProps = {
	targetUri: string;
	labels: {
		[key: string]: string;
	}
	disableItems: NodeListOf<HTMLElement>
}
export const Client = ({targetUri, labels, disableItems}: ClientProps): JSX.Element => {
	const isTooSmallForExpandedView = useMediaQuery("(max-width: 780px)");
	const [isExpanded, setIsExpanded] = React.useState(false);
	const isRedirecting = React.useRef(false);
	const [query, setQuery] = React.useState("");

	const close = () => setIsExpanded(false);
	const node = useHandlerOnOuterClick(close, disableItems);

	const redirectToSearchPage = () => {
		if (!isRedirecting.current) {
			isRedirecting.current = true;

			const url = new URL(targetUri);

			if (query.length) {
				url.searchParams.append("searchTerm", query);
			}

			disableItems.forEach((element) => {
				element.classList.add("menuToggle:flex");
			});
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
				setIsExpanded(state => !state);
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
					"w-full h-full grid place-items-center z-40 cursor-pointer relative menuToggle:translate-x-[50px]",
				)}
			>
				<StampedeIcon iconName="search"  size={1.5}/>
			</div>
			{
				(isExpanded && !isTooSmallForExpandedView) && (
					<form
						onSubmit={onSubmit}
						className={cx(
							"absolute z-50 border-b bottom-0 right-0 w-[1000%] h-[50px]",
							"grid grid-cols-4"
						)}
					>
						<div className="col-span-3 flex items-center">
							<input
								type="text"
								autoFocus
								value={query}
								onChange={(e: React.ChangeEvent<HTMLInputElement>) => setQuery(e.currentTarget.value)}
								className="border-none w-[80%] focus:outline-none text-neutral-contrast font-bold bg-transparent"
								placeholder={labels["placeholder.searchInput"] ?? ""}
							/>
						</div>
						<div
							onClick={onSubmit}
							className="w-full h-full cursor-pointer grid place-items-center text-light translate-x-[42px]"
							title={labels["title.submit"] ?? ""}
						>
						</div>
					</form>
				)
			}
		</div>
	);
};

export const useHandlerOnOuterClick = (handler: () => void, disableItems: NodeListOf<HTMLElement>): React.RefObject<HTMLDivElement> => {
	const node = React.useRef(null);

	const handleClick = (e: MouseEvent) => {
		const $node = node.current as HTMLElement|null;

		if ($node !== null && !$node.contains(e.target as HTMLElement)) {
			handler();

			disableItems.forEach((element) => {
				element.classList.add("menuToggle:flex");
			});
		}
	};

	React.useEffect(() => {
		document.addEventListener("mousedown", handleClick);

		return () => {
			document.removeEventListener("mousedown", handleClick);
		};
	}, []);

	return node;
};
