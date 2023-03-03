import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		console.log("Hello HeaderVideoStage.entry.ts");
		const slider = $node.querySelector("[data-select='slider']");
		const DURATION = 4000;
		let timeout = 0;

		if (slider instanceof HTMLElement) {
			const onSlideChanged = (instance: KeenSlider) => {
				const currentSlide = instance.details().relativeSlide;
				console.log(currentSlide, "currentSlide");
				if (instance && instance.options()) {
					console.log(instance.options().slides, "slides");
				}


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
