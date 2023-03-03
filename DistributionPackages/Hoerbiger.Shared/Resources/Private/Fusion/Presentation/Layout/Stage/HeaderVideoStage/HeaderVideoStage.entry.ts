import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const slider = $node.querySelector("[data-select='slider']");
		const DURATION = 4000;
		let timeout = 0;

		if (slider instanceof HTMLElement) {
			const onSlideChanged = (instance: KeenSlider) => {
				const currentSlide = instance.details().relativeSlide;

				if (timeout) {
					window.clearTimeout(timeout);
				}
				timeout = window.setTimeout(instance.next, DURATION);
			};

			// Init slider.
			const keenSlider = new KeenSlider(
				slider,
				{
					loop: true,
					controls: false,
					slidesPerView: 1,
					duration: DURATION,
					slideChanged: onSlideChanged
				},
			);
		}

	}
};
