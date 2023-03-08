export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const visibleClass = $node.dataset.animationIn ?? "";
		const invisibleClass = $node.dataset.animationOut ?? "";

		let isVisible = false;

		// Page load not on Top
		const position = $node.getBoundingClientRect();
		if (position.top >= 0 && position.bottom <= window.innerHeight) {
			isVisible = true;
			animateElement(visibleClass, invisibleClass, isVisible, $node);
		}

		// EventListener
		window.addEventListener("scroll", function() {
			const position = $node.getBoundingClientRect();

			// remove from page
			if (position.bottom > window.innerHeight) {
				if (isVisible) {
					isVisible = false;
					animateElement(visibleClass, invisibleClass, isVisible, $node);
				}
				return;
			}

			if (!isVisible) {
				// checking whether fully visible
				if (position.top >= 0 && position.bottom <= window.innerHeight) {
					isVisible = true;
					animateElement(visibleClass, invisibleClass, isVisible, $node);
				}

				// checking for partial visibility
				// if (position.top < window.innerHeight && position.bottom >= 0) {
				// }
			}
		});
	}
};

export const animateElement = (
	visibleClass: string,
	invisibleClass: string,
	show: boolean,
	container: HTMLElement
) => {
	visibleClass && container.classList.toggle(visibleClass, show);
	container.classList.toggle("opacity-0", false);
	invisibleClass && container.classList.toggle(invisibleClass, !show);
};
