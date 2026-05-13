import EmblaCarousel, { EmblaOptionsType } from "embla-carousel";
import { addPrevNextBtnsClickHandlers } from "./EmblaCarouselArrowButtons";

export default (el: HTMLElement):void => {
	const OPTIONS: EmblaOptionsType = {
		dragFree: true,
		slidesToScroll: "auto"
	};

	const emblaViewport = <HTMLElement>el.querySelector(".embla__viewport");
	const prevBtnNode = <HTMLElement>el.querySelector(".embla__button--prev");
	const nextBtnNode = <HTMLElement>el.querySelector(".embla__button--next");

	const emblaApi = EmblaCarousel(emblaViewport, OPTIONS);

	const removePrevNextBtnsClickHandlers = addPrevNextBtnsClickHandlers(
		emblaApi,
		prevBtnNode,
		nextBtnNode
	);

	emblaApi.on("destroy", removePrevNextBtnsClickHandlers);
};
