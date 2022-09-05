export default function ($node: Element) {
	console.log($node)
	if ($node instanceof HTMLElement) {
		const scrollBtn = $node;
		console.log(scrollBtn);

		if (scrollBtn instanceof HTMLElement) {
			scrollBtn.addEventListener('click', () => {
				window.scrollTo({
					top: 0,
					left: 0,
					behavior: 'smooth'
				});
			})
		}
	}
}
