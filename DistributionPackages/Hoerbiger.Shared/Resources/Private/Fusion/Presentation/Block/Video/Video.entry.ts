export default ($node: Element):void => {
	if ($node instanceof HTMLElement) {
		const $poster = $node.querySelector("[data-select='poster']");
		const $video = $node.querySelector("video");

		if ($poster instanceof HTMLElement) {
			const onPosterClick = () => {
				$poster.parentElement?.removeChild($poster);

				if ($video instanceof HTMLVideoElement) {
					$video.play();
				}
			};

			$poster.addEventListener("click", onPosterClick, {once: true});
		}
	}
};
