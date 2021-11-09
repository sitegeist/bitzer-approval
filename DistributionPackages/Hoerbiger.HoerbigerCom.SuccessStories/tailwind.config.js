const shared = require("@hoerbiger/shared/tailwind.config");

module.exports = {
	mode: 'jit',
	purge: [
		'./Resources/Private/Fusion/**/*'
	],
	...shared
};
