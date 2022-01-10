const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');
const selectorParser = require('postcss-selector-parser');

const MAX_CONTENT_WIDTH = '1920px';
const MIN_TOUCH = '48px';

const OFFCANVAS_WIDTH = '340px';

// up to 1920px screen-width this is 1/12 width, not growing any further above 1920px
const SPACE_SM = `min(calc(100vw / 36), calc(${MAX_CONTENT_WIDTH} / 36))`;
const SPACES = {
	'ch-xs': '.62ch',
	ch: '1ch',
	'2xs': `calc(.4 * ${SPACE_SM})`,
	xs: `calc(.62 * ${SPACE_SM})`,
	sm: SPACE_SM,
	md: `calc(2 * ${SPACE_SM})`,
	lg: `calc(3 * ${SPACE_SM})`,
	xl: `calc(4 * ${SPACE_SM})`,
	'2xl': `calc(6 * ${SPACE_SM})`,
	'minus-ch-xs': `-.62ch`,
	'minus-ch': `-1ch`,
	'minus-xs': `calc(-.62 * ${SPACE_SM})`,
	'minus-sm': `calc(-1 * ${SPACE_SM})`,
	'minus-md': `calc(-2 * ${SPACE_SM})`,
	'minus-lg': `calc(-3 * ${SPACE_SM})`,
	'minus-xl': `calc(-4 * ${SPACE_SM})`,
	'minus-2xl': `calc(-6 * ${SPACE_SM})`,
};

const NTH_CHILD_SELECTORS = ['nth-child-4n+1', 'nth-child-4n+2']

module.exports = {
	mode: 'jit',
	purge: [
		'./Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom.SuccessStories/Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom/Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom.AffiliateDirectory/Resources/Private/Fusion/**/*',
	],
	darkMode: false,
	theme: {
		colors: {
			light: {
				DEFAULT: '#fff',
				contrast: 'rgba(0, 0, 0, .85)'
			},
			neutral: {
				lighter: '#F0F0F0',
				DEFAULT: '#B2B5B7',
				darker: '#8F8F8F',
				contrast: 'rgba(0, 0, 0, .85)',
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

		screens: {
			/* Breakpoint to switch between fullscreen and aside-offcanvas. */
			offcanvas: '400px',
			/* Breakpoint to switch between inline navigation and  offcanvas. */
			menuToggle: '1300px',
			xs: '480px',
			...defaultTheme.screens
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
				'2-1fr-max': '1fr max-content',
				tabs: '35% 1fr',
				'2-1fr-icon': '1fr 1.2em',
				'2-icon-1fr': '1.2em 1fr',
				'affiliates-map-list': '70% 1fr',
			},

			gridTemplateRows: {
				'form-field': '2.5em max-content',
			},

			minWidth: {
				touch: MIN_TOUCH,
			},

			minHeight: {
				touch: MIN_TOUCH,
			},

			maxHeight: {
				touch: MIN_TOUCH,
			},

			maxWidth: {
				...SPACES,
				'content-full': `min(${MAX_CONTENT_WIDTH}, 100vw)`,
				'content-66': `min(calc(2/3 * ${MAX_CONTENT_WIDTH}), 100vw)`,
				'10-sm': `calc(10 * ${SPACES.sm})`,
				'quotation': '1152px',
			},

			width: {
				...SPACES,
				offcanvas: OFFCANVAS_WIDTH,
				touch: MIN_TOUCH,
			},

			height: {
				...SPACES,
				touch: MIN_TOUCH,
			},

			space: {
				...SPACES,
			},

			margin: {
				...SPACES,
			},

			padding: {
				...SPACES,
				touch: MIN_TOUCH,
				'ratio-square': '100%',
				'ratio-2-1': '50%',
				'ratio-4-3': 'calc(3 * 100% / 4)',
				'ratio-5-4': 'calc(4 * 100% / 5)',
				'ratio-16-9': 'calc(9 * 100% / 16)',
				'ratio-16-10': 'calc(10 * 100% / 16)',
				'ratio-google-map': 'calc(9 * 100% / 16)',
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

			boxShadow: {
				/* Will match to py-3. */
				'decorative-heading-3': `1.25rem 0 0 #075289, -1.25rem 0 0 #075289`,
				'textbox': '0 0 14px rgba(0, 0, 0, .08)',
				'textbox-hover': '0 0 24px rgba(0, 0, 0, .2)',
				card: '0 0 14px rgba(0, 0, 0, 0.1)',
				play: '0 0 48px #8D172E80',
				submenu: '0 0 5px 2px rgb(0 0 0 / 10%)',
			},

			dropShadow: {
				brand: '0 0  5px #8D172E60',
			},

			outline: theme => ({
				brand: `1px solid ${theme('colors.brand.DEFAULT')}`,
			}),

			zIndex: {
				'form-field': '30',
				'form-field-elevated': '50',
				header: '70',
				content: '50',
			},

			right: {
				offcanvas: OFFCANVAS_WIDTH,
			},
		},
	},
	variants: {
		transform: ({after}) => after(['group-hover', 'open']),
		backgroundColor: ({after}) => after(NTH_CHILD_SELECTORS),
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
		plugin(({addVariant, e}) => {
			NTH_CHILD_SELECTORS
				.map(item => item.replace('nth-child-', ''))
				.forEach((item) =>
					addVariant(`nth-child-${item}`, ({modifySelectors, separator}) =>
						modifySelectors(({className}) =>
							`.${e(`nth-child-${item}${separator}${className}`)}:nth-child(${item})`
						)
					)
				)
		}),
		plugin(({addVariant}) =>
			addVariant('open', ({modifySelectors, separator}) =>
				modifySelectors(({selector}) => {
					return selectorParser((selectors) => {
						selectors.walkClasses((sel) => {
							sel.value = `open${separator}${sel.value}`;
							sel.parent.insertBefore(sel, selectorParser().astSync('details[open] '));
						});
					}).processSync(selector)

				})
			)
		),
		require('@tailwindcss/typography'),
	],
};
