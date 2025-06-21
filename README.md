# wp-overlay-restaurant

wp-overlay-restaurant combines a small page builder with an overlay search. It ships a custom meta box powered by [CMB2](https://github.com/CMB2/CMB2) to create flexible fullwidth or grid modules on pages or posts. If CMB2 is missing, a lightweight fallback meta box is used instead. A simple JavaScript powered overlay search is included to search through a small data set which can be replaced with your own items.

## Features

- Add repeating modules to pages and posts (fullwidth sections or 2×2 grids)
- Choose between predefined layout patterns or build custom sequences
- Shortcode `[free_flexio_modules]` renders the modules and the overlay search
- Overlay search shows results in a centered modal

## Requirements

The plugin works best with the CMB2 library. When CMB2 is not present a simplified built-in meta box is loaded. You can still install the CMB2 plugin or copy the library into `includes/cmb2/` so that `includes/cmb2/init.php` exists to use the full CMB2 interface.

## Installation

1. Upload the plugin folder to your WordPress installation and activate it.
2. Install and activate the CMB2 plugin if it isn't already present.
3. Version 2.4.0 bundles a fallback meta box so the plugin works even without CMB2.
4. Create or edit a page/post and configure modules via the **Page Modules** meta
   box.
5. Insert the shortcode `[free_flexio_modules]` into the content where the
   modules and search should appear.

## Customising the search

Edit `assets/script.js` to replace the sample array of items with your own data or modify the overlay markup inside `includes/render-modules.php`.

## License

This project is released under the MIT license. See `LICENSE` for details.
