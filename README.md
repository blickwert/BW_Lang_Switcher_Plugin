# Custom Polylang Language Switcher

A custom WordPress plugin that provides a customizable language switcher for Polylang with shortcode support.

## Features

- **Shortcode Support**: Add a language switcher anywhere using `[bw-lang-switcher]`.
- **Layouts**: Choose between different layouts using the `layout` attribute (`flag`, `text-flag`, `flag-text`, `text`).
- **Customizable Styles**: Control the size of flags using the `width` and `height` shortcode attributes.
- **Admin Panel**: Easily manage flag images for each language using the WordPress media library.
- **Polylang Integration**: Automatically detects available languages and links them appropriately.

## Requirements

- WordPress 5.0 or higher
- Polylang plugin installed and activated

## Installation

1. Download the plugin and upload it to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure that Polylang is installed and activated.
4. Use the shortcode `[bw-lang-switcher]` to display the language switcher.

## Shortcode Options

| Attribute        | Description                                                                 | Default   |
|------------------|-----------------------------------------------------------------------------|-----------|
| `available-lang` | Show available languages (`true` or `false`).                              | `true`    |
| `width`          | Set the width of the flag images in pixels.                                | CSS-based |
| `height`         | Set the height of the flag images in pixels.                               | CSS-based |
| `layout`         | Choose the layout: `flag`, `text-flag`, `flag-text`, or `text`.            | `flag`    |

### Example Shortcodes

- **Default Layout**: `[bw-lang-switcher]`
- **Flags with Text Below**: `[bw-lang-switcher layout="flag-text"]`
- **Only Text**: `[bw-lang-switcher layout="text"]`
- **Custom Flag Size**: `[bw-lang-switcher width="32" height="20"]`

## Changelog

### Version 1.3.4
- Added support for `layout` shortcode attribute with options: `flag`, `text-flag`, `flag-text`, `text`.
- Improved admin settings page with media picker for flag images.
- Updated shortcode to include `width` and `height` attributes for flag sizing.

### Version 1.3.3
- Added inline CSS support for flag width and height via shortcode attributes.

### Version 1.3.2
- Replaced background images in `<a>` elements with `<img>` tags for better accessibility.

### Version 1.3.1
- Improved shortcode rendering logic.
- Added support for dynamic styles based on shortcode attributes.

### Version 1.3.0
- Initial release with basic Polylang integration and shortcode support.

## License

This project is licensed under the MIT License.

---

Developed with ❤️ by [Kreativbüro Blickwert](https://www.blickwert.at).
