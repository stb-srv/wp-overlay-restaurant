<?php
/**
 * Fallback meta boxes when CMB2 is missing
 */
add_action( 'add_meta_boxes', 'ffo_add_fallback_metabox' );
function ffo_add_fallback_metabox() {
    add_meta_box( 'ffo_modules', __( 'Page Modules', 'freeflexoverlay' ), 'ffo_render_fallback_metabox', [ 'post', 'page', 'ffo_layout' ] );
}

function ffo_render_fallback_metabox( $post ) {
    wp_nonce_field( 'ffo_save_modules', 'ffo_modules_nonce' );
    $prefix = 'ffo_';
    $layout_pattern = get_post_meta( $post->ID, $prefix . 'layout_pattern', true );
    $modules = get_post_meta( $post->ID, $prefix . 'modules_group', true );
    if ( ! is_array( $modules ) ) {
        $modules = [];
    }
    ?>
    <p>
        <label for="ffo_layout_pattern"><?php esc_html_e( 'Layout Pattern', 'freeflexoverlay' ); ?></label>
        <select id="ffo_layout_pattern" name="ffo_layout_pattern">
            <option value="custom" <?php selected( $layout_pattern, 'custom' ); ?>><?php esc_html_e( 'Custom Modules', 'freeflexoverlay' ); ?></option>
            <option value="pattern2" <?php selected( $layout_pattern, 'pattern2' ); ?>><?php esc_html_e( 'Fullwidth – 2×2 – 2×2 – Fullwidth', 'freeflexoverlay' ); ?></option>
            <option value="fullwidth-2x2-fullwidth" <?php selected( $layout_pattern, 'fullwidth-2x2-fullwidth' ); ?>><?php esc_html_e( 'Fullwidth – 2×2 – Fullwidth', 'freeflexoverlay' ); ?></option>
            <option value="fullwidth" <?php selected( $layout_pattern, 'fullwidth' ); ?>><?php esc_html_e( 'Fullwidth Only', 'freeflexoverlay' ); ?></option>
            <option value="grid" <?php selected( $layout_pattern, 'grid' ); ?>><?php esc_html_e( '2×2 Grid Only', 'freeflexoverlay' ); ?></option>
        </select>
    </p>
    <div id="ffo-modules">
    <?php
    $i = 0;
    foreach ( $modules as $mod ) {
        ffo_render_single_module( $i, $mod );
        $i++;
    }
    ?>
    </div>
    <p><button type="button" id="ffo-add-module" class="button"><?php esc_html_e( 'Add Module', 'freeflexoverlay' ); ?></button></p>
    <div id="ffo-module-template" style="display:none;">
        <?php ffo_render_single_module( '__index__', [] ); ?>
    </div>

    <div id="ffo-fw2x2fw-fields">
        <p>
            <label><?php esc_html_e( 'Fullwidth Top', 'freeflexoverlay' ); ?><br/>
                <textarea name="ffo_fullwidth_top" rows="5" style="width:100%;"><?php echo esc_textarea( get_post_meta( $post->ID, $prefix . 'fullwidth_top', true ) ); ?></textarea>
            </label>
        </p>
        <?php for ( $j = 1; $j <= 4; $j++ ) : ?>
            <p>
                <label><?php printf( esc_html__( 'Grid Item %d', 'freeflexoverlay' ), $j ); ?><br/>
                    <textarea name="ffo_grid_item_<?php echo $j; ?>" rows="3" style="width:100%;"><?php echo esc_textarea( get_post_meta( $post->ID, $prefix . 'grid_item_' . $j, true ) ); ?></textarea>
                </label>
            </p>
        <?php endfor; ?>
        <p>
            <label><?php esc_html_e( 'Fullwidth Bottom', 'freeflexoverlay' ); ?><br/>
                <textarea name="ffo_fullwidth_bottom" rows="5" style="width:100%;"><?php echo esc_textarea( get_post_meta( $post->ID, $prefix . 'fullwidth_bottom', true ) ); ?></textarea>
            </label>
        </p>
    </div>
    <?php
}

