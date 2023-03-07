import KeenSlider from "keen-slider";

declare global {
	interface Document {
		addEventListener(type: "Neos.NodeSelected" | "Neos.NodeRemoved" | "Neos.NodeCreated", listener: ((event: NeosUiNodeEvent) => void)): void;
	}
}

interface NeosUiNodeEvent extends Event {
	detail: {
		element: HTMLElement
	}
}

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const slider = $node.querySelector("[data-select='slider']");
		const DURATION = 8000;
		let timeout = 0;
		const isInNeosBackend = ("neos" in window);

		const jumpToNextContainer = $node.querySelector("[data-jump-to-end]") as HTMLElement;
		const textAnimationItems = $node.querySelectorAll("[data-fade-in-container]") as NodeListOf<HTMLElement>;

		doAnimations(textAnimationItems, 0);

		if (slider instanceof HTMLElement) {
			jumpToNextContainer.addEventListener("click", () => {
				const scrollPositionY = $node.offsetHeight;

				const header = document.querySelector("header") as HTMLElement;
				const headerHeight = header?.offsetHeight ?? 0;

				window.scrollTo({ top: scrollPositionY - headerHeight, left: 0, behavior: "smooth" });
			});

			const keenSlider = new KeenSlider(
				slider,
				{
					loop: true,
					drag: (!isInNeosBackend),
					defaultAnimation: {
						duration: 1
					},
					slideChanged: (instance) => {
						const currentSlide = instance.slides[instance.track.details.rel];
						if (currentSlide instanceof HTMLElement) {
							const video = currentSlide.querySelector("video") as HTMLVideoElement;

							video.pause();
							video.load();
							video.play();

							doAnimations(textAnimationItems, instance.track.details.rel);
						}

						if (!isInNeosBackend) {
							if (timeout) {
								window.clearTimeout(timeout);
							}
							timeout = window.setTimeout(instance.next, DURATION);
						}
					}
				},
			);

			if (isInNeosBackend) {
				slider.setAttribute("data-__neos-insertion-anchor", "");

				document.addEventListener("Neos.NodeSelected", (event) => {
					const itemSlider = event.detail.element;
					if (!$node.contains(itemSlider)) {
						return;
					}
					const index = Array.from(itemSlider.parentNode!.children).indexOf(itemSlider);
					keenSlider.update({drag: false}, index);
				});

				document.addEventListener("Neos.NodeCreated", (event) => {
					const itemSlider = event.detail.element;
					if (!$node.contains(itemSlider)) {
						return;
					}
					keenSlider.update({drag: false});
				});

				document.addEventListener("Neos.NodeRemoved", (event) => {
					const itemSlider = event.detail.element;
					if (!("sliderSlide" in itemSlider.dataset)) {
						return;
					}
					keenSlider.update({drag: false});
				});
			}
		}

	}
};

export const doAnimations = (animationItems: NodeListOf<HTMLElement>, active: number): void => {
	animationItems.forEach((slide, index) => {
		slide.classList.toggle("fade-in-text", active === index);
	});
};
