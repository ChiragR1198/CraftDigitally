# CraftDigitally WordPress Theme

A modern, professional WordPress theme for digital marketing agencies. Built with clean code, full plugin compatibility, and beautiful UI/UX.

## Features

- ✅ **Fully WordPress Compatible** - Works with all standard WordPress features
- ✅ **Plugin Support** - Compatible with popular WordPress plugins
- ✅ **WooCommerce Ready** - Full e-commerce support
- ✅ **Gutenberg Compatible** - Works perfectly with block editor
- ✅ **SEO Optimized** - Clean, semantic HTML5 markup
- ✅ **Responsive Design** - Mobile-first approach
- ✅ **Custom Menus** - Multiple menu locations
- ✅ **Widget Areas** - Sidebar and footer widget areas
- ✅ **Custom Logo Support** - Easy branding
- ✅ **Translation Ready** - Full i18n support
- ✅ **Customizer Options** - Theme customization via WordPress Customizer

## Installation

1. Upload the `CraftDigitally` folder to `/wp-content/themes/`
2. Activate the theme through the 'Appearance > Themes' menu in WordPress
3. Go to 'Appearance > Customize' to configure theme settings
4. Set up your menus at 'Appearance > Menus'
5. Configure widgets at 'Appearance > Widgets'

## Theme Structure

```
CraftDigitally/
├── assets/
│   ├── css/
│   │   ├── editor-style.css
│   │   └── woocommerce.css
│   ├── images/
│   ├── js/
│   │   ├── main.js
│   │   └── customizer.js
│   └── fonts/
├── inc/
│   ├── customizer.php
│   ├── template-functions.php
│   ├── template-tags.php
│   └── woocommerce.php
├── patterns/
├── styles/
├── 404.php
├── archive.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── search.php
├── searchform.php
├── sidebar.php
├── single.php
├── style.css
├── theme.json
└── woocommerce.php
```

## Template Files

- **front-page.php** - Homepage template with all sections
- **page.php** - Single page template
- **single.php** - Single post template
- **archive.php** - Archive/category/tag template
- **search.php** - Search results template
- **404.php** - Error page template
- **woocommerce.php** - WooCommerce pages template

## Menu Locations

The theme supports three menu locations:

1. **Primary Menu** - Main navigation in header
2. **Footer Menu** - Footer navigation links
3. **Footer Social Menu** - Social media links in footer

Configure menus at: `Appearance > Menus`

## Widget Areas

1. **Sidebar** - Main sidebar widget area
2. **Footer Widget Area 1** - First footer widget column
3. **Footer Widget Area 2** - Second footer widget column

## Customizer Options

Access theme options at: `Appearance > Customize`

- Site Identity (Logo, Title, Tagline)
- Colors (Custom color palette)
- Typography (Font settings)
- Theme Options (Hero section visibility)
- Widgets
- Menus

## Plugin Compatibility

### Tested with:

- ✅ **WooCommerce** - Full e-commerce functionality
- ✅ **Contact Form 7** - Contact forms
- ✅ **Yoast SEO** - SEO optimization
- ✅ **Elementor** - Page builder
- ✅ **WPForms** - Advanced forms
- ✅ **Jetpack** - WordPress.com features
- ✅ **All In One SEO** - SEO optimization
- ✅ **WP Rocket** - Performance optimization
- ✅ **Akismet** - Spam protection
- ✅ **Classic Editor** - Classic editing experience

## Customization

### Custom Colors

Edit colors in `theme.json` or use WordPress Customizer.

### Custom Fonts

Google Fonts are loaded in `functions.php`. To change fonts:

1. Update the Google Fonts URL in `craftdigitally_scripts()` function
2. Update CSS variables in `style.css`
3. Update `theme.json` font families

### Custom Layouts

All section content can be customized by:

1. Using WordPress Customizer
2. Editing template files
3. Creating child theme
4. Using hooks and filters

## Hooks & Filters

The theme includes standard WordPress hooks:

- `after_setup_theme`
- `wp_enqueue_scripts`
- `widgets_init`
- `body_class`
- `excerpt_length`
- `excerpt_more`

## Performance

- Minimal CSS/JS
- Optimized images
- Lazy loading support
- Clean, semantic HTML
- No jQuery dependency for main functionality

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Developer Documentation

### Creating Child Theme

```css
/*
Theme Name: CraftDigitally Child
Template: CraftDigitally
*/
@import url('../CraftDigitally/style.css');
```

### Adding Custom Functions

Use child theme `functions.php` or create a custom plugin.

### Template Hierarchy

The theme follows WordPress template hierarchy:
https://developer.wordpress.org/themes/basics/template-hierarchy/

## Support

For theme support and documentation, visit:
https://craftdigitally.com

## Changelog

### Version 1.0.0
- Initial release
- Full WordPress compatibility
- Plugin support
- WooCommerce integration
- Responsive design
- Customizer options

## Credits

- Google Fonts: https://fonts.google.com
- WordPress: https://wordpress.org
- Theme developed by: CraftDigitally Team

## License

This theme is licensed under the GPL v2 or later.

```
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

---

**Made with ❤️ by CraftDigitally**

