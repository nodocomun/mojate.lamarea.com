<?php
if (!empty($meta_data[$args['key']])) {

    $lightbox = isset($data['file_link']) && $data['file_link'] === 'lightbox';
    $length = count($meta_data[$args['key']]['url']) - 1;
    $disable_lightbox = apply_filters('ptb_extra_disable_file_lightbox', array('zip', 'doc', 'docx', 'xls', 'xlsx', 'xlsm', 'tar', 'gzip', '7z'));
    $new_window = !$lightbox && isset($data['file_link']) && $data['file_link'] === 'new_window';
    $show_icons = !empty($data['show_icons']);
    $color = $show_icons && !empty($data['color']) ? $data['color'] : false;
    $show_as = !empty($data['show_as']) ? $data['show_as'] : 'l';
    ?>
    <ul  class="ptb_extra_files ptb_extra_files_<?php echo $show_as ?>">
        <?php foreach ($meta_data[$args['key']]['url'] as $index => $file): ?>
            <?php if ($file): ?>
                <?php
                $title = !empty($meta_data[$args['key']]['title'][$index]) ? esc_attr($meta_data[$args['key']]['title'][$index]) : '';
                $file = esc_url($file);
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                $class = array();
                if ($lightbox && !in_array($ext, $disable_lightbox, true)) {
                    $class[] = 'ptb_lightbox';
                }
                if ($show_icons) {
                    $class[] = 'fa ptb_extra_file_icons ptb_extra_' . $ext;
                }
                ?>
                <li>
                    <a <?php if ($color): ?>style="color:<?php echo $color ?>"<?php endif; ?><?php if ($new_window): ?>target="_blank"<?php endif; ?> <?php echo!empty($class) ? 'class="' . implode(' ', $class) . '"' : '' ?> href="<?php echo $file ?>"><span><?php echo $title ?></span></a><?php if ($index != $length): ?>, <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php
}