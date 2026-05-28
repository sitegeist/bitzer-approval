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

	let lightboxOpen = false;

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
		if (!canSlide || lightboxOpen) emblaApi.plugins().autoplay?.stop();
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

	const lightboxOverlay = el.querySelector<HTMLElement>(".lightbox");
	const lightboxImages = Array.from(
		el.querySelectorAll<HTMLImageElement>("[data-lightbox-src]")
	);

	if (lightboxOverlay) {
		document.body.appendChild(lightboxOverlay);

		const lightboxImg = lightboxOverlay.querySelector<HTMLImageElement>("img")!;
		const lightboxCaption = lightboxOverlay.querySelector<HTMLElement>("figcaption")!;

		const closeLightbox = (): void => {
			lightboxOpen = false;
			delete lightboxOverlay.dataset.open;
			lightboxOverlay.setAttribute("aria-hidden", "true");
			document.body.style.overflow = "";

			const canSlide = emblaApi.canScrollPrev() || emblaApi.canScrollNext();
			if (canAutoplay && canSlide) emblaApi.plugins().autoplay?.play();
		};

		const openLightbox = (src: string, alt: string, title: string, orientation: string): void => {
			lightboxOpen = true;
			lightboxImg.src = src;
			lightboxImg.alt = alt;
			lightboxImg.title = title;
			lightboxImg.dataset.orientation = orientation;
			lightboxCaption.textContent = title;
			lightboxOverlay.dataset.open = "";
			lightboxOverlay.setAttribute("aria-hidden", "false");
			document.body.style.overflow = "hidden";
			emblaApi.plugins().autoplay?.stop();
		};

		const onImageClick = (e: Event): void => {
			const img = e.currentTarget as HTMLImageElement;
			const clickedIndex = lightboxImages.indexOf(img);
			if (clickedIndex !== emblaApi.selectedScrollSnap()) return;
			openLightbox(img.dataset.lightboxSrc ?? img.src, img.dataset.lightboxAlt ?? img.alt, img.title ?? "", img.dataset.orientation ?? "");
		};

		const onKeyDown = (e: KeyboardEvent): void => {
			if (e.key === "Escape") closeLightbox();
		};

		lightboxImages.forEach((img) => img.addEventListener("click", onImageClick));
		lightboxOverlay.addEventListener("click", closeLightbox);
		document.addEventListener("keydown", onKeyDown);

		emblaApi.on("destroy", (): void => {
			emblaApi.plugins().autoplay?.stop();
			removePrevNextBtnsClickHandlers();
			lightboxImages.forEach((img) => img.removeEventListener("click", onImageClick));
			lightboxOverlay.removeEventListener("click", closeLightbox);
			document.removeEventListener("keydown", onKeyDown);
			lightboxOverlay.remove();
		});
	} else {
		emblaApi.on("destroy", (): void => {
			emblaApi.plugins().autoplay?.stop();
			removePrevNextBtnsClickHandlers();
		});
	}
};
