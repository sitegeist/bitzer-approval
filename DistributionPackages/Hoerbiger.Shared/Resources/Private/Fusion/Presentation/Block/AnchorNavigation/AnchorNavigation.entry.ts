export default ($node: Element): void => {
	let isVisible = false;
	if ($node instanceof HTMLElement) {
		const anchorNav = $node.querySelector("[data-anchor-nav]") as HTMLElement;
		const anchorPos = $node.querySelector("[data-anchor-pos]") as HTMLElement;
		const main = document.querySelector("main") as HTMLElement;
		const elementItems = document.querySelectorAll("[data-element-height]") as NodeListOf<HTMLElement>;
		let height = document.querySelector("main")?.getBoundingClientRect().height ?? 0;
		const anchorItems = $node.querySelectorAll(".anchorItem") as NodeListOf<HTMLElement>;

		setAnchorHeight(anchorPos, height, elementItems);
		setAnchorPos(anchorNav, anchorPos);
		setPercentageValues(anchorItems);
		window.addEventListener("scroll", () => {
			if (height !== document.querySelector("main")?.getBoundingClientRect().height) {
				height = document.querySelector("main")?.getBoundingClientRect().height ?? 0;
				setAnchorHeight(
					anchorPos,
					height,
					elementItems
				);
			}
			setAnchorPos(anchorNav, anchorPos);
			setPercentageValues(anchorItems);
		});

		anchorItems.forEach((item , index) => {
			const progressBar = item.querySelector(".animate-progress-nav") as HTMLElement;
			const progressRota = item.querySelector(".progress-rota") as HTMLElement;

			progressBar.style.animationDelay=`calc(var(--itemProgress${index}) * -1s)`;
			progressRota.style.rotate=`var(--itemProgressRota${index})`;
		});

		window.addEventListener("resize", () => {
			anchorPos.style.height = `${main?.getBoundingClientRect().height - window.innerHeight}px`;
		});
	}

	function getViewPercentage(element: HTMLElement , offset: number): [string, string] {
		const viewport = {
			top: parent.pageYOffset,
			bottom: parent.pageYOffset + parent.innerHeight
		};

		const elementBoundingRect = element.getBoundingClientRect();
		const elementPos = {
			top: (elementBoundingRect.y + parent.pageYOffset) + offset,
			bottom: (elementBoundingRect.y + elementBoundingRect.height + parent.pageYOffset) + offset
		};
		const elementHeight = elementBoundingRect.height;
		let elementHeightInView = elementHeight;

		if (viewport.top > elementPos.bottom || viewport.bottom < elementPos.top) {
			return ["0", "0"];
		}

		// Element is fully within viewport
		if (viewport.top < elementPos.top && viewport.bottom > elementPos.bottom) {
			return ["0.999999", "0"];
		}

		// Element is bigger than the viewport
		if (elementPos.top < viewport.top && elementPos.bottom > viewport.bottom) {
			return ["0.999999", "0"];
		}

		if (elementPos.top < viewport.top) {
			elementHeightInView = elementHeight - (parent.pageYOffset - elementPos.top);

			const percentageInView = (elementHeightInView / window.innerHeight);

			return [percentageInView.toString(), "180deg"];
		}

		if (elementPos.bottom > viewport.bottom) {
			elementHeightInView = elementHeightInView - (elementPos.bottom - viewport.bottom);
		}

		const percentageInView = (elementHeightInView / window.innerHeight);

		return [percentageInView.toString(), "0"];
	}

	function setAnchorHeight(anchorPos: HTMLElement, height: number, items: NodeListOf<HTMLElement>) {
		items.forEach((content) => {
			height -= content.getBoundingClientRect().height;
		});

		anchorPos.style.height = `${height}px`;
	}

	function setAnchorPos(anchorNav: HTMLElement, anchorPos: HTMLElement) {
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
	}

	function setPercentageValues(anchorItems: NodeListOf<HTMLElement>) {
		anchorItems.forEach((item , index) => {
			const ref = item.querySelector(".itemLink")?.getAttribute("href")?.substring(1) as string;
			const refEl = document.getElementById(ref) as HTMLElement;
			const refElSibling :HTMLElement = refEl.nextSibling as HTMLElement;

			const progressValues = getViewPercentage(refElSibling, 130);
			document.body.style.setProperty("--itemProgress" + index, progressValues[0]);
			document.body.style.setProperty("--itemProgressRota" + index, progressValues[1].toString());
		});
	}
};
