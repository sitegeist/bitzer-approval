export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const maxWidth = 768;
		const actualWidth = document.body.offsetWidth;
		const timelineElements = $node.querySelectorAll("details");

		if (timelineElements && maxWidth && actualWidth) {
			timelineElements.forEach(function(element) {
				if (actualWidth < maxWidth) {
					element.open = false;
				}
			});
		}
	}
};
