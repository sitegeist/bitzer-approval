export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const anch = $node.querySelector('.anchorNav') as HTMLElement
		let isVisible = false
		window.addEventListener('scroll', () => {
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
			const progressBar = item.querySelector('.animate-progress-nav') as HTMLElement;
			progressBar.style.animationDelay=`calc(var(--itemProgress${index}) * -1s)`;
		});

		window.addEventListener('scroll', () => {
			anchorItems.forEach((item , index) => {
				const ref = item.querySelector(".itemLink")?.getAttribute("href")?.substring(1) as string;
				const refEl = document.getElementById(ref) as HTMLElement;

				document.body.style.setProperty('--itemProgress' + index, getPercentOfView(refEl, 200));
			});
		});
	}

	function getPercentOfView(element:HTMLElement, topDelay = 0): string {
		const viewportHeight = window.innerHeight;
		const scrollTop = window.scrollY;
		const elementOffsetTop = element.offsetTop - topDelay;
		const elementHeight = element.offsetHeight;

		const distance = scrollTop + viewportHeight - elementOffsetTop;
		const percentage = Math.round(
			distance / ((viewportHeight + elementHeight) / 100)
		);

		return ((Math.min(100, Math.max(0, percentage)) / 100).toString());
	}
}
