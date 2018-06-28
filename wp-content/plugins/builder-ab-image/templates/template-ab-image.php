<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template A/B Image
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title_image_compare' => '',
        'style_image' => '',
        'url_image_a' => '',
        'url_image_b' => '',
        'title_image' => '',
        'image_size_image_compare' => '',
        'width_image_compare' => '',
        'height_image_compare' => '',
        'orientation_compare' => 'horizontal',
        'css_ab_image' => '',
        'animation_effect' => ''
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);
    $image_alt = esc_attr($fields_args['title_image']);

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, $fields_args['css_ab_image'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    if (Themify_Builder_Model::is_img_php_disabled()) {
        // get image preset
        global $_wp_additional_image_sizes;
        $preset = themify_builder_get('setting-global_feature_size', 'image_global_size_field');
        if (isset($_wp_additional_image_sizes[$preset]) && $image_size_image !== '') {
            $width_image_compare = (int) $_wp_additional_image_sizes[$preset]['width'];
            $height_image_compare = (int) $_wp_additional_image_sizes[$preset]['height'];
        } else {
            $width_image_compare = $fields_args['width_image_compare'] !== '' ? $fields_args['width_image_compare'] : get_option($preset . '_size_w');
            $height_image_compare = $fields_args['height_image_compare'] !== '' ? $fields_args['height_image_compare'] : get_option($preset . '_size_h');
        }
        $image = '<img src="' . esc_url($fields_args['url_image_a']) . '" alt="' . $image_alt . '" width="' . $fields_args['width_image_compare'] . '" height="' . $fields_args['height_image_compare'] . '"/>';
        $image2 = '<img src="' . esc_url($fields_args['url_image_b']) . '" alt="' . $image_alt . '" width="' . $fields_args['width_image_compare'] . '" height="' . $fields_args['height_image_compare'] . '"/>';
    } else {
        $image = themify_get_image('src=' . esc_url($fields_args['url_image_a']) . '&w=' . $fields_args['width_image_compare'] . '&h=' . $fields_args['height_image_compare'] . '&alt=' . $image_alt . '&ignore=true');
        $image2 = themify_get_image('src=' . esc_url($fields_args['url_image_b']) . '&w=' . $fields_args['width_image_compare'] . '&h=' . $fields_args['height_image_compare'] . '&alt=' . $image_alt . '&ignore=true');
    }

    $max_width_style = ( $fields_args['width_image_compare'] !== '' ) ? "style='max-width: {$fields_args['width_image_compare']}px;'" : '';

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module a/b image -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ($fields_args['mod_title_image_compare'] !== ''): ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_image_compare'], $fields_args) . $fields_args['after_title']; ?>
        <?php endif; ?>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <div id="ab-image-<?php echo $module_ID; ?>" class="twentytwenty-container ab-image" data-orientation="<?php echo $fields_args['orientation_compare']; ?>" <?php echo $max_width_style; ?>>
            <?php echo $image; ?>
            <?php echo $image2; ?>
        </div>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module a/b image -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>