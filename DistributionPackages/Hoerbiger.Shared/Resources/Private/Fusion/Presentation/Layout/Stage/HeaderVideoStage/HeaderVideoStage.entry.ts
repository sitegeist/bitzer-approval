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
		const DURATION = 6000;
		let timeout = 0;
		const isInNeosBackend = ("neos" in window);

		if (slider instanceof HTMLElement) {
			const keenSlider = new KeenSlider(
				slider,
				{
					loop: true,
					drag: (!isInNeosBackend),
					defaultAnimation: {
						duration: 1
					},
					detailsChanged: (instance) => {
						const currentSlide = instance.slides[instance.track.details.rel];
						if (currentSlide instanceof HTMLElement) {
							console.log(currentSlide, "currentSlide");
							const video = currentSlide.querySelector("video") as HTMLVideoElement;
							console.log(video, "video");
							console.log(video.currentTime, "video.currentTime");

							video.pause();
							video.load();
							video.play();
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
