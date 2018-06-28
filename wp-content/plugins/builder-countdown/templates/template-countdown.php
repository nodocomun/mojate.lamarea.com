<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Template Countdown
 * 
 * Access original fields: $mod_settings
 */
if (TFCache::start_cache($mod_name, self::$post_id, array('ID' => $module_ID))):
    $fields_default = array(
        'mod_title_countdown' => '',
        'mod_date_countdown' => '',
        'done_action_countdown' => '',
        'content_countdown' => '',
        'redirect_countdown' => '',
        'color_countdown' => '',
        'label_days' => __('Days', 'builder-countdown'),
        'label_hours' => __('Hours', 'builder-countdown'),
        'label_minutes' => __('Minutes', 'builder-countdown'),
        'label_seconds' => __('Seconds', 'builder-countdown'),
        'add_css_countdown' => '',
        'counter_background_color' => '',
        'animation_effect' => '',
    );

    $fields_args = wp_parse_args($mod_settings, $fields_default);
    unset($mod_settings);
    $animation_effect = self::parse_animation_effect($fields_args['animation_effect'], $fields_args);

    $container_class = implode(' ', apply_filters('themify_builder_module_classes', array(
        'module', 'module-' . $mod_name, $module_ID, $fields_args['add_css_countdown'], $animation_effect
                    ), $mod_name, $module_ID, $fields_args)
    );

// get target date based on user timezone
    $epoch = strtotime($fields_args['mod_date_countdown'] . ' ' . get_option('timezone_string'));
    $next_year = strtotime('+1 year');

    $container_props = apply_filters('themify_builder_module_container_props', array(
        'id' => $module_ID,
        'class' => $container_class
            ), $fields_args, $mod_name, $module_ID);
    ?>

    <!-- module countdown -->
    <div <?php echo self::get_element_attributes($container_props); ?>>

        <?php if ('' !== $fields_args['counter_background_color']) : ?>
            <style type="text/css" scoped>#<?php echo $module_ID; ?> .ui { background-color: <?php echo Themify_Builder_Stylesheet::get_rgba_color($fields_args['counter_background_color']); ?>; }</style>
        <?php endif; ?>

        <?php do_action('themify_builder_before_template_content_render'); ?>

        <?php if ($epoch <= time()): ?>
            <?php if ($fields_args['done_action_countdown'] === 'revealo') :
                ?>

                <div class="countdown-finished ui <?php echo $fields_args['color_countdown']; ?>">
                    <?php echo apply_filters('themify_builder_module_content', $fields_args['content_countdown']); ?>
                </div>

            <?php elseif ($fields_args['done_action_countdown'] === 'redirect' && !Themify_Builder::$frontedit_active) : ?>

                <script type="text/javascript">
                    window.location = '<?php echo esc_url($fields_args['redirect_countdown']); ?>';
                </script>

            <?php endif; ?>
        <?php else: ?>

            <?php if ($fields_args['mod_title_countdown'] !== ''): ?>
                <?php echo $fields_args['before_title'] . apply_filters('themify_builder_module_title', $fields_args['mod_title_countdown'], $fields_args) . $fields_args['after_title']; ?>
            <?php endif; ?>

            <div class="builder-countdown-holder" data-target-date="<?php echo $epoch; ?>">

                <?php if ($next_year < $epoch) : ?>
                    <div class="years ui <?php echo $fields_args['color_countdown']; ?>">
                        <span class="date-counter"></span>
                        <span class="date-label"><?php _e('Years', 'builder-countdown'); ?></span>
                    </div>
                <?php endif; ?>

                <div class="days ui <?php echo $fields_args['color_countdown']; ?>">
                    <span class="date-counter"></span>
                    <span class="date-label"><?php echo $fields_args['label_days']; ?></span>
                </div>
                <div class="hours ui <?php echo $fields_args['color_countdown']; ?>">
                    <span class="date-counter"></span>
                    <span class="date-label"><?php echo $fields_args['label_hours']; ?></span>
                </div>
                <div class="minutes ui <?php echo $fields_args['color_countdown']; ?>">
                    <span class="date-counter"></span>
                    <span class="date-label"><?php echo $fields_args['label_minutes']; ?></span>
                </div>
                <div class="seconds ui <?php echo $fields_args['color_countdown']; ?>">
                    <span class="date-counter"></span>
                    <span class="date-label"><?php echo $fields_args['label_seconds']; ?></span>
                </div>
            </div>

        <?php endif; ?>

        <?php do_action('themify_builder_after_template_content_render'); ?>
    </div>
    <!-- /module countdown -->
<?php endif; ?>
<?php TFCache::end_cache(); ?>