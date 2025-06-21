<?php
/*
Plugin Name:       wp-overlay-restaurant
Plugin URI:        https://stb-srv.de/
Description:       Kombiniert modulare Page-Builder-Module (Fullwidth & 2×2 Grid) und mittig zentrierte Overlay-Suche.
Version:           2.1.0
Author:            stb-srv
Author URI:        https://stb-srv.de/
License:           MIT
License URI:       https://opensource.org/licenses/MIT
Text Domain:       freeflexoverlay
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if CMB2 is available and, if not, display an admin notice.
 *
 * Prior versions tried to fetch CMB2 from GitHub automatically. In
 * restricted environments that behaviour caused failures. The plugin
 * now simply alerts administrators when CMB2 is missing so they can
 * install it manually (either as a separate plugin or by placing the
 * library inside <code>includes/cmb2/</code>).
 */
add_action( 'admin_notices', 'ffo_check_cmb2_notice' );
function ffo_check_cmb2_notice() {
    if ( defined( 'CMB2_LOADED' ) || class_exists( 'CMB2' ) ) {
        return;
    }
    $init = plugin_dir_path( __FILE__ ) . 'includes/cmb2/init.php';
    if ( file_exists( $init ) ) {
        require_once $init;
        return;
    }
    echo '<div class="notice notice-warning"><p>' .
        esc_html__(
            'FreeFlexOverlay Builder requires the CMB2 library. ' .
            'Please install the CMB2 plugin or place it in the plugin\'s includes/cmb2 directory.',
            'freeflexoverlay'
        ) .
        '</p></div>';
}

/**
 * Initialization: load CMB2, meta boxes, render modules, shortcode
 */
add_action( 'init', 'ffo_init_plugin', 5 );
function ffo_init_plugin() {
    $init = plugin_dir_path( __FILE__ ) . 'includes/cmb2/init.php';
    if ( file_exists( $init ) ) {
        require_once $init;
    }
    if ( class_exists( 'CMB2' ) && function_exists( 'new_cmb2_box' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/render-modules.php';
        if ( function_exists( 'ffo_render_modules_shortcode' ) ) {
            add_shortcode( 'free_flexio_modules', 'ffo_render_modules_shortcode' );
        }
    }
}

/**
 * Enqueue assets
 */
add_action( 'wp_enqueue_scripts', 'ffo_enqueue_assets' );
function ffo_enqueue_assets() {
    wp_enqueue_style(
        'freeflexoverlay-style',
        plugin_dir_url( __FILE__ ) . 'assets/style.css',
        [],
        '2.1.0'
    );
    wp_enqueue_script(
        'freeflexoverlay-script',
        plugin_dir_url( __FILE__ ) . 'assets/script.js',
        [ 'jquery' ],
        '2.1.0',
        true
    );
}
