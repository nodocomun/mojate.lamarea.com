<?php
$post_data = array();
if (!empty($meta_data[$args['key']]['url'])) {
    $post_data = array_filter($meta_data[$args['key']]['url']);
}
?>
<?php if (!empty($post_data)): ?>
    <div class="ptb_extra_video ptb_extra_grid  ptb_extra_columns_<?php echo $data['columns'] ?>">
        <?php foreach ($post_data as $index => $value): ?>
            <?php
            if (!$value) {
                continue;
            }
            $title = !empty($meta_data[$args['key']]['title'][$index]) ? esc_attr($meta_data[$args['key']]['title'][$index]) : '';
            $description = !empty($meta_data[$args['key']]['description'][$index]) ? esc_attr($meta_data[$args['key']]['description'][$index]) : '';
            ?>
            <div class="ptb_extra_item ptb_extra_audio_item">
                <h3 class="ptb_extra_audio_title"><?php echo $title ?></h3>
                <div class="ptb_extra_audio_overlay_wrap">
                    <audio controls><source src="<?php echo $value ?>"></audio>
                    <div class="ptb_extra_audio_description"><?php echo $description ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

