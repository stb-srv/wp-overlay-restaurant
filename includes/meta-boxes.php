<?php
/**
 * Meta-boxes for flexible modules via CMB2
 */
add_action( 'cmb2_admin_init', 'ffo_register_module_metabox' );
function ffo_register_module_metabox() {
    $prefix = 'ffo_';
    $cmb = new_cmb2_box( array(
        'id'           => $prefix . 'modules',
        'title'        => __( 'Page Modules', 'freeflexoverlay' ),
        'object_types' => array( 'page', 'post', 'ffo_layout' ),
    ) );
    // Layout pattern select
    $cmb->add_field( array(
        'name'    => __( 'Layout-Muster', 'freeflexoverlay' ),
        'id'      => $prefix . 'layout_pattern',
        'type'    => 'select',
        'options' => array(
            'custom'                     => __( 'Custom Modules', 'freeflexoverlay' ),
            'pattern2'                   => __( 'Fullwidth – 2×2 – 2×2 – Fullwidth', 'freeflexoverlay' ),
            'fullwidth-2x2-fullwidth'    => __( 'Fullwidth – 2×2 – Fullwidth', 'freeflexoverlay' ),
            'fullwidth'                  => __( 'Fullwidth Only', 'freeflexoverlay' ),
            'grid'                       => __( '2×2 Grid Only', 'freeflexoverlay' ),
        ),
        'default' => 'custom',
    ) );
    // Module group
    $group_id = $cmb->add_field( array(
        'id'         => $prefix . 'modules_group',
        'type'       => 'group',
        'row_classes' => 'ffo-modules-group',
        'options'    => array(
            'group_title'   => __( 'Modul {#}', 'freeflexoverlay' ),
            'add_button'    => __( 'Hinzufügen', 'freeflexoverlay' ),
            'remove_button' => __( 'Entfernen', 'freeflexoverlay' ),
            'sortable'      => true,
        ),
    ) );
    // Fields within group
    $cmb->add_group_field( $group_id, array(
        'name'       => __( 'Layout-Typ', 'freeflexoverlay' ),
        'id'         => 'layout_type',
        'type'       => 'select',
        'attributes' => array( 'class' => 'ffo-layout-type' ),
        'options'    => array(
            'fullwidth' => __( 'Fullwidth', 'freeflexoverlay' ),
            'grid'      => __( '2×2 Grid', 'freeflexoverlay' ),
        ),
    ) );
    $cmb->add_group_field( $group_id, array(
        'name'        => __( 'Inhalt Fullwidth', 'freeflexoverlay' ),
        'id'          => 'full_content',
        'type'        => 'wysiwyg',
        'row_classes' => 'ffo-field-fullwidth',
        'options'     => array(
            'textarea_rows' => 5,
        ),
    ) );
    for ( $i = 1; $i <= 4; $i++ ) {
        $cmb->add_group_field( $group_id, array(
            'name'        => sprintf( __( 'Grid Item %d', 'freeflexoverlay' ), $i ),
            'id'          => 'grid_item_' . $i,
            'type'        => 'wysiwyg',
            'row_classes' => 'ffo-field-grid',
            'options'     => array(
                'textarea_rows' => 3,
            ),
        ) );
    }

    // Fields for the "fullwidth-2x2-fullwidth" pattern
    $cmb->add_field( array(
        'name'        => __( 'Fullwidth Top', 'freeflexoverlay' ),
        'id'          => $prefix . 'fullwidth_top',
        'type'        => 'wysiwyg',
        'row_classes' => 'ffo-fw2x2fw-field',
        'options'     => array( 'textarea_rows' => 5 ),
    ) );
    for ( $i = 1; $i <= 4; $i++ ) {
        $cmb->add_field( array(
            'name'        => sprintf( __( 'Grid Item %d', 'freeflexoverlay' ), $i ),
            'id'          => $prefix . 'grid_item_' . $i,
            'type'        => 'wysiwyg',
            'row_classes' => 'ffo-fw2x2fw-field',
            'options'     => array( 'textarea_rows' => 3 ),
        ) );
    }
    $cmb->add_field( array(
        'name'        => __( 'Fullwidth Bottom', 'freeflexoverlay' ),
        'id'          => $prefix . 'fullwidth_bottom',
        'type'        => 'wysiwyg',
        'row_classes' => 'ffo-fw2x2fw-field',
        'options'     => array( 'textarea_rows' => 5 ),
    ) );
}
