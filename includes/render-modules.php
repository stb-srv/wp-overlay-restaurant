<?php
/**
 * Render modules
 */
function ffo_render_modules_shortcode( $atts = array() ) {
    global $post;
    $atts = shortcode_atts( array( 'id' => 0 ), $atts, 'free_flexio_modules' );
    $layout_post = $post;
    if ( ! empty( $atts['id'] ) ) {
        if ( is_numeric( $atts['id'] ) ) {
            $layout_post = get_post( (int) $atts['id'] );
            if ( ! $layout_post || $layout_post->post_type !== 'ffo_layout' ) {
                $layout_post = get_page_by_path( sanitize_title( $atts['id'] ), OBJECT, 'ffo_layout' );
            }
        } else {
            $layout_post = get_page_by_path( sanitize_title( $atts['id'] ), OBJECT, 'ffo_layout' );
        }
    }
    if ( ! ( $layout_post instanceof WP_Post ) ) {
        return '';
    }
    $prefix = 'ffo_';
    $output  = '';
    $pattern = get_post_meta( $layout_post->ID, $prefix . 'layout_pattern', true );
    $modules = get_post_meta( $layout_post->ID, $prefix . 'modules_group', true );

    if ( $pattern === 'fullwidth-2x2-fullwidth' ) {
        ob_start();
        $post = $layout_post;
        include FFO_DIR . 'templates/layout-fullwidth-2x2-fullwidth.php';
        $output .= ob_get_clean();
    } elseif ( $pattern && $pattern !== 'custom' && empty( $modules ) ) {
        // When no custom modules exist output simple placeholders for the
        // selected pattern. This keeps backward compatibility with older
        // behaviour where the pattern rendered static demo content.
        if ( in_array( $pattern, array( 'fullwidth-2x2-fullwidth', 'pattern1' ), true ) ) {
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
            $output .= '<div class="ffo-grid"><!-- 4 Items --></div>';
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
        } elseif ( $pattern === 'fullwidth' ) {
            $output .= '<div class="ffo-fullwidth">' . __( 'Standard Fullwidth-Content', 'freeflexoverlay' ) . '</div>';
        } elseif ( $pattern === 'grid' ) {
            $output .= '<div class="ffo-grid"><!-- 4 Items --></div>';
        }
    } else {
        // Render modules saved via the meta box. These will be used for
        // custom layouts and also override the placeholders of predefined
        // patterns when provided by the user.
        if ( ! empty( $modules ) && is_array( $modules ) ) {
            foreach ( $modules as $mod ) {
                if ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'fullwidth' ) {
                    $output .= '<div class="ffo-module ffo-fullwidth">' . apply_filters( 'the_content', $mod['full_content'] ) . '</div>';
                } elseif ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'grid' ) {
                    $output .= '<div class="ffo-module ffo-grid"><div class="ffo-row">';
                    for ( $i = 1; $i <= 4; $i++ ) {
                        $output .= '<div class="ffo-col">';
                        if ( ! empty( $mod['grid_heading_' . $i] ) ) {
                            $output .= '<h3 class="ffo-grid-heading">' . esc_html( $mod['grid_heading_' . $i] ) . '</h3>';
                        }
                        $output .= apply_filters( 'the_content', $mod['grid_item_' . $i] ) . '</div>';
                        if ( $i % 2 === 0 && $i < 4 ) {
                            $output .= '</div><div class="ffo-row">';
                        }
                    }
                    $output .= '</div></div>';
                }
            }
        }
    }
    // Previously an overlay search was appended here. The feature has been
    // removed so only the module markup is returned.
    return $output;
}
