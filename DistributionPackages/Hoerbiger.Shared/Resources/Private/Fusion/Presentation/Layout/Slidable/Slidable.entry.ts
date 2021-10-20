import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $slidable = $node.querySelector("[data-select='slidable']");
		const $progress = $node.querySelector("[data-select='progress']");
		const $jumper = $node.querySelectorAll("[data-select='jumper']");

		if ($slidable instanceof HTMLElement) {
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
							$node.classList.toggle("!bg-brand", currentSlide === index);
							$node.classList.toggle("!text-brand-contrast", currentSlide === index);
							$node.classList.toggle("bg-neutral-contrast", currentSlide !== index);
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

			// ProgressBar
			if ($progress instanceof HTMLElement) {
				const tick = () => {
					window.requestAnimationFrame(() => {
						$progress.style.width = `${Math.min(100, 100 * (Date.now() - updatedAt) / DURATION)}%`;
						tick();
					});
				};

				tick();
			}

			// JumperNavigation.
			if ($jumper instanceof NodeList) {
				const onActivate = (index:number) => (e: MouseEvent) => {
					e.preventDefault();
					if (index !== currentSlide) {
						keenSlider.moveToSlide(index);
					}
				};

				$jumper.forEach(($node, index) => {
					if ($node instanceof HTMLElement) {
						$node.addEventListener("click", onActivate(index));
					}
				});
			}
		}
	}
};
