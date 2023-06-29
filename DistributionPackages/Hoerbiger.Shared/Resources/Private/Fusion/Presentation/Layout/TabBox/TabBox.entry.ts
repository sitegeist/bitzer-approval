import {filter} from "rxjs/operators";

import {NeosEvent} from "../../Application/Definitions";
import {getStreamOfNeosEvents, NEOS_NODE_SELECTED} from "../../Application/Utils/getStreamOfNeosEvents";
export default ($node: Element): void => {
	if ($node instanceof HTMLElement) {
		const $tabs: Element[] = Array.from($node.querySelectorAll("[data-select='tab']"));
		const $accordeonHeader: Element[] = Array.from($node.querySelectorAll("[data-select='accordeon-header']"));
		const $tabContents: Element[] = Array.from($node.querySelectorAll("[data-select='tab-content']"));
		const $isHorizontal: boolean = $node.dataset.orientation === "horizontal";
		const headerHeight = document.querySelector("header")?.getBoundingClientRect().height ?? 0;
		const theme = $node.dataset.theme ?? "";

		console.log(theme);

		if ($tabs.length === $tabContents.length && $accordeonHeader.length === $tabContents.length) {
			const updateInnerButtons = (index:number): void => {
				$tabs.forEach(($tab, i) =>  {
					const $button: Element = $tab.children[0];
					$button.classList.toggle("text-light", i === index);
					$button.classList.toggle("bg-brand", i === index);
					$button.classList.toggle("md:bg-neutral-lighter", i !== index);
				});
			};

			const onUpdateIndex = (index: number) => {
				if (index >= 0) {
					const $boxId = $tabs[index].getAttribute("data-box-id");
					if ($boxId) {
						const $parentElements = $node.parentNode?.parentNode?.parentNode;
						if ($parentElements) {
							const $element = $parentElements.querySelector(`#${$boxId}`);
							if ($element) {
								const scrollPos = ($element.getBoundingClientRect().top + window.scrollY) - headerHeight;
								window.scrollTo({top: scrollPos, behavior: "smooth"});
							}
						}
					}

					$tabs.forEach(($tab, i) =>{
						if ($isHorizontal) {
							updateInnerButtons(index);

							let toggleCondition = (i !== index) ? true : false;

							if (!$tabContents[i].classList.contains("hidden")) {
								toggleCondition = true;
							}
							$tabContents[i].classList.toggle("hidden", toggleCondition);
						} else {
							$tab.classList.toggle("font-bold", i === index);
							$tab.classList.toggle("font-base", i !== index);
							$tab.querySelector("[data-icon]")?.classList.toggle("pr-[15px]", i !== index);
							theme === "" && $tab.querySelector("[data-icon]")?.classList.toggle("text-service-red", i === index);

							$tabContents[i].classList.toggle("flow", i === index);
							$tabContents[i].classList.toggle("hidden", i !== index);
						}

						if (i === index) {
							$accordeonHeader[i].querySelector("[data-select='icon']")?.classList.add("rotate-180");
						} else {
							$accordeonHeader[i].querySelector("[data-select='icon']")?.classList.remove("rotate-180");
						}
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
