export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		/* Handle hover-flyouts on touch-devices. */
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
				}, {
					passive: true,
				});
		});

		// handle mobile navigation as accordion
		Array.from($node.querySelectorAll("[data-accordion]")).forEach($link => {
			$link.addEventListener("click", (e: Event) => {
				if (e.currentTarget instanceof HTMLElement) {
					const $sibling = e.currentTarget.nextSibling as HTMLElement|undefined;

					if ($sibling && $sibling.dataset.accitem) {
						const $imgElement = e.currentTarget.querySelector("[data-accimage]");
						if ($imgElement) {
							$imgElement.classList.toggle("transition-all");
							$imgElement.classList.toggle("rotate-180");
						}

						$sibling.classList.toggle("hidden");
						e.preventDefault();
					}
				}

			});

		});
	}
};
