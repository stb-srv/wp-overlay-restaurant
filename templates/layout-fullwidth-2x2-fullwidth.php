<div class="pm-fullwidth pm-fullwidth--top">
  <?= apply_filters('the_content', get_post_meta($post->ID,'fullwidth_top',true)); ?>
</div>
<div class="pm-grid pm-grid--2x2">
  <?php for($i=1;$i<=4;$i++): ?>
    <div class="pm-grid__item"><?= apply_filters('the_content', get_post_meta($post->ID,"grid_item_$i",true)); ?></div>
  <?php endfor; ?>
</div>
<div class="pm-fullwidth pm-fullwidth--bottom">
  <?= apply_filters('the_content', get_post_meta($post->ID,'fullwidth_bottom',true)); ?>
</div>
