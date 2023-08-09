export default ($node: HTMLElement): void => {
	const gridContainer = $node.querySelector("[data-grid=cards]") as HTMLElement;
	const filtersContainer = $node.querySelector("[data-grid=filters]") as HTMLElement;

	const cardItems = gridContainer.querySelectorAll("a");
	const filterItems = filtersContainer?.querySelectorAll("[data-filter-item]") as NodeListOf<HTMLElement>;
	const resetBtn = filtersContainer?.querySelector("[data-reset-item=reset]") as HTMLElement;

	const filterFromHash = window.location.hash.substr(1);
	let filterFromHashExists = false;

	/*
	 * Filter Handling - handle filters for Cards
	 * */
	if (filtersContainer) {
		resetBtn.addEventListener(("click"), () => {
			updateFilterItems(null, filterItems, resetBtn);
			showOnlyCardsFromCategory(null, cardItems);

			location.hash = "";
			history.pushState("", "", location.pathname);
		});

		filterItems.forEach((filter) => {
			filter.addEventListener(("click"), () => {
				const selectedFilter = filter.dataset.filterItem ?? "";

				updateFilterItems(selectedFilter, filterItems, resetBtn);
				showOnlyCardsFromCategory(selectedFilter, cardItems);

				location.hash = selectedFilter;
			});

			if (filterFromHash &&( (filter.dataset.filterItem ?? "") === filterFromHash)) {
				filterFromHashExists = true;
			}
		});
	}

	function showOnlyCardsFromCategory(type: string | null, cards: NodeListOf<HTMLElement>) {
		cards.forEach((card) => {
			const cardContent = card.querySelector("[data-card-type]") as HTMLElement;
			const cardType = cardContent.dataset.cardType ?? "";

			card.classList.toggle("hidden", (cardType != type) && (type != null));
		});
	}

	function updateFilterItems(selected: string | null, filterItems: NodeListOf<HTMLElement>, resetBtn: HTMLElement) {
		filterItems.forEach((filter) => {
			const filterType = filter.dataset.filterItem ?? "";

			if (selected) {
				toggleFilterItem(filterType == selected, filter);
			} else {
				filter.classList.remove("border-[red]");
				filter.classList.remove("text-[red]");
				filter.classList.remove("opacity-50");

			}

		});

		if (selected) {
			resetBtn.classList.remove("hidden");
		} else {
			resetBtn.classList.add("hidden");
		}
	}

	function toggleFilterItem(selected: boolean, filter: HTMLElement) {
		filter.classList.toggle("border-[red]", selected);
		filter.classList.toggle("text-[red]", selected);
		filter.classList.toggle("opacity-50", !selected);
	}

	/* --------------------------------------------------------------------------------------------------------------*/

	/*
	 * Grid Handling - resize Card Height for each Element
	 * */
	if (gridContainer) {
		resizeAllGridItems(gridContainer);
		window.addEventListener("resize", () => {
			resizeAllGridItems(gridContainer);
		});
	}

	function resizeAllGridItems(container: HTMLElement) {
		const rowHeight = parseInt(window.getComputedStyle(container).getPropertyValue("grid-auto-rows"));
		const rowGap = parseInt(window.getComputedStyle(container).getPropertyValue("grid-row-gap"));
		const allItems = container.querySelectorAll("a") as NodeListOf<HTMLElement>;

		allItems.forEach((item) => {
			resizeGridItem(item, container, rowGap, rowHeight);
		});
	}

	function resizeGridItem(item: HTMLElement, container: HTMLElement, rowGap: number, rowHeight: number) {
		const content = item.querySelector("[data-card=press]") as HTMLElement;

		if (content) {
			content.classList.remove("h-full");

			const rowSpan = Math.ceil((content.getBoundingClientRect().height+rowGap) / (rowHeight + rowGap));
			item.style.gridRowEnd = "span " + rowSpan;

			content.classList.add("h-full");
		}
	}

	if (filtersContainer && filterItems && resetBtn && cardItems && filterFromHashExists) {
		updateFilterItems(filterFromHash, filterItems, resetBtn);
		showOnlyCardsFromCategory(filterFromHash, cardItems);
	}
};
