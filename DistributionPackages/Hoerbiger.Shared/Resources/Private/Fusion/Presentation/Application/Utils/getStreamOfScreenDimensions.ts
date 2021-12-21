import {fromEvent, Observable} from "rxjs";
import {map, startWith} from "rxjs/operators";

let stream$: Observable<number[]>|null;

export const getStreamOfScreenDimensions = (): Observable<number[]> => {
	if (!stream$) {
		stream$ = fromEvent(window, "resize")
			.pipe(
				map(() => [window.innerWidth, window.innerHeight]),
				startWith([window.innerWidth, window.innerHeight])
			);
	}

	return stream$;
};
