import * as React from "react";
import * as ReactDom from "react-dom";

import {Client} from "./Client";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const header = $node.querySelector("header") as HTMLElement;
		const headerVariant = $node.dataset.siteHeaderVariant ?? "regular";

		// compactSearch
		const compactSearchRoot = $node.querySelector("[data-compact-search=root]") as HTMLElement;
		const compactSearchTargetUri = compactSearchRoot.dataset.targetUri ?? "";
		const labels = JSON.parse(compactSearchRoot.dataset.labels ?? "{}");
		const disableItems = $node.querySelectorAll("[data-search=visible]") as NodeListOf<HTMLElement>;

		if (compactSearchRoot instanceof HTMLElement && compactSearchTargetUri) {
			ReactDom.render(
				React.createElement(
					Client,
					{
						targetUri: compactSearchTargetUri,
						labels,
						disableItems
					}
				),
				compactSearchRoot
			);
		}

		// scroll Verhalten
		if (headerVariant === "transparent") {
			const activeLinks = header.querySelector("[data-active-main-link]") as HTMLElement;

			// ... wenn page nicht oben beginnt
			if (window.scrollY > 0) {
				header.classList.add("bg-header");
				header.classList.remove("bg-header-transparent");
				activeLinks.classList.add("text-neutral-contrast");
			}

			window.addEventListener("scroll", (e) => {
				const offset = window.scrollY;

				if (offset > 100) {
					header.classList.add("bg-header");
					header.classList.remove("bg-header-transparent");
					activeLinks.classList.add("text-neutral-contrast");
				} else if (offset === 0) {
					header.classList.add("bg-header-transparent");
					header.classList.remove("bg-header");
					activeLinks.classList.remove("text-neutral-contrast");
				}
			});
		}

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
							e.currentTarget.classList.toggle("text-brand");
						}

						$sibling.classList.toggle("hidden");
						e.preventDefault();
					}
				}

			});

		});
	}
};
