import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $slidable = $node.querySelector("[data-select='slidable']");
		const $jumper = $node.querySelectorAll("[data-select='jumper']");

		if ($slidable instanceof HTMLElement) {
			const $isApplicationWorld = !!$slidable.dataset.isApplicationWorld;
			const DURATION = 4000;
			let timeout = 0;
			let updatedAt = 0;
			let currentSlide = 0;

			const onSlideChanged = (instance: KeenSlider) => {
				currentSlide = instance.details().relativeSlide;
				updatedAt = Date.now();

				if ($jumper instanceof NodeList) {
					$jumper.forEach(($node, index) => {
						if ($node instanceof HTMLElement) {
							let $element: HTMLElement | null = $node;

							const $isThumNavigation = $node.querySelector("[data-type='ThumbNavigationItem']");
							if ($isThumNavigation instanceof HTMLElement) {
								$element = $node.querySelector("[data-type='ThumbNavigationItem']");
							}

							if($element && !$isApplicationWorld){
								$element.classList.toggle("!bg-brand", currentSlide === index);
								$element.classList.toggle("!text-brand-contrast", currentSlide === index);
								$element.classList.toggle("bg-neutral-lighter", currentSlide !== index);
							}else{
								if ($element) {
									$element.classList.toggle("opacity-100", currentSlide === index);
									$element.classList.toggle("opacity-50", currentSlide !== index);
								}
							}
						}
					});
				}

				if (timeout) {
					window.clearTimeout(timeout);
				}
				timeout = window.setTimeout(instance.next, DURATION);
			};

			// Init slider.
			const keenSlider = new KeenSlider($slidable, {
				loop: true,
				controls: false,
				slidesPerView: 1,
				mode: "snap",
				duration: 1000,
				slideChanged: onSlideChanged,
			});

			if (keenSlider) {
				const $prev = $node.querySelector("[data-select='prev']");
				const $next = $node.querySelector("[data-select='next']");

				if ($prev instanceof HTMLElement) {
					$prev.addEventListener("click", keenSlider.prev, false);
				}

				if ($next instanceof HTMLElement) {
					$next.addEventListener("click", keenSlider.next, false);
				}
			}
		}
	}
};
