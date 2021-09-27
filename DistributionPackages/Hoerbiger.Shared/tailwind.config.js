// const theme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

const MAX_CONTENT_WIDTH = '1920px';
const MIN_TOUCH = '48px';

// up to 1920px screen-width this is 1/12 width, not growing any further above 1920px
const SPACE_SM = `min(calc(100vw / 36), calc(${MAX_CONTENT_WIDTH} / 36))`;
const SPACES = {
	xs: `calc(.62 * ${SPACE_SM})`,
	sm: SPACE_SM,
	md: `calc(2 * ${SPACE_SM})`,
	lg: `calc(3 * ${SPACE_SM})`,
	xl: `calc(4 * ${SPACE_SM})`,
	'2xl': `calc(6 * ${SPACE_SM})`,
	'minus-xs': `calc(-.62 * ${SPACE_SM})`,
	'minus-sm': `calc(-1 * ${SPACE_SM})`,
	'minus-md': `calc(-2 * ${SPACE_SM})`,
	'minus-lg': `calc(-3 * ${SPACE_SM})`,
	'minus-xl': `calc(-4 * ${SPACE_SM})`,
	'minus-2xl': `calc(-6 * ${SPACE_SM})`,
};

module.exports = {
	mode: 'jit',
	purge: [
		'./Resources/Private/Fusion/**/*'
	],
	darkMode: false,
	theme: {
		colors: {
			light: {
				DEFAULT: '#fff',
				contrast: 'rgba(0, 0, 0, .85)'
			},
			neutral: {
				lighter: '#F9F9F9',
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
			transparent: {
				DEFAULT: 'transparent',
				contrast: '#fff',
			},
		},

		fontFamily: {
			'sans-regular': ['TradeGothic', 'Helvetica', 'Arial', 'sans-serif'],
			'sans-bold': ['TradeGothicBold', 'Helvetica', 'Arial', 'sans-serif'],
			'serif-regular': ['PT Serif', 'serif'],
		},

		boxShadow: {
			/* Will match to py-3. */
			'decorative-heading-3': `1.25rem 0 0 #075289, -1.25rem 0 0 #075289`,
			'card': '0 0 14px rgba(0, 0, 0, .08)',
		},

		extend: {
			typography: (theme) => ({
				DEFAULT: {
					css: {
						color: theme('colors.light.contrast'),
						fontSize: '1.25rem',
						strong: {
							color: theme('colors.light.contrast'),
							fontFamily: theme('fontFamily.sans-bold'),
						},
						a: {
							color: theme('colors.brand.DEFAULT'),
							fontFamily: theme('fontFamily.sans-bold'),
							fontWeight: 600,
							textDecoration: 'none',
						},
						'ul li::before': {
							backgroundColor: theme('colors.light.contrast'),
						},
					},
				},
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
				'transparent-contrast': {
					css: {
						color: theme('colors.transparent.contrast'),
						strong: {
							css: {
								color: theme('colors.transparent.contrast'),
							},
						},
						a: {
							css: {
								color: theme('colors.transparent.contrast'),
							},
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
				'content-full': `min(${MAX_CONTENT_WIDTH}, 100vw)`,
				'10-sm': `calc(10 * ${SPACES.sm})`,
				'quotation': '1152px',
			},

			space: {
				...SPACES,
			},

			margin: {
				...SPACES,
			},

			padding: {
				...SPACES,
				'ratio-square': '100%',
				'ratio-2-1': '50%',
				'ratio-4-3': 'calc(3 * 100% / 4)',
				'ratio-5-4': 'calc(4 * 100% / 5)',
				'ratio-16-9': 'calc(9 * 100% / 16)',
				'ratio-16-10': 'calc(10 * 100% / 16)',
			},

			gap: {
				...SPACES,
			},

			inset: {
				...SPACES,
			},

			borderRadius: {
				...SPACES,
			},

			fontSize: {
				'5xl': ['3rem', {
					lineHeight: '1.3em',
				}],
				'6xl': ['3.75rem', {
					lineHeight: '1.3em',
				}],
			},

		},
	},
	variants: {
		extend: {
			transform: ['group-hover'],
		},
	},
	plugins: [
		plugin(({addUtilities}) => {
			addUtilities({
				'.column-count-2': {
					'column-count': 2,
				},
				'.column-gap-md': {
					'column-gap': SPACES.md,
				},
			}, ['responsive']);
		}),
		require('@tailwindcss/typography'),
	],
};
