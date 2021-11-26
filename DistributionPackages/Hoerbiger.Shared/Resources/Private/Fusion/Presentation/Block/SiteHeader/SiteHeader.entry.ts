export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		Array.from($node.querySelectorAll("[data-touch-hover]")).forEach($link => {
			$link
				.addEventListener("touchstart", (e: Event) => {
					if (e.currentTarget instanceof HTMLAnchorElement) {
						try {
							const $nextSibling = e.currentTarget.parentNode?.nextSibling as Element|undefined;

							if ($nextSibling) {
								const {opacity} = window.getComputedStyle($nextSibling);

								if (opacity === "0") {
									e.preventDefault();
								}
							}
						/* eslint-disable no-empty */
						} catch(e) {}
						/* eslint-enable */
					}
				});
		});
	}
};
