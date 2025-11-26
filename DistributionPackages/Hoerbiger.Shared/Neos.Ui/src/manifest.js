import manifest from '@neos-project/neos-ui-extensibility';

import {VideoParser} from '../lib';

manifest('@hoerbiger/shared-editors', {}, (globalRegistry) => {
	const editorsRegistry = globalRegistry.get('@sitegeist/inspectorgadget/editors');
	
	editorsRegistry.set(
		'Hoerbiger\\Shared\\Domain\\VideoIdentifier',
		VideoParser
	);
});
