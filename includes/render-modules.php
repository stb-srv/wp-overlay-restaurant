<?php
/**
 * Render modules and overlay search
 */
function ffo_render_modules_shortcode( $atts = array() ) {
    global $post;
    $atts = shortcode_atts( array( 'id' => 0 ), $atts, 'free_flexio_modules' );
    $layout_post = $post;
    if ( ! empty( $atts['id'] ) ) {
        $layout_post = get_post( (int) $atts['id'] );
    }
    if ( ! ( $layout_post instanceof WP_Post ) ) {
        return '';
    }
    $prefix = 'ffo_';
    $output = '';
    $pattern = get_post_meta( $layout_post->ID, $prefix . 'layout_pattern', true );
    if ( $pattern && $pattern !== 'custom' ) {
        if ( $pattern === 'pattern1' ) {
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
            $output .= '<div class="ffo-grid"><!-- 4 Items --></div>';
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
        } elseif ( $pattern === 'fullwidth' ) {
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
        } elseif ( $pattern === 'grid' ) {
            $output .= '<div class="ffo-grid"><!-- 4 Items --></div>';
        }
    } else {
        $modules = get_post_meta( $layout_post->ID, $prefix . 'modules_group', true );
        if ( ! empty( $modules ) ) {
            foreach ( $modules as $mod ) {
                if ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'fullwidth' ) {
                    $output .= '<div class="ffo-module ffo-fullwidth">' . apply_filters( 'the_content', $mod['full_content'] ) . '</div>';
                } elseif ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'grid' ) {
                    $output .= '<div class="ffo-module ffo-grid"><div class="ffo-row">';
                    for ( $i = 1; $i <= 4; $i++ ) {
                        $output .= '<div class="ffo-col">' . apply_filters( 'the_content', $mod['grid_item_' . $i] ) . '</div>';
                        if ( $i % 2 === 0 && $i < 4 ) {
                            $output .= '</div><div class="ffo-row">';
                        }
                    }
                    $output .= '</div></div>';
                }
            }
        }
    }
    // Overlay Search Container
    $output .= '<div class="ffo-search-wrapper">';
    $output .= '<input type="text" id="ffo-search-input" placeholder="' . esc_attr__( 'Suche…', 'freeflexoverlay' ) . '">';
    $output .= '<div id="ffo-search-overlay"><button id="ffo-overlay-close">&times;</button><div id="ffo-search-results"></div></div>';
    $output .= '<div id="ffo-search-source" style="display:none;">';
    $items = array( 'Margherita', 'Pepperoni', 'Caesar Salad', 'Greek Salad' );
    foreach ( $items as $it ) {
        $output .= '<div class="ffo-item"><h3 class="ffo-item-title">' . esc_html( $it ) . '</h3></div>';
    }
    $output .= '</div></div>';
    return $output;
}
