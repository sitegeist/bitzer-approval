const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');
const selectorParser = require('postcss-selector-parser');
const { lineHeight } = require('tailwindcss/defaultTheme');
const { reduceEachLeadingCommentRange } = require('typescript');

const MAX_CONTENT_WIDTH = '1800px';
const MIN_TOUCH = '48px';

const OFFCANVAS_WIDTH = '340px';
const HEADER_HEIGHT = 127;
const NAV_HEIGHT = `${HEADER_HEIGHT + 340}px`;
const HOMEPAGE_TEASER_TEXT_WIDTH = '800px';
const LOGO_HOVER =  '#8A9199';
const HEADER_ITEMS_INACTIVE = '#7F7F7F';

// up to 1920px screen-width this is 1/12 width, not growing any further above 1920px
const SPACE_SM = `min(calc(100vw / 36), calc(${MAX_CONTENT_WIDTH} / 36))`;
const SPACES = {
	'ch-xs': '.62ch',
	ch: '1ch',
	'3ch': '3ch',
	'2xs': `calc(.4 * ${SPACE_SM})`,
	xs: `calc(.62 * ${SPACE_SM})`,
	sm: SPACE_SM,
	md: `calc(2 * ${SPACE_SM})`,
	lg: `calc(3 * ${SPACE_SM})`,
	xl: `calc(4 * ${SPACE_SM})`,
	'2xl': `calc(6 * ${SPACE_SM})`,
	'3xl': `calc(8 * ${SPACE_SM})`,
	'minus-ch-xs': `-.62ch`,
	'minus-ch': `-1ch`,
	'minus-xs': `calc(-.62 * ${SPACE_SM})`,
	'minus-sm': `calc(-1 * ${SPACE_SM})`,
	'minus-md': `calc(-2 * ${SPACE_SM})`,
	'minus-lg': `calc(-3 * ${SPACE_SM})`,
	'minus-xl': `calc(-4 * ${SPACE_SM})`,
	'minus-2xl': `calc(-6 * ${SPACE_SM})`,
	'25': '25px',
};

const NTH_CHILD_SELECTORS = ['nth-child-4n+1', 'nth-child-4n+2'];

