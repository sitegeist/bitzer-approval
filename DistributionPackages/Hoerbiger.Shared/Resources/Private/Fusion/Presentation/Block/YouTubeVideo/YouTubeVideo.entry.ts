export default (el: Element):void => {
	if (el instanceof HTMLElement) {
		const iframe = el.querySelector("[data-select=\"iframe\"]");

		if (iframe instanceof HTMLIFrameElement) {
			el.addEventListener("click", () => {
				const {src} = iframe.dataset;

				if (src) {
					iframe.src = src;
					Array.from(iframe.parentElement?.children ?? []).forEach(el => {
						if (el !== iframe) {
							el.parentElement?.removeChild(el);
						}
					});
				}
			}, {once: true});
		}
	}
};
