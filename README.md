# wp-overlay-restaurant

wp-overlay-restaurant is a small page builder. It ships a custom meta box powered by [CMB2](https://github.com/CMB2/CMB2) to create flexible fullwidth or grid modules on pages or posts. If CMB2 is missing, a lightweight fallback meta box is used instead.

## Features

- Add repeating modules to pages and posts (fullwidth sections or 2×2 grids)
- Choose between predefined layout patterns or build custom sequences
- Selecting a predefined pattern now auto-populates matching modules for quick editing
- Shortcode `[free_flexio_modules]` renders the modules
- Create reusable layouts in the **Overlay Layouts** menu and reference them via `[free_flexio_modules id="123"]` (or by slug, e.g. `[free_flexio_modules id="startseite"]`)
- Overlay layout lists in the admin now show the shortcode for each layout

## Requirements

The plugin works best with the CMB2 library. When CMB2 is not present a simplified built-in meta box is loaded. You can still install the CMB2 plugin or copy the library into `includes/cmb2/` so that `includes/cmb2/init.php` exists to use the full CMB2 interface.

## Installation

1. Upload the plugin folder to your WordPress installation and activate it.
2. Install and activate the CMB2 plugin if it isn't already present.
3. Version 2.7.0 bundles a fallback meta box so the plugin works even without CMB2.
4. Create a new entry under **Overlay Layouts** and configure modules via the **Page Modules** meta box.
5. Insert the shortcode `[free_flexio_modules id="123"]` on any page, replacing `123` with the layout ID. Slugs work as well, e.g. `[free_flexio_modules id="startseite"]`.


## License

This project is released under the MIT license. See `LICENSE` for details.

## Overlay Component

The `assets/overlay.js` and `assets/overlay.css` files provide a configurable overlay.
Call `showOverlay()` with your texts to display it. The `templates/overlay-template.html` file shows the data attributes you can use.
You can also attach the overlay to any element with `showOverlayFromElement(element)` which reads those attributes.

Example:
```html
<script>
showOverlay({
  overlayTitle: 'Titel',
  tile1Title: 'Überschrift 1',
  tile1Text: 'Kachel 1 Text',
  tile2Title: 'Überschrift 2',
  tile2Text: 'Kachel 2 Text',
  tile3Title: 'Überschrift 3',
  tile3Text: 'Kachel 3 Text',
  tile4Title: 'Überschrift 4',
  tile4Text: 'Kachel 4 Text',
  ctaText: 'Mehr erfahren',
  ctaUrl: '#'
});
</script>
```

### Styling the overlay

The overlay uses several CSS variables that can be overridden to control its
appearance. In particular the heading sizes can be changed via
`--overlay-title-size` and `--tile-title-size`. The tile text size is
controlled through `--tile-text-size`.

When calling `showOverlay()` you can also pass `overlayTitleSize`,
`tileTitleSize` and `tileTextSize` options (or set the corresponding
`data-*-size` attributes) to set these values for a single overlay. If you need
different heading sizes for each tile you can additionally pass
`tile1TitleSize` … `tile4TitleSize` (or `data-tile1-title-size` and so on).

You can also customise the colour and font for each tile title. Pass
`tile1TitleColor` … `tile4TitleColor` and `tile1TitleFont` …
`tile4TitleFont` (or use the corresponding `data-*` attributes) to override the
default styles individually.

The overlay container automatically adjusts its width for different
screen sizes. On narrow screens it becomes almost full width while on
large screens it shrinks a bit for a more balanced appearance. The tile
heights now grow with their content so text is no longer clipped. This
behaviour and the breakpoints can be customised in `assets/overlay.css`
if needed.
Font sizes now use CSS `clamp()` to adapt to the viewport and
iframes keep a 16:9 ratio so content remains readable on any device.

### Dark Mode

The default styles use light colours but the plugin supports both a body
class `dark-mode` and automatic detection via the
`prefers-color-scheme` media query. When a dark scheme is active the
overlay and module elements switch to darker backgrounds and lighter
text. If another dark‑mode plugin toggles the class the components will
adjust automatically. The admin interface respects the same class and
media query so module controls remain usable when a backend dark‑mode
plugin is active.
