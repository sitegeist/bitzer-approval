import {filter} from "rxjs/operators";

import {NeosEvent} from "../../Application/Definitions";
import {getStreamOfNeosEvents, NEOS_NODE_SELECTED} from "../../Application/Utils/getStreamOfNeosEvents";
export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $tabs: Element[] = Array.from($node.querySelectorAll("[data-select='tab']"));
		const $accordeonHeader: Element[] = Array.from($node.querySelectorAll("[data-select='accordeon-header']"));
		const $tabContents: Element[] = Array.from($node.querySelectorAll("[data-select='tab-content']"));
		const $isHorizontal: boolean = $node.dataset.orientation === "horizontal";

		if ($tabs.length === $tabContents.length && $accordeonHeader.length === $tabContents.length) {
			const updateInnerButtons = (index:number): void => {
				$tabs.forEach(($tab, i) =>  {
					const $button: Element = $tab.children[0];
					console.log(i, index);
					$button.classList.toggle("text-light", i === index);

					$button.classList.toggle("bg-brand", i === index);
					$button.classList.toggle("bg-neutral", i !== index);
				});
			};

			const onUpdateIndex = (index: number) => {
				if (index >= 0) {
					$tabs.forEach(($tab, i) =>{

						if($isHorizontal){
							updateInnerButtons(index);
						}else{
							$tab.classList.toggle("opacity-100", i === index);
							$tab.classList.toggle("opacity-30", i !== index);
						}
						$tabContents[i].classList.toggle("flex", i === index);
						$tabContents[i].classList.toggle("hidden", i !== index);
						$accordeonHeader[i].querySelector("[data-select='icon']")?.classList.toggle("rotate-180", i === index);
					});
				}
			};

			const onNeosEvent = (e: NeosEvent) => {
				switch (e.type) {
				case NEOS_NODE_SELECTED:
					onUpdateIndex($tabContents.findIndex($tabContent => $tabContent.contains(e.detail.element)));
					break;
				default:
					break;
				}
			};

			getStreamOfNeosEvents()
				.pipe(
					filter(({detail: {element}}: NeosEvent) => $node.contains(element))
				)
				.subscribe(onNeosEvent);

			$tabs.forEach(($tab, index) => $tab.addEventListener("click", () => onUpdateIndex(index)));
			$accordeonHeader.forEach(($item, index) => $item.addEventListener("click", () => onUpdateIndex(index)));
			if($isHorizontal){
				updateInnerButtons(0);
			}
		}
	}
};
