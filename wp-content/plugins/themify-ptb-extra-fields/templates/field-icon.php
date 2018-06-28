<?php 
$icons = $meta_data[$args['key']];
 if (isset($icons['icon'])): ?>
    <?php
    $size = isset($data['size']) ? 'ptb_extra_icons_' . $data['size'] : '';
    $classes = array();
    $new_window = false;
    $classes[] = 'ptb_extra_icon_link';
    if(isset($data['icon_link']) ){
        if($data['icon_link'] === 'lightbox') {
            $classes[] = 'ptb_lightbox';
        } elseif ($data['icon_link'] === 'new_window') {
            $new_window = true;
        } 
    }
    $classes = implode(' ', $classes);
    ?>
    <ul class="ptb_extra_icons <?php echo $size; ?>">
        <?php foreach ($icons['icon'] as $key => $ic): ?>
            <li class="ptb_extra_icon">
                <?php $color = !empty($icons['color'][$key]) ? 'style="color:' . esc_attr($icons['color'][$key]) . ';"' : ''; ?>
                <?php if (!empty($icons['url'][$key])): ?>
                    <a <?php echo $color ?> class="<?php echo $classes ?>" <?php if ($new_window): ?>target="_blank"<?php endif; ?> href="<?php echo esc_url($icons['url'][$key]) ?>">
                        <i class="fa fa-<?php esc_attr_e($ic) ?>"></i>
                        <span class="ptb_extra_icon_label"><?php esc_attr_e($icons['label'][$key]) ?></span>
                    </a>
                <?php else: ?>
                    <i <?php echo $color ?> class="fa fa-<?php esc_attr_e($ic) ?>"></i>
                    <span <?php echo $color ?> class="ptb_extra_icon_label"><?php esc_attr_e($icons['label'][$key]) ?></span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
