<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Countdown
 */

class TB_Countdown_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('Countdown', 'builder-countdown'),
            'slug' => 'countdown'
        ));
    }

    function get_assets() {
        $instance = Builder_Countdown::get_instance();
        return array(
            'selector' => '.module-countdown',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/script.js'),
            'ver' => $instance->version
        );
    }

    public function get_options() {
        $colors = Themify_Builder_Model::get_colors();
        $colors[] = array('img' => 'transparent', 'value' => 'transparent', 'label' => __('Transparent', 'themify'));
        return array(
            array(
                'id' => 'mod_title_countdown',
                'type' => 'text',
                'label' => __('Module Title', 'builder-countdown'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'mod_date_countdown',
                'type' => 'text',
                'label' => __('Date', 'builder-countdown'),
                'class' => 'large',
                'wrap_with_class' => 'builder-countdown-datepicker',
                'render_callback' => array(
                    'control_type' => 'change',
                    'event' => 'change',
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'color_countdown',
                'type' => 'layout',
                'mode' => 'sprite',
                'class' => 'tb-colors',
                'label' => __('Color', 'builder-countdown'),
                'options' => $colors,
                'bottom' => true,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'counter_background_color',
                'type' => 'text',
                'colorpicker' => true,
                'label' => __('Custom Color', 'builder-countdown'),
                'class' => 'small',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'done_action_countdown',
                'type' => 'radio',
                'label' => __('Finish Action', 'builder-countdown'),
                'options' => array(
                    'nothing' => __('Do nothing', 'builder-countdown'),
                    'redirect' => __('Redirect to an external URL', 'builder-countdown'),
                    'revealo' => __('Show content', 'builder-countdown'),
                ),
                'default' => 'nothing',
                'option_js' => true,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'content_countdown',
                'type' => 'wp_editor',
                'class' => 'fullwidth',
                'wrap_with_class' => 'tb-group-element tb-group-element-revealo',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'redirect_countdown',
                'type' => 'text',
                'label' => __('External Link', 'builder-countdown'),
                'class' => 'fullwidth',
                'after' => __('<div class="description">Note: the redirect will not occur for website administrators.</div>', 'builder-countdown'),
                'wrap_with_class' => 'tb-group-element tb-group-element-redirect',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'custom_labels',
                'type' => 'multi',
                'label' => __('Labels', 'builder-countdown'),
                'fields' => array(
                    array(
                        'id' => 'label_days',
                        'type' => 'text',
                        'label' => __('Days', 'themify'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'label_hours',
                        'type' => 'text',
                        'label' => __('Hours', 'themify'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'label_minutes',
                        'type' => 'text',
                        'label' => __('Minutes', 'themify'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'label_seconds',
                        'type' => 'text',
                        'label' => __('Seconds', 'themify'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                )
            )
            ,
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr />')
            ),
            array(
                'id' => 'add_css_countdown',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-countdown'),
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-countdown')),
                'class' => 'large exclude-from-reset-field',
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'mod_date_countdown' => '2030-12-31 16:00:00',
            'color_countdown' => 'transparent',
            'label_days' => esc_html__('Days', 'builder-countdown'),
            'label_hours' => esc_html__('Hours', 'builder-countdown'),
            'label_minutes' => esc_html__('Minutes', 'builder-countdown'),
            'label_seconds' => esc_html__('Seconds', 'builder-countdown'),
        );
    }

    public function get_styling() {
        return array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-countdown', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family(array('.module-countdown', '.module-countdown .builder-countdown-holder .ui')),
            self::get_color(array('.module-countdown', '.module-countdown .ui'), 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-countdown'),
            self::get_line_height('.module-countdown'),
            self::get_text_align('.module-countdown'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-countdown'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-countdown'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-countdown')
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>
        <# 
        var epoch = Date.parse( data.mod_date_countdown + ' <?php echo get_option('timezone_string'); ?>' ) / 1000,
        nextYear = new Date().setFullYear(new Date().getFullYear() + 1) / 1000,
        counterBg = data.counter_background_color 
        ? 'style="background: ' + themifybuilderapp.Utils.toRGBA( data.counter_background_color ) + '"' : '';
        #>
        <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_countdown }}">
            <# if( epoch <= Date.now() / 1000 ) { #>
            <# if( data.done_action_countdown == 'revealo' ) { #>
            <div class="countdown-finished ui {{ data.color_countdown }}">
                {{{ data.content_countdown }}}
            </div>
            <# } #>
            <# } else { #>
            <# if( data.mod_title_countdown ) { #>
            <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_countdown }}}
            <?php echo $module_args['after_title']; ?>
            <# } #>

            <?php do_action('themify_builder_before_template_content_render'); ?>

            <div class="builder-countdown-holder" data-target-date="{{ epoch }}">

                <# if( nextYear < epoch ) { #>
                <div class="years ui {{ data.color_countdown }}" data-leading-zeros="2" {{{ counterBg }}}>
                     <span class="date-counter"></span>
                    <span class="date-label"><?php _e('Years', 'builder-countdown'); ?></span>
                </div>
                <# } #>

                <div class="days ui {{ data.color_countdown }}" {{{ counterBg }}}>
                     <span class="date-counter"></span>
                    <span class="date-label">{{{ data.label_days }}}</span>
                </div>
                <div class="hours ui {{ data.color_countdown }}" {{{ counterBg }}}>
                     <span class="date-counter"></span>
                    <span class="date-label">{{{ data.label_hours }}}</span>
                </div>
                <div class="minutes ui {{ data.color_countdown }}" {{{ counterBg }}}>
                     <span class="date-counter"></span>
                    <span class="date-label">{{{ data.label_minutes }}}</span>
                </div>
                <div class="seconds ui {{ data.color_countdown }}" {{{ counterBg }}}>
                     <span class="date-counter"></span>
                    <span class="date-label">{{{ data.label_seconds }}}</span>
                </div>
            </div>

            <?php do_action('themify_builder_after_template_content_render'); ?>
            <# } #>
        </div>
        <?php
    }

}

Themify_Builder_Model::register_module('TB_Countdown_Module');
