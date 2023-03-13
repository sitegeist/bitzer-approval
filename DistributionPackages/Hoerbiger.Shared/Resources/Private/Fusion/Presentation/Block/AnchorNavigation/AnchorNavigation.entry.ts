export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const anch = $node.querySelector(".anchorNav") as HTMLElement;
		const anchPos = $node.querySelector(".anchorPos") as HTMLElement;
		const neosContentCollection = document.querySelector("main .neos-contentcollection");

		anchPos.style.height = `${neosContentCollection?.getBoundingClientRect().height}px`;
		let isVisible = false
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
					anch.classList.add("fixed");
					anch.classList.remove("absolute");
				}
				return;
			}

			if (!isVisible) {
				if (position.top >= 0 && position.bottom <= window.innerHeight) {
					isVisible = true;
					anch.classList.remove("fixed");
					anch.classList.add("absolute");
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

			if(anchPos.getBoundingClientRect().bottom <= window.innerHeight) {
				anch.classList.remove("nav-top-inset");
				anch.classList.add("nav-bot-inset");
				anch.classList.remove("fixed");
				anch.classList.add("absolute");
			} else if (anchPos.getBoundingClientRect().top <= 0) {
				anch.classList.add("nav-top-inset");
				anch.classList.remove("nav-bot-inset");
				anch.classList.add("fixed");
				anch.classList.remove("absolute");
			}
		});

		window.addEventListener("resize", () => {
			anchPos.style.height = `${neosContentCollection?.getBoundingClientRect().height}px`;
		})
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
		const elementHeight = elementBoundingRect.height;
		let elementHeightInView = elementHeight;

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
}
