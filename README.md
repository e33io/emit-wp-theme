# emit wp theme

an ultra-minimalistic blog theme for [WordPress](https://wordpress.org) - view a live example here: https://emit.e33.io

### Highlights and Details

- ultra-minimalistic design

- dark theme by default, light theme activated with `prefers-color-scheme: light` CSS

- single-column layout

- page/post navigation on home, single post, search and archives pages

- one full-width, and two half-width widget areas in the footer

- [Inter](https://rsms.me/inter) and [PlexMonoMod](https://git.sr.ht/~e33io/fonts/tree/main/item/PlexMonoMod) fonts

- Prism syntax highlighting for posted code blocks (see Note #1 below)

- PHP functions include:
	- Site Icon login logo
	- remove query strings from static resources
	- header cleanup to remove unneeded tags/links
	- disable WordPress emojis
	- delay RSS feeds (set to 1 hour)
	- set excerpt length
	- redirect attachment page to post
	- optimized [Contact Form 7](https://contactform7.com) CSS and JS (only loads on pages, not on home or posts)
	- optimized Comments CSS (only loads on posts/pages when comments are open)
	- optimized [Jetpack](https://wordpress.org/plugins/jetpack) CSS (only loads on posts/pages when Jetpack is installed)

- Twitter Card and Open Graph rich link previews

### Notes

1) posting code:
	- install the [Code Syntax Block](https://wordpress.org/plugins/code-syntax-block) plugin
	- pick the corresponding code language in the default Code Blocks in each blog post
	- for posting minified code in the Code Block, go to Advanced > Additional CSS Class(es), and add `minified`

### License
[BSD Zero Clause License](https://git.sr.ht/~e33io/emit-wp-theme/tree/main/item/LICENSE)
