
<?php if (!empty($meta_data[$args['key']])): ?>
    <?php
    $class = array();
    $class[] = 'ptb_extra_columns_' . $data['columns'];
    $preview = isset($data['preview']);
    if ($preview) {
        $class[] = 'ptb_extra_video_preview';
    }
    $lightbox = isset($data['lightbox']);
    if ($lightbox) {
        $class[] = 'ptb_extra_lighbtox';
    }
    $class = implode(' ', $class);
    global $wp_embed;
    ?>
    <div class="ptb_extra_video ptb_extra_grid  <?php echo $class ?>">
        <?php foreach ($meta_data[$args['key']]['url'] as $index => $value): ?>
            <?php
            if (!$value) {
                continue;
            }
            $value = esc_url_raw($value);
            $embed = false;
            $title = !empty($meta_data[$args['key']]['title'][$index]) ? $meta_data[$args['key']]['title'][$index] : '';
            $description = !empty($meta_data[$args['key']]['description'][$index]) ? $meta_data[$args['key']]['description'][$index] : '';
            $remote = strpos($value, 'vimeo.com') !== false || strpos($value, 'youtu.be') !== false || strpos($value, 'youtube.com') !== false;
            if ($remote) {
                $ret = $preview ? 'hqthumb' : 'embed';
                $old_v = $value;
                $value = PTB_CMB_Video::parse_video_url($value, $ret);
                if ($value) {
                    $url = $value['url'] . '&autoplay=1';
                    $value = $value['data'];
                } else {
                    $embed = true;
                }
            }
            if ($lightbox) {
                $link = $remote && !$embed ? $url : admin_url('admin-ajax.php?action=ptb_extra_video&post_id=' . $meta_data['ID'] . '&k=' . $args['key'] . '&v=' . $index . '&embed=' . $embed);
            }
            ?>
            <div class="ptb_extra_item ptb_extra_video_item">
                <h3 class="ptb_extra_video_title"><?php echo $title ?></h3>
                <div class="ptb_extra_video_overlay_wrap">
                    <?php if (isset($link)): ?>
                        <a  href="<?php echo esc_url_raw($link) ?>" <?php if ($embed): ?>data-type="ajax"<?php endif; ?> title="<?php esc_attr_e($title) ?>" data-rel="lightcase:collection:<?php echo $args['key'] ?>" class="<?php if ($lightbox): ?>ptb_extra_lighbtox ptb_extra_video_lightbox<?php endif; ?> ptb_extra_video_overlay"></a>
                    <?php endif; ?>
                    <?php if ($preview && !$embed): ?>
                        <span <?php if (!$lightbox && $remote): ?>data-url="<?php echo $url ?>"<?php endif; ?> class="ptb_extra_play_icon<?php echo!$lightbox ? ' ptb_extra_show_video' : '' ?>"><i class="fa fa-play"></i></span>
                    <?php endif; ?>
                    <?php if (!$remote): ?>
                        <video <?php if (!$preview): ?>controls<?php endif; ?>>
                            <source src="<?php echo $value ?>">
                        </video>
                    <?php else: ?>
                        <?php if ($preview && !$embed): ?>
                            <img class="ptb_image" src="<?php echo $value ?>" alt="<?php esc_attr_e($title) ?>" title="<?php esc_attr_e($title) ?>" />
                        <?php else: ?>
                            <div class="fluid-width-video-wrapper">
                                <?php echo $wp_embed->run_shortcode('[embed]' . $old_v . '[/embed]'); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="ptb_extra_video_description"><?php echo $description ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
