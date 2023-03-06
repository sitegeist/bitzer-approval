import KeenSlider from "keen-slider";

export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const slider = $node.querySelector("[data-select='slider']");
		const DURATION = 6000;
		let timeout = 0;

		if (slider instanceof HTMLElement) {
			const keenSlider = new KeenSlider(
				slider,
				{
					loop: true,
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

						if (timeout) {
							window.clearTimeout(timeout);
						}
						timeout = window.setTimeout(instance.next, DURATION);
					}
				},
			);
		}

	}
};
