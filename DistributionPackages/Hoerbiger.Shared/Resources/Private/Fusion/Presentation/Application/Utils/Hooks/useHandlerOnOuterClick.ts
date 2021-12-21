import * as React from "react";

export const useHandlerOnOuterClick = (handler: () => void): React.RefObject<HTMLDivElement> => {
	const node = React.useRef(null);

	const handleClick = (e: MouseEvent) => {
		const $node = node.current as HTMLElement|null;

		if ($node !== null && !$node.contains(e.target as HTMLElement)) {
			handler();
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
