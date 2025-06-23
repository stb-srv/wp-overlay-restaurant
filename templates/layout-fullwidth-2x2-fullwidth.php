<div class="pm-fullwidth pm-fullwidth--top">
<?php 
$top_content = get_post_meta( $post->ID, 'ffo_fullwidth_top', true );
if ( trim( $top_content ) !== '' ) {
    echo apply_filters( 'the_content', $top_content );
}
?>
</div>
<div class="pm-grid pm-grid--2x2">
<?php for ($i = 1; $i <= 4; $i++) : ?>
    <div class="pm-grid__item">
        <?php
        $heading = get_post_meta( $post->ID, "ffo_grid_heading_{$i}", true );
        if ( trim( $heading ) !== '' ) {
            echo '<h3 class="ffo-grid-heading">' . esc_html( $heading ) . '</h3>';
        }
        $item = get_post_meta( $post->ID, "ffo_grid_item_{$i}", true );
        if ( trim( $item ) !== '' ) {
            echo apply_filters( 'the_content', $item );
        }
        ?>
    </div>
<?php endfor; ?>
</div>
<div class="pm-fullwidth pm-fullwidth--bottom">
<?php 
$bottom_content = get_post_meta( $post->ID, 'ffo_fullwidth_bottom', true );
if ( trim( $bottom_content ) !== '' ) {
    echo apply_filters( 'the_content', $bottom_content );
}
?>
</div>
