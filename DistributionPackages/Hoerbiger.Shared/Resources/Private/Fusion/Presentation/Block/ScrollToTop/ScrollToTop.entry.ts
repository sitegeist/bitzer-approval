export default function ($node: Element) {
	console.log($node)
	if ($node instanceof HTMLElement) {
			$node.addEventListener('click', () => {
				window.scrollTo({
					top: 0,
					left: 0,
					behavior: 'smooth'
			});
		})
	}
}
