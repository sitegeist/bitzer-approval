export default ($node: Element):void => {
	if ($node instanceof HTMLElement) {
		const $poster = $node.querySelector("[data-select='poster']");
		const $video = $node.querySelector("video");

		if ($poster instanceof HTMLElement) {
			const onPosterClick = (e: MouseEvent): void => {
				if (e.currentTarget instanceof HTMLElement && !e.currentTarget.dataset.noPointerEvents) {
					$poster.parentElement?.removeChild($poster);

					if ($video instanceof HTMLVideoElement) {
						$video.play();
					}

					$poster.removeEventListener("click", onPosterClick);
				} else {
					e.preventDefault();
				}
			};

			$poster.addEventListener("click", onPosterClick);
		}
	}
};
