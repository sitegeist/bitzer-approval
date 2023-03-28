import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const slider = $node.querySelector("[data-select='slider']") as HTMLElement;
		const navigationItems = $node.querySelectorAll("[data-slider=inlineNavigationItem]") as NodeListOf<HTMLElement>;
		const counterItem = $node.querySelector("[data-slide-counter]") as HTMLElement;
		const textNavJumper = $node.querySelectorAll("[data-select='textNavJumper']") as NodeListOf<HTMLElement>;

		if (slider instanceof HTMLElement) {
			let currentSlide = 0;

			// eslint-disable-next-line @typescript-eslint/ban-ts-comment
			// @ts-ignore
			const onSlideChanged = (instance: KeenSlider) => {
				currentSlide = instance.track.details.rel;

				if (textNavJumper instanceof NodeList) {
					textNavJumper.forEach((navItem, index) => {
						if (navItem instanceof HTMLElement) {
							navItem.classList.toggle("text-slider-grey", currentSlide !== index);
						}
					});
				}

				counterItem.textContent = (currentSlide + 1).toString();
			};

			// eslint-disable-next-line @typescript-eslint/ban-ts-comment
			// @ts-ignore
			const onSliderCreated = (instance: KeenSlider) => {
				navigationItems.forEach(item => {
					item.addEventListener(("click"), () => {
						instance.moveToIdx(
							parseInt(item.dataset.itemIndex ?? "0"),
							false
						);
					});
				});
			};

			// Init slider.
			const keenSlider = new KeenSlider(slider, {
				drag: false,
				mode: "snap",
				detailsChanged: onSlideChanged,
				created: onSliderCreated
			});
		}
	}
};
