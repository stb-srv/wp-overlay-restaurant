<?php
/*
Plugin Name:       FreeFlexOverlay Builder
Plugin URI:        https://stb-srv.de/
Description:       Kombiniert modulare Page-Builder-Module (Fullwidth & 2×2 Grid) und mittig zentrierte Overlay-Suche ohne ACF Pro.
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
 * Autoinstallation of CMB2 on activation and admin_init
 */
register_activation_hook( __FILE__, 'ffo_maybe_install_cmb2' );
add_action( 'admin_init', 'ffo_maybe_install_cmb2', 1 );
function ffo_maybe_install_cmb2() {
    $init = plugin_dir_path( __FILE__ ) . 'includes/cmb2/init.php';
    if ( file_exists( $init ) ) {
        return;
    }
    $response = wp_remote_get(
        'https://api.github.com/repos/CMB2/CMB2/releases/latest',
        [ 'headers' => [ 'User-Agent' => 'WordPress/' . get_bloginfo('version') ] ]
    );
    if ( is_wp_error( $response ) ) {
        return;
    }
    $data = json_decode( wp_remote_retrieve_body( $response ), true );
    if ( empty( $data['tag_name'] ) ) {
        return;
    }
    $tag = sanitize_text_field( $data['tag_name'] );
    $zip_url = "https://github.com/CMB2/CMB2/archive/refs/tags/{$tag}.zip";
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/misc.php';
    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    $tmp = download_url( $zip_url );
    if ( is_wp_error( $tmp ) ) {
        return;
    }
    $dest = plugin_dir_path( __FILE__ ) . 'includes/';
    $unz = unzip_file( $tmp, $dest );
    @unlink( $tmp );
    if ( is_wp_error( $unz ) ) {
        return;
    }
    $source = $dest . "CMB2-{$tag}";
    $target = $dest . 'cmb2';
    if ( is_dir( $source ) ) {
        rename( $source, $target );
    }
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
