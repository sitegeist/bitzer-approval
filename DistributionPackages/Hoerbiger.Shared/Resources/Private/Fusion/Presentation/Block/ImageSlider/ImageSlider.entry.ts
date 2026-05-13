import EmblaCarousel, { EmblaOptionsType } from "embla-carousel";
import Autoplay from "embla-carousel-autoplay";
import { addPrevNextBtnsClickHandlers } from "./EmblaCarouselArrowButtons";

export default (el: HTMLElement):void => {
	const OPTIONS: EmblaOptionsType = {
		dragFree: false,
		loop: true
	};

	const emblaViewport = <HTMLElement>el.querySelector(".embla__viewport");
	const prevBtnNode = <HTMLElement>el.querySelector(".embla__button--prev");
	const nextBtnNode = <HTMLElement>el.querySelector(".embla__button--next");
	const rootNode = el;
	const intervalSeconds = Number(rootNode.dataset.interval ?? "5");
	const intervalMs = Number.isFinite(intervalSeconds) && intervalSeconds > 0
		? Math.round(intervalSeconds * 1000)
		: 0;
	const canAutoplay = intervalMs > 0;
	const autoplayPlugin = Autoplay({
		active: canAutoplay,
		delay: intervalMs > 0 ? intervalMs : 4000
	});

	const emblaApi = EmblaCarousel(emblaViewport, OPTIONS, [autoplayPlugin]);

	const updateSelected = (): void => {
		const slides = emblaApi.slideNodes();
		const selectedIndex = emblaApi.selectedScrollSnap();
		slides.forEach((slide: Element): void => slide.classList.remove("is-active"));
		if (slides[selectedIndex]) slides[selectedIndex].classList.add("is-active");
	};

	const updateSliderActiveState = (): void => {
		const canSlide = emblaApi.canScrollPrev() || emblaApi.canScrollNext();
		if (canSlide) rootNode.classList.remove("slider-inactive");
		else rootNode.classList.add("slider-inactive");
	};

	const updateSliderState = (): void => {
		updateSelected();
		updateSliderActiveState();
	};

	const refreshSlider = (): void => {
		updateSliderState();
		const canSlide = emblaApi.canScrollPrev() || emblaApi.canScrollNext();
		if (!canAutoplay) return;
		if (!canSlide) emblaApi.plugins().autoplay?.stop();
		else emblaApi.plugins().autoplay?.play();
	};
	emblaApi.on("select", refreshSlider);
	emblaApi.on("reInit", refreshSlider);
	refreshSlider();

	const removePrevNextBtnsClickHandlers = addPrevNextBtnsClickHandlers(
		emblaApi,
		prevBtnNode,
		nextBtnNode
	);

	emblaApi.on("destroy", (): void => {
		emblaApi.plugins().autoplay?.stop();
		removePrevNextBtnsClickHandlers();
	});
};
