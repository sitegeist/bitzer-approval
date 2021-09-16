// const theme = require('tailwindcss/defaultTheme');

const MAX_CONTENT_WIDTH = '1920px';
const GRID_COL = 'clamp(1.61rem, calc(60 * 100vw / 1920), 40px)';
const MIN_TOUCH = '48px';

const lg = () => `clamp(1.61rem, calc(60 * 100vw / ${MAX_CONTENT_WIDTH}), 48px)`;
const md = (reverse = false) => `calc(${reverse ? -.4 : .4} * ${GRID_COL})`;
const xl = (reverse = false) => `calc(${reverse ? -2 : 2} * ${GRID_COL})`;

const spaces = {
	lg: lg(),
	md: md(),
	xl: xl(),
	'md-negative': md(true),
	'xl-negative': xl(true),
};

module.exports = {
	mode: 'jit',
	purge: [
		'./Resources/Private/Fusion/**/*'
	],
	darkMode: false,
	theme: {
		colors: {
			neutral: {
				DEFAULT: '#B2B5B7',
				contrast: '#fff',
			},
			brand: {
				DEFAULT: '#8D172E',
				contrast: '#fff',
			},
			decorative: {
				DEFAULT: '#075289',
				contrast: '#fff',
			},
		},
		fontFamily: {
		},
		extend: {
			typography: (theme) => ({
				'brand-contrast': {
					css: {
						color: theme('colors.brand.contrast'),
						strong: {
							color: theme('colors.brand.contrast'),
						},
						a: {
							color: theme('colors.brand.contrast'),
						},
					},
				},
				'neutral-contrast': {
					css: {
						color: theme('colors.neutral.contrast'),
						strong: {
							color: theme('colors.neutral.contrast'),
						},
						a: {
							color: theme('colors.neutral.contrast'),
						},
					},
				},
				'decorative-contrast': {
					css: {
						color: theme('colors.decorative.contrast'),
						strong: {
							color: theme('colors.decorative.contrast'),
						},
						a: {
							color: theme('colors.decorative.contrast'),
						},
					},
				},
			}),

			gridTemplateColumns: {
				'2-max-1fr': 'max-content 1fr',
			},

			minWidth: {
				touch: MIN_TOUCH,
			},

			minHeight: {
				touch: MIN_TOUCH,
			},

			maxWidth: {

			},

			space: {
				...spaces,
			},

			margin: {
				...spaces,
			},

			padding: {
				...spaces,
				'ratio-2-1': '50%',
				'ratio-4-3': 'calc(3 * 100% / 4)',
				'ratio-16-9': 'calc(9 * 100% / 16)',
				'ratio-16-10': 'calc(10 * 100% / 16)',
			},

			gap: {
				...spaces,
			},

			borderRadius: {
				...spaces,
			}
		},
	},
	variants: {
		extend: {
			transform: ['group-hover'],
		},
	},
	plugins: [
		require('@tailwindcss/typography'),
	],
};
