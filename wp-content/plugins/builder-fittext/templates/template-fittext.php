<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template FitText
 * 
 * Access original fields: $mod_settings
 * @author Themify
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'fittext_text' => '',
        'fittext_link' => '',
        'fittext_params' => '',
        'font_family' => '',
        'add_css_fittext' => '',
        'js_params' => array(),
        'animation_effect' => ''
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);
    $fittext_params = array_values( explode( '|', $fields_args['fittext_params'] ) );

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, $fields_args['add_css_fittext'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

    $link_target = $link_class = false;
    if (in_array('lightbox', $fittext_params, true)) {
        $link_class = 'themify_lightbox';
    } elseif (in_array('newtab', $fittext_params, true)) {
        $link_target = '_blank';
    }

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module fittext -->
    <div <?php echo self::get_element_attributes($container_props); ?> data-font-family="<?php echo $fields_args['font_family']; ?>">

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <?php if ('' !== $fields_args['fittext_link']) : ?>
            <a href="<?php echo $fields_args['fittext_link']; ?>"<?php if ($link_class !== false): ?> class="<?php echo $link_class; ?>"<?php endif; ?><?php if ($link_target !== false): ?> target="<?php echo $link_target; ?><?php endif; ?>">
            <?php endif; ?>

            <span><?php echo $fields_args['fittext_text']; ?></span>

            <?php if ('' !== $fields_args['fittext_link']) : ?>
            </a>
        <?php endif; ?>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module fittext -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>