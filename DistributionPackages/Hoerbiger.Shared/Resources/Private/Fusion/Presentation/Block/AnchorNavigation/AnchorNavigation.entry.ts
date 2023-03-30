export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const anchorNav = $node.querySelector("[data-anchor-nav]") as HTMLElement;
		const anchorPos = $node.querySelector("[data-anchor-pos]") as HTMLElement;
		const main = document.querySelector("main") as HTMLElement;
		const elementItems = document.querySelectorAll("[data-element-height]") as NodeListOf<HTMLElement>;
		let height = document.querySelector("main")?.getBoundingClientRect().height ?? 0;
		let isVisible = false;

		elementItems.forEach((content) => {
			height -= content.getBoundingClientRect().height;
		});

		anchorPos.style.height = `${height}px`;

		window.addEventListener("scroll", () => {
			const position = $node.getBoundingClientRect();

			if (position.bottom > window.innerHeight) {
				if (isVisible) {
					isVisible = false;
				}
				return;
			}

			if (position.bottom <= 0) {
				if (isVisible) {
					isVisible = false;
					anchorNav.classList.add("fixed");
					anchorNav.classList.remove("absolute");
				}
				return;
			}

			if (!isVisible) {
				if (position.top >= 0 && position.bottom <= window.innerHeight) {
					isVisible = true;
					anchorNav.classList.remove("fixed");
					anchorNav.classList.add("absolute");
				}
			}

		})

		const anchorItems = $node.querySelectorAll(".anchorItem") as NodeListOf<HTMLElement>;

		anchorItems.forEach((item , index) => {
			const progressBar = item.querySelector(".animate-progress-nav") as HTMLElement;
			progressBar.style.animationDelay=`calc(var(--itemProgress${index}) * -1s)`;
		});

		window.addEventListener("scroll", () => {
			anchorItems.forEach((item , index) => {
				const ref = item.querySelector(".itemLink")?.getAttribute("href")?.substring(1) as string;
				const refEl = document.getElementById(ref) as HTMLElement;

				document.body.style.setProperty("--itemProgress" + index, getViewPercentage(refEl));
			});

			if (anchorPos.getBoundingClientRect().bottom <= window.innerHeight) {
				anchorNav.classList.remove("nav-top-inset");
				anchorNav.classList.add("nav-bot-inset");
				anchorNav.classList.remove("fixed");
				anchorNav.classList.add("absolute");
			} else if (anchorPos.getBoundingClientRect().top <= 0) {
				anchorNav.classList.add("nav-top-inset");
				anchorNav.classList.remove("nav-bot-inset");
				anchorNav.classList.add("fixed");
				anchorNav.classList.remove("absolute");
			}
		});

		window.addEventListener("resize", () => {
			anchorPos.style.height = `${main?.getBoundingClientRect().height - window.innerHeight}px`;
		});
	}

	function getViewPercentage(element: HTMLElement): string {
		const viewport = {
			top: window.pageYOffset,
			bottom: window.pageYOffset + window.innerHeight
		};
		const elementBoundingRect = element.getBoundingClientRect();
		const elementPos = {
			top: elementBoundingRect.y + window.pageYOffset,
			bottom: elementBoundingRect.y + elementBoundingRect.height + window.pageYOffset
		};
		let elementHeightInView = elementBoundingRect.height;

		//ELEMENT NOT IN VIEWPORT
		if (viewport.top > elementPos.bottom || viewport.bottom < elementPos.top) {
			return "0";
		}

		// ELEMENT COMPLETELY IN VIEWPORT
		if (viewport.top < elementPos.top && viewport.bottom > elementPos.bottom) {
			elementHeightInView = elementHeightInView - (elementPos.bottom - viewport.bottom);
		}

		// Element BIGGER THAN VIEWPORT
		if (elementPos.top < viewport.top && elementPos.bottom > viewport.bottom) {
			return "0.9999";
		}

		if (elementPos.top < viewport.top) {
			return "0.9999";
		}

		if (elementPos.bottom > viewport.bottom) {
			elementHeightInView = elementHeightInView - (elementPos.bottom - viewport.bottom);
		}

		const percentageInView = (elementHeightInView / window.innerHeight);
		return percentageInView.toString();
	}
};