module.exports = {
	content: [
		'./Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom.SuccessStories/Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom/Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom.AffiliateDirectory/Resources/Private/Fusion/**/*',
		'../Hoerbiger.HoerbigerCom.PressArchive/Resources/Private/Fusion/**/*',
	],
	theme: {
		colors: {
			current: {
				DEFAULT: 'currentColor'
			},

			light: {
				DEFAULT: '#fff',
				contrastTransparent: 'rgba(0, 0, 0, .5)',
				contrast: 'rgba(0, 0, 0, .85)',
			},
			neutral: {
				lighter: '#F0F0F0',
				DEFAULT: '#B2B5B7',
				darker: '#8F8F8F',
				darkest: '#4E4E4E',
				footer: '#0D1622',
				contrast: 'rgba(0, 0, 0, .85)',
				transparent: 'rgba(240, 240, 240, .95)',
				grey: '#8E969D'
			},
			standard: {
				DEFAULT: '#1B365D'
			},
			brand: {
				DEFAULT: '#8D172E',
				contrast: '#fff',
			},
			decorative: {
				DEFAULT: '#1B365D',
				contrast: '#fff',
			},
			transparent: {
				DEFAULT: 'transparent',
				contrast: '#fff',
			},
			warning: {
				DEFAULT: '#FF2424'
			},
			excite: {
				DEFAULT: '#A89379',
				contrast: '#fff'
			},
			experience: {
				DEFAULT: '#5E8AB4',
				contrast: '#fff'
			},
			lite: {
				DEFAULT: '#FF671F',
				contrast: '#fff'
			},
			service: {
				DEFAULT: '#D9D9D9',
				contrast: 'rgba(0, 0, 0, .85)',
				darker: '#8A9199',
				red: '#FF2424',
				lightGrey: '#999',
				border: '#1D1D1B'
			},
			slider: {
				grey: '#999999',
				lightGrey: '#8E969D',
				red: '#FF2424'
			},
			default: {
				DEFAULT: '#075289',
				contrast: '#fff'
			},
			nav: {
				logo: LOGO_HOVER,
				inactiveText: HEADER_ITEMS_INACTIVE
			}

		},
		fill: {
			current: {
				DEFAULT: 'currentColor'
			},
			brand: {
				DEFAULT: '#8D172E',
			},
			excite: {
				DEFAULT: '#A89379',
			},
			experience: {
				DEFAULT: '#5E8AB4',
			},
			lite: {
				DEFAULT: '#FF671F',
			},
			service: {
				DEFAULT: '#D9D9D9',
			}

		},

		fontFamily: {
			'sans-light': ['TradeGothicNextLight', 'Helvetica', 'Arial', 'sans-serif'],
			'sans-regular': ['TradeGothicNextRegular', 'Helvetica', 'Arial', 'sans-serif'],
			'sans-bold': ['TradeGothicNextBold', 'Helvetica', 'Arial', 'sans-serif'],
			'serif-regular': ['Times New Roman', 'Times', 'serif'],
			// @TODO ... korrekt Schrift fehlt noch, erst einmal nur DUMMY
			'fira-pro-light': ['TradeGothicNextLight', 'Helvetica', 'Arial', 'sans-serif'],
			'fira-pro-regular': ['TradeGothicNextRegular', 'Helvetica', 'Arial', 'sans-serif'],
			'fira-pro-bold': ['TradeGothicNextBold', 'Helvetica', 'Arial', 'sans-serif'],
		},

		screens: {
			/* Breakpoint to switch between fullscreen and aside-offcanvas. */
			offcanvas: '400px',
			/* Breakpoint to switch between inline navigation and  offcanvas. */
			menuToggle: '1300px',
			xs: '480px',
			'affiliates-map-toggle': '980px',
			/* Breakpoint to switch between accordeon and tabbed view of Block.TabBox.*/
			tabBoxToggle: '800px',
			...defaultTheme.screens
		},

		fontSize: {
			'largeHeadline': '42px',
			'extraLarge': '72px',
			'small': '16px',
			...defaultTheme.fontSize
		},

		lineHeight: {
			'largeHeadline': '46px',
			...defaultTheme.lineHeight
		},

		extend: {
			typography: (theme) => ({
				DEFAULT: {
					css: {
						color: theme('colors.light.contrast'),
						fontSize: '1.25rem',
						lineHeight: 1.6,
						strong: {
							fontFamily: theme('fontFamily.sans-bold')
						},
						'ul': {
							listStyleType: 'none',
							padding: '12px',
						},
						'ul li': {
							marginBottom: '.3em',
						},
						'ul li::before': {
							display: 'inline-block',
							width: '1em',
							marginLeft: '-1em',
							content: '"\u2013"',
							color: theme('colors.service.red'),
							fontWeight: 'bold',
						},
						p: {
							marginTop: 0,
						},
						'ol > li > :first-child': {
							margin: 0
						}
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
						'ul li::before': {
							backgroundColor: theme('colors.brand.contrast'),
						},
					},
				},
				'neutral-contrast': {
					css: {
						color: theme('colors.neutral.contrast'),
						strong: {
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
						'ul li::before': {
							backgroundColor: theme('colors.decorative.contrast'),
						},
					},
				},
				'transparent-contrast': {
					css: {
						color: theme('colors.transparent.contrast'),
						strong: {
							color: theme('colors.transparent.contrast'),
						},
						a: {
							color: theme('colors.transparent.contrast'),
						},
						'ul li::before': {
							backgroundColor: theme('colors.transparent.contrast'),
						},
					},
				},
				'excite': {
					css: {
						a: {
							color: theme('colors.excite.DEFAULT'),
						}
					},
				},
				'experience': {
					css: {
						a: {
							color: theme('colors.experience.DEFAULT'),
						}
					},
				},
				'lite': {
					css: {
						a: {
							color: theme('colors.lite.DEFAULT'),
						}
					},
				},
				'service': {
					css: {
						a: {
							color: theme('colors.service.contrast'),
						}
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
				'people-story': '25% 1fr',
				'2-33-auto': '30% 1fr',
				'2-160-auto': '160px auto',
				'2-slider-bubbles': '1fr 1fr',
				'slider-tabs': 'clamp(200px, 35%, 430px) 1fr',
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
				'content-snug': `min(calc(3/4 * ${MAX_CONTENT_WIDTH}), 100vw)`,
				'content-tight': `min(calc(2/3 * ${MAX_CONTENT_WIDTH}), 100vw)`,
				'10-sm': `calc(10 * ${SPACES.sm})`,
				'quotation': '1152px',
				touch: MIN_TOUCH,
				'small-text': `min(${HOMEPAGE_TEASER_TEXT_WIDTH}, 100vw)`,
			},

			width: {
				...SPACES,
				offcanvas: OFFCANVAS_WIDTH,
				touch: MIN_TOUCH,
			},

			height: {
				...SPACES,
				touch: MIN_TOUCH,
				header: HEADER_HEIGHT + 'px',
				nav: NAV_HEIGHT
			},

			space: {
				...SPACES,
			},

			margin: {
				...SPACES,
				'minus-contentcontainer': `calc(-1.25 * ${SPACE_SM})`,
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
				contentcontainer: SPACE_SM,
			},

			gap: {
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
				'space14': '14px',
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
				...SPACES,
				offcanvas: OFFCANVAS_WIDTH,
			},

			inset: {
				...SPACES,
				header: HEADER_HEIGHT,
				revertHeaderOffset: -HEADER_HEIGHT + 'px'
			},

			backgroundImage: {
				'sliderItem': 'linear-gradient(17.54deg, #000000 -7.11%, rgba(0, 0, 0, 0.0001) 47.28%);'
			},

			animation: {
				'fade-in-text': 'fadeIn 3s',
				'fade-out-text': 'fadeOut 3s',
				'fade-in-text-translate': 'fadeInWithTranslate 3s',
				'fade-out-text-translate': 'fadeOutWithTranslate 3s',
			},

			keyframes: {
				'fadeIn': {
					'0%': {
						opacity: 0,
					},
					'50%': {
						opacity: 0.35
					},
					'100%': {
						opacity: 1
					}
				},
				'fadeOut': {
					'100%': {
						opacity: 0,
					},
					'50%': {
						opacity: 0.35
					},
					'0%': {
						opacity: 1
					}
				},
				'fadeInWithTranslate': {
					'0%': {
						opacity: 0,
						transform: 'translateX(-50px)'
					},
					'50%': {
						opacity: 0.35
					},
					'100%': {
						opacity: 1,
					}
				},
				'fadeOutWithTranslate': {
					'100%': {
						opacity: 0,
						transform: 'translateX(-50px)'
					},
					'50%': {
						opacity: 0.35
					},
					'00%': {
						opacity: 1
					}
				}
			},

			aspectRatio: {
				'5/3': '5 / 3',
			}
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
				'.bg-triangle': {
					'height': (Math.tan(2 * Math.PI / 180) * 100).toFixed(3) + 'vw',
					'border-width': `0 0 ${(Math.tan(2 * Math.PI / 180) * 100).toFixed(3) + 'vw'} 100vw`,
					'border-color': `transparent transparent #1B365D transparent`,
				},
				'.bg-triangle-light': {
					'height': (Math.tan(2 * Math.PI / 180) * 100).toFixed(3) + 'vw',
					'border-width': `0 0 ${(Math.tan(2 * Math.PI / 180) * 100).toFixed(3) + 'vw'} 100vw`,
					'border-color': `transparent transparent #FFFFFF transparent`,
				},
				'.vertical-lr': {
					'writing-mode': 'vertical-lr'
				},
				'.nav-top-inset' : {
					'top': '0'
				},
				'.nav-bot-inset' : {
					'bottom': '0'
				},
				'.bg-header': {
					'background': 'white',
					'color':  HEADER_ITEMS_INACTIVE,
					'transition': 'background-color .5s'
				},
				'.bg-header-transparent': {
					'background': 'linear-gradient(180deg, #000000 0%, rgba(0, 0, 0, 0.937255) 6.08%, rgba(0, 0, 0, 0.866667) 12.31%, rgba(0, 0, 0, 0.788235) 18.7%, rgba(0, 0, 0, 0.705882) 25.25%, rgba(0, 0, 0, 0.623529) 31.96%, rgba(0, 0, 0, 0.541176) 38.83%, rgba(0, 0, 0, 0.454902) 45.88%, rgba(0, 0, 0, 0.372549) 53.08%, rgba(0, 0, 0, 0.294118) 60.46%, rgba(0, 0, 0, 0.219608) 68.02%, rgba(0, 0, 0, 0.152941) 75.75%, rgba(0, 0, 0, 0.0901961) 83.65%, rgba(0, 0, 0, 0.0392157) 91.73%, rgba(0, 0, 0, 0.0001) 100%)',
					'mix-blend-mode': 'normal',
					'opacity': '0.6'
				},
				'.bg-nav-card': {
					'background': 'linear-gradient(90deg, #FFFFFF 35.34%, rgba(255, 255, 255, 0.968) 79.74%, rgba(255, 255, 255, 0.93) 99.97%)',
				},
				'.bg-mobile-nav': {
					'background': 'linear-gradient(90deg, #FFFFFF 35.34%, rgba(255, 255, 255, 0.968) 68.67%, rgba(255, 255, 255, 0.93) 100%)',
				},
				'.press-overview-top-news-grid': {
					'display': 'grid',
					'grid-gap': '50px',
					'grid-template-columns': 'repeat(4, 1fr)',
					'grid-auto-rows': '1px',
				},
				'.press-overview-grid': {
					'display': 'grid',
					'grid-gap': '24px',
					'grid-template-columns': 'repeat(3, 1fr)',
					'grid-auto-rows': '1px',
				},
				'.press-overview-grid-tablet': {
					'display': 'grid',
					'grid-gap': '16px',
					'grid-template-columns': 'repeat(2, 1fr)',
					'grid-auto-rows': '1px',
				},
				'.press-overview-grid-mobile': {
					'display': 'grid',
					'grid-gap': '50px',
					'grid-template-columns': 'repeat(1, 1fr)',
					'grid-auto-rows': '1px',
				},
				'.bg-hanns': {
					'background': 'linear-gradient(90deg, #F1F0EE 35.34%, rgba(241, 240, 238, 0.968) 79.74%, rgba(241, 240, 238, 0.93) 99.97%)',
				},
				'.bg-stage-press': {
					'background': 'linear-gradient(90deg, #FFFFFF 35.34%, rgba(255, 255, 255, 0.968) 79.74%, rgba(255, 255, 255, 0.93) 99.97%)',
				},
				'.card-hyphens': {
					' hyphens': 'auto',
				}
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
