<?php
/*
Plugin Name:       wp-overlay-restaurant
Plugin URI:        https://stb-srv.de/
Description:       Kombiniert modulare Page-Builder-Module (Fullwidth & 2×2 Grid) und mittig zentrierte Overlay-Suche.
Version:           2.5.0
Author:            stb-srv
Author URI:        https://stb-srv.de/
License:           MIT
License URI:       https://opensource.org/licenses/MIT
Text Domain:       freeflexoverlay
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FFO_VERSION', '2.5.0' );
define( 'FFO_DIR', plugin_dir_path( __FILE__ ) );
define( 'FFO_URL', plugin_dir_url( __FILE__ ) );

register_activation_hook( __FILE__, 'ffo_check_cmb2_on_activation' );
function ffo_check_cmb2_on_activation() {
    if ( ! ffo_cmb2_available() ) {
        add_option( 'ffo_cmb2_missing', 1 );
    }
}

add_action( 'admin_notices', 'ffo_admin_cmb2_notice' );
function ffo_admin_cmb2_notice() {
    if ( get_option( 'ffo_cmb2_missing' ) ) {
        echo '<div class="notice notice-warning"><p>' .
            esc_html__( 'WP Overlay Restaurant requires the CMB2 plugin. Please install and activate CMB2 or place it in the plugin\'s includes/cmb2 directory.', 'freeflexoverlay' ) .
            '</p></div>';
    }
}

function ffo_cmb2_available() {
    return class_exists( 'CMB2' ) || defined( 'CMB2_LOADED' ) || file_exists( FFO_DIR . 'includes/cmb2/init.php' );
}

add_action( 'plugins_loaded', 'ffo_init_plugin' );
function ffo_init_plugin() {
    if ( ffo_cmb2_available() ) {
        if ( ! ( class_exists( 'CMB2' ) || defined( 'CMB2_LOADED' ) ) && file_exists( FFO_DIR . 'includes/cmb2/init.php' ) ) {
            require_once FFO_DIR . 'includes/cmb2/init.php';
        }

        delete_option( 'ffo_cmb2_missing' );

        require_once FFO_DIR . 'includes/meta-boxes.php';
    } else {
        require_once FFO_DIR . 'includes/fallback-meta-boxes.php';
    }

    require_once FFO_DIR . 'includes/render-modules.php';
    add_shortcode( 'free_flexio_modules', 'ffo_render_modules_shortcode' );
}

add_action( 'init', 'ffo_register_layout_cpt' );
function ffo_register_layout_cpt() {
    $labels = array(
        'name'               => __( 'Overlay Layouts', 'freeflexoverlay' ),
        'singular_name'      => __( 'Overlay Layout', 'freeflexoverlay' ),
        'add_new'            => __( 'Add Layout', 'freeflexoverlay' ),
        'add_new_item'       => __( 'Add New Layout', 'freeflexoverlay' ),
        'edit_item'          => __( 'Edit Layout', 'freeflexoverlay' ),
        'new_item'           => __( 'New Layout', 'freeflexoverlay' ),
        'view_item'          => __( 'View Layout', 'freeflexoverlay' ),
        'search_items'       => __( 'Search Layouts', 'freeflexoverlay' ),
        'not_found'          => __( 'No layouts found.', 'freeflexoverlay' ),
        'not_found_in_trash' => __( 'No layouts found in Trash.', 'freeflexoverlay' ),
    );

    $args = array(
        'labels'       => $labels,
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'supports'     => array( 'title' ),
    );
    register_post_type( 'ffo_layout', $args );
}

add_action( 'wp_enqueue_scripts', 'ffo_enqueue_assets' );
function ffo_enqueue_assets() {
    wp_enqueue_style( 'freeflexoverlay-style', FFO_URL . 'assets/style.css', [], FFO_VERSION );
    wp_enqueue_script( 'freeflexoverlay-script', FFO_URL . 'assets/script.js', [ 'jquery' ], FFO_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'ffo_admin_assets' );
function ffo_admin_assets( $hook ) {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        wp_enqueue_script( 'freeflexoverlay-admin', FFO_URL . 'assets/admin.js', [ 'jquery' ], FFO_VERSION, true );
        wp_enqueue_style( 'freeflexoverlay-admin-style', FFO_URL . 'assets/admin.css', [], FFO_VERSION );
    }
}
