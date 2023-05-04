export default ($node: Element):void => {
	if ($node instanceof HTMLElement) {
		const scrollBtn = document.querySelector("[data-scrollToTop]");
		scrollBtn?.addEventListener("click", () => {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: 'smooth'
			});
		});
	}
};


