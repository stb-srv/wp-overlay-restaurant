<?php
/*
Plugin Name:       wp-overlay-restaurant
Plugin URI:        https://stb-srv.de/
Description:       Kombiniert modulare Page-Builder-Module (Fullwidth & 2×2 Grid).
Version:           2.6.2
Author:            stb-srv
Author URI:        https://stb-srv.de/
License:           MIT
License URI:       https://opensource.org/licenses/MIT
Text Domain:       freeflexoverlay
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'FFO_VERSION', '2.6.2' );
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

add_filter( 'manage_ffo_layout_posts_columns', 'ffo_layout_add_shortcode_column' );
function ffo_layout_add_shortcode_column( $columns ) {
    $new = array();
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( 'title' === $key ) {
            $new['ffo_shortcode'] = __( 'Shortcode', 'freeflexoverlay' );
        }
    }
    return $new;
}

add_action( 'manage_ffo_layout_posts_custom_column', 'ffo_layout_render_shortcode_column', 10, 2 );
function ffo_layout_render_shortcode_column( $column, $post_id ) {
    if ( 'ffo_shortcode' === $column ) {
        echo '<code>[free_flexio_modules id="' . (int) $post_id . '"]</code>';
    }
}

add_action( 'wp_enqueue_scripts', 'ffo_enqueue_assets' );
function ffo_enqueue_assets() {
    wp_enqueue_style( 'freeflexoverlay-style', FFO_URL . 'assets/style.css', [], FFO_VERSION );
    // Overlay component assets
    wp_enqueue_style( 'ffo-overlay', FFO_URL . 'assets/overlay.css', [], FFO_VERSION );
    wp_enqueue_script( 'ffo-overlay', FFO_URL . 'assets/overlay.js', [], FFO_VERSION, true );
    // Previously a search script was enqueued here. It has been removed.
}

add_action( 'admin_enqueue_scripts', 'ffo_admin_assets' );
function ffo_admin_assets( $hook ) {
    if ( in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        wp_enqueue_script( 'freeflexoverlay-admin', FFO_URL . 'assets/admin.js', [ 'jquery' ], FFO_VERSION, true );
        wp_enqueue_style( 'freeflexoverlay-admin-style', FFO_URL . 'assets/admin.css', [], FFO_VERSION );
    }
}

/**
 * Populate default modules when a predefined pattern is chosen and no modules
 * are set yet.
 */
add_action( 'save_post', 'ffo_apply_pattern_defaults', 20 );
function ffo_apply_pattern_defaults( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $post_type = get_post_type( $post_id );
    if ( ! in_array( $post_type, array( 'post', 'page', 'ffo_layout' ), true ) ) {
        return;
    }

    $prefix  = 'ffo_';
    $pattern = get_post_meta( $post_id, $prefix . 'layout_pattern', true );
    $modules = get_post_meta( $post_id, $prefix . 'modules_group', true );

    if ( ! empty( $modules ) || ! $pattern || 'custom' === $pattern ) {
        return;
    }

    $defaults = array();
    if ( 'pattern1' === $pattern ) {
        $defaults = array(
            ffo_empty_module( 'fullwidth' ),
            ffo_empty_module( 'grid' ),
            ffo_empty_module( 'fullwidth' ),
        );
    } elseif ( 'pattern2' === $pattern ) {
        $defaults = array(
            ffo_empty_module( 'fullwidth' ),
            ffo_empty_module( 'grid' ),
            ffo_empty_module( 'grid' ),
            ffo_empty_module( 'fullwidth' ),
        );
    } elseif ( 'fullwidth' === $pattern ) {
        $defaults = array( ffo_empty_module( 'fullwidth' ) );
    } elseif ( 'grid' === $pattern ) {
        $defaults = array( ffo_empty_module( 'grid' ) );
    }

    if ( ! empty( $defaults ) ) {
        update_post_meta( $post_id, $prefix . 'modules_group', $defaults );
    }
}

/**
 * Helper to create an empty module array for a given layout type.
 */
function ffo_empty_module( $type ) {
    return array(
        'layout_type'  => $type,
        'full_content' => '',
        'grid_item_1'  => '',
        'grid_item_2'  => '',
        'grid_item_3'  => '',
        'grid_item_4'  => '',
    );
}

/**
 * Remove wrapping <p> tags added by wpautop for the 'myplugin/pm-grid' block.
 */
add_filter( 'render_block', 'ffo_pm_grid_remove_wpautop', 10, 2 );
function ffo_pm_grid_remove_wpautop( $block_content, $block ) {
    if ( isset( $block['blockName'] ) && 'myplugin/pm-grid' === $block['blockName'] ) {
        // Strip all outer <p> wrappers added by wpautop.
        while ( preg_match( '#^\s*<p>(.*)</p>\s*$#s', $block_content, $m ) ) {
            $block_content = $m[1];
        }
    }

    return $block_content;
}
