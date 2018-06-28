<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Template Timeline
 * 
 * Access original fields: $mod_settings
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title_timeline' => '',
        'source_timeline' => 'post',
        'template_timeline' => 'graph',
        'add_css_timeline' => '',
        'animation_effect' => '',
    );
    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, 'layout-' . $fields_args['template_timeline'],$fields_args['add_css_timeline'] 
                    ), $mod_name, $module_ID, $fields_args)
    );

// get items
    $fields_args['items'] = Builder_Timeline::get_instance()->get_source($fields_args['source_timeline'])->get_items($fields_args);

    /* #3154 hack, if using Descending order for posts, make the Graph layout start at the end */
    if (isset($fields_args['order_post_timeline']) && $fields_args['order_post_timeline'] === 'desc') {
        $fields_args['start_at_end'] = true;
    }

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>
    <!-- module timeline -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ($fields_args['mod_title_timeline'] !== ''): ?>
            <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_timeline'] , $fields_args). $fields_args['after_title']; ?>
        <?php endif; ?>

        <?php do_action('themify_builder_before_template_content_render'); ?>
        <?php 
        if(!empty($fields_args['items'])){
        
            // render the template
            $fields_args['items'] = apply_filters( 'themify_builder_timeline_'.$fields_args['template_timeline'] .'_items', $fields_args['items'], $fields_args );
            self::retrieve_template('template-' . $mod_name . '-' . $fields_args['template_timeline']  . '.php', array(
                'module_ID' => $module_ID,
                'mod_name' => $mod_name,
                'settings' => $fields_args
                    ), '', '', true);
        }
        ?>
    <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module timeline -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>