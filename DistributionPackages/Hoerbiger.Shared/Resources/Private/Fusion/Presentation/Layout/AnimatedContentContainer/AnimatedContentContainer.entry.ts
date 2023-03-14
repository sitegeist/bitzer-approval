export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const visibleClass = $node.dataset.animationIn ?? "";
		const invisibleClass = $node.dataset.animationOut ?? "";
		const fadeOutOffset = 400;
		let isVisible = false;
		let lastScrollPosition = 0;
		let isScrollUp = false;

		// Page load not on Top
		const position = $node.getBoundingClientRect();
		if (position.top >= 0 && position.bottom <= window.innerHeight) {
			isVisible = true;
			animateElement(visibleClass, invisibleClass, isVisible, $node);
		}

		// EventListener
		window.addEventListener("scroll", function() {
			const scrollPosition = window.scrollY || document.documentElement.scrollTop;
			const position = $node.getBoundingClientRect();

			isScrollUp = scrollPosition <= lastScrollPosition;
			lastScrollPosition = scrollPosition <= 0 ? 0 : scrollPosition;

			// remove from page
			if (isScrollUp && position.bottom > (window.innerHeight - fadeOutOffset)) {
				if (isVisible) {
					isVisible = false;
					animateElement(visibleClass, invisibleClass, isVisible, $node);
					return;
				}
			}

			if (!isVisible) {
				// checking whether fully visible
				if (!isScrollUp && position.top >= 0 && position.bottom <= window.innerHeight) {
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
	container.classList.toggle("opacity-0", !show);
	invisibleClass && container.classList.toggle(invisibleClass, !show);
};