function ffo_render_single_module( $index, $mod ) {
    ?>
    <div class="ffo-module">
        <p>
            <label><?php esc_html_e( 'Layout Type', 'freeflexoverlay' ); ?>
                <select class="ffo-layout-type" name="ffo_modules_group[<?php echo esc_attr( $index ); ?>][layout_type]">
                    <option value="fullwidth" <?php if ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'fullwidth' ) echo 'selected'; ?>><?php esc_html_e( 'Fullwidth', 'freeflexoverlay' ); ?></option>
                    <option value="grid" <?php if ( isset( $mod['layout_type'] ) && $mod['layout_type'] === 'grid' ) echo 'selected'; ?>><?php esc_html_e( '2×2 Grid', 'freeflexoverlay' ); ?></option>
                </select>
            </label>
            <a href="#" class="ffo-remove-module" style="margin-left:10px;"><?php esc_html_e( 'Remove', 'freeflexoverlay' ); ?></a>
        </p>
        <p class="ffo-field-fullwidth">
            <label><?php esc_html_e( 'Fullwidth Content', 'freeflexoverlay' ); ?><br/>
                <textarea name="ffo_modules_group[<?php echo esc_attr( $index ); ?>][full_content]" rows="5" style="width:100%;"><?php echo isset( $mod['full_content'] ) ? esc_textarea( $mod['full_content'] ) : ''; ?></textarea>
            </label>
        </p>
        <?php for ( $j = 1; $j <= 4; $j++ ) : ?>
            <p class="ffo-field-grid">
                <label><?php printf( esc_html__( 'Grid Item %d', 'freeflexoverlay' ), $j ); ?><br/>
                    <textarea name="ffo_modules_group[<?php echo esc_attr( $index ); ?>][grid_item_<?php echo $j; ?>]" rows="3" style="width:100%;"><?php echo isset( $mod['grid_item_' . $j] ) ? esc_textarea( $mod['grid_item_' . $j] ) : ''; ?></textarea>
                </label>
            </p>
        <?php endfor; ?>
    </div>
    <?php
}

add_action( 'save_post', 'ffo_save_fallback_metabox' );
function ffo_save_fallback_metabox( $post_id ) {
    if ( ! isset( $_POST['ffo_modules_nonce'] ) || ! wp_verify_nonce( $_POST['ffo_modules_nonce'], 'ffo_save_modules' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    $prefix = 'ffo_';
    if ( isset( $_POST['ffo_layout_pattern'] ) ) {
        update_post_meta( $post_id, $prefix . 'layout_pattern', sanitize_text_field( $_POST['ffo_layout_pattern'] ) );
    }
    if ( isset( $_POST['ffo_modules_group'] ) && is_array( $_POST['ffo_modules_group'] ) ) {
        $modules = [];
        foreach ( $_POST['ffo_modules_group'] as $module ) {
            $modules[] = [
                'layout_type'  => isset( $module['layout_type'] ) ? sanitize_text_field( $module['layout_type'] ) : '',
                'full_content' => isset( $module['full_content'] ) ? wp_kses_post( $module['full_content'] ) : '',
                'grid_item_1'  => isset( $module['grid_item_1'] ) ? wp_kses_post( $module['grid_item_1'] ) : '',
                'grid_item_2'  => isset( $module['grid_item_2'] ) ? wp_kses_post( $module['grid_item_2'] ) : '',
                'grid_item_3'  => isset( $module['grid_item_3'] ) ? wp_kses_post( $module['grid_item_3'] ) : '',
                'grid_item_4'  => isset( $module['grid_item_4'] ) ? wp_kses_post( $module['grid_item_4'] ) : '',
            ];
        }
        update_post_meta( $post_id, $prefix . 'modules_group', $modules );
    } else {
        delete_post_meta( $post_id, $prefix . 'modules_group' );
    }

    if ( isset( $_POST['ffo_layout_pattern'] ) && $_POST['ffo_layout_pattern'] === 'fullwidth-2x2-fullwidth' ) {
        $fields = array( 'fullwidth_top', 'grid_item_1', 'grid_item_2', 'grid_item_3', 'grid_item_4', 'fullwidth_bottom' );
        foreach ( $fields as $field ) {
            if ( isset( $_POST[ 'ffo_' . $field ] ) ) {
                update_post_meta( $post_id, $prefix . $field, wp_kses_post( $_POST[ 'ffo_' . $field ] ) );
            } else {
                delete_post_meta( $post_id, $prefix . $field );
            }
        }
    }
}
