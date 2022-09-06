export default function ($node: Element) {
	if ($node instanceof HTMLElement) {
		$node.addEventListener('click', () => {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: 'smooth'
			});
		})
		window.addEventListener('scroll', () => {
			$node.classList.toggle('opacity-0', window.scrollY < 100);
		});
	}
}
