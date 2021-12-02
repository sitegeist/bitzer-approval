import {fromEvent, Observable} from "rxjs";
import {map, mergeWith} from "rxjs/operators";

import {NeosEvent} from "../Definitions";

export const NEOS_NODE_SELECTED = "Neos.NodeSelected";
export const NEOS_NODE_CREATED = "Neos.NodeCreated";
export const NEOS_NODE_REMOVED = "Neos.NodeRemoved";

let stream$: Observable<NeosEvent>|null;

export const getStreamOfNeosEvents = (): Observable<any> => {
	if (!stream$) {
		const nodeSelected$: Observable<NeosEvent> = fromEvent(document, NEOS_NODE_SELECTED).pipe(map((e: any) => e as NeosEvent));
		const nodeCreated$: Observable<NeosEvent> = fromEvent(document, NEOS_NODE_CREATED).pipe(map((e: any) => e as NeosEvent));
		const nodeRemoved$: Observable<NeosEvent> = fromEvent(document, NEOS_NODE_REMOVED).pipe(map((e: any) => e as NeosEvent));

		stream$ = nodeSelected$.pipe(
			mergeWith(nodeCreated$),
			mergeWith(nodeRemoved$),
		);
	}

	return stream$;
};
