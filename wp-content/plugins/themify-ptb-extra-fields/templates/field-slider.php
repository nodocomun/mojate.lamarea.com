
<?php
if (!empty($meta_data[$args['key']])) {
    $js_data = array();
    foreach ($data as $key => $arg) {
        if (!is_array($arg)) {
            $js_data[$key] = $arg;
        } elseif (isset($arg[$lang])) {
            $js_data[$key] = $arg[$lang];
        }
    }
    $caption = !empty($data['captions']);
    ?>
    <ul data-slider="<?php esc_attr_e(base64_encode(json_encode($js_data))) ?>" class="ptb_extra_bxslider">
        <?php foreach ($meta_data[$args['key']]['url'] as $index => $slider): ?>
            <?php if ($slider): ?>
                <?php
                $title = !empty($meta_data[$args['key']]['title'][$index]) ? esc_attr($meta_data[$args['key']]['title'][$index]) : '';
                if ($caption) {
                    $title.=!empty($meta_data[$args['key']]['description'][$index]) ? (' - ' . esc_attr($meta_data[$args['key']]['description'][$index])) : '';
                }
                $video = !in_array(pathinfo($slider, PATHINFO_EXTENSION), array('png', 'jpg', 'gif', 'jpeg', 'bmp'), true);
                $slider = esc_url($slider);
                $link =!empty($meta_data[$args['key']]['link'][$index])?esc_url($meta_data[$args['key']]['link'][$index]):false;
                ?>
                <li>
                   
                    <?php if (!$video): ?>
                        <img class="ptb_extra_image" src="<?php echo $slider ?>" alt="<?php echo $title ?>" title="<?php echo $title ?>" />
                    <?php else: ?>
                        <?php
                        $remote = strpos($slider, 'vimeo.com') !== false || strpos($slider, 'youtu.be') !== false || strpos($slider, 'youtube.com') !== false;
                        ?>
                        <?php if ($remote): ?>
                            <?php
                            global $wp_embed;
                            echo $wp_embed->run_shortcode('[embed]' . $slider . '[/embed]');
                            ?>
                        <?php else: ?>
                            <video width="100%" controls><source src="<?php echo $slider ?>"></video>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($link):?>
                        <a class="ptb_extra_slider_link" href="<?php echo $link ?>"></a>
                    <?php endif;?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}