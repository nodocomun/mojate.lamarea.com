<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Counter
 */

class TB_Counter_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('Counter', 'builder-counter'),
            'slug' => 'counter'
        ));
    }

    function get_assets() {
        $instance = Builder_Counter::get_instance();
        return array(
            'selector' => '.module-counter',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/scripts.js'),
            'ver' => $instance->version
        );
    }

    public function get_options() {
        return array(
            array(
                'id' => 'mod_title_counter',
                'type' => 'text',
                'label' => __('Module Title', 'builder-counter'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'multi_number_counter',
                'type' => 'multi',
                'label' => __('Number', 'builder-counter'),
                'fields' => array(
                    array(
                        'id' => 'number_counter',
                        'type' => 'text',
                        'class' => 'fullwidth',
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'number_grouping',
                        'type' => 'text',
                        'after' => __('Thousand Separator', 'builder-counter'),
                        'class' => 'xsmall',
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                )
            ),
            array(
                'id' => 'label_counter',
                'type' => 'text',
                'label' => __('Label', 'builder-counter'),
                'class' => 'fullwidth',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'multi_circle_counter',
                'type' => 'multi',
                'label' => __('Circle', 'builder-counter'),
                'fields' => array(
                    array(
                        'id' => 'circle_percentage_counter',
                        'type' => 'text',
                        'label' => __('Percentage', 'builder-counter'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'circle_stroke_counter',
                        'type' => 'text',
                        'label' => __('Stroke', 'builder-counter'),
                        'class' => 'large',
                        'after' => 'px',
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'circle_color_counter',
                        'type' => 'text',
                        'colorpicker' => true,
                        'class' => 'large',
                        'label' => __('Color', 'builder-counter'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                    array(
                        'id' => 'circle_background_counter',
                        'type' => 'text',
                        'colorpicker' => true,
                        'class' => 'large',
                        'label' => __('Background', 'builder-counter'),
                        'render_callback' => array(
                            'binding' => 'live'
                        )
                    ),
                )
            ),
            array(
                'id' => 'size_counter',
                'type' => 'select',
                'label' => __('Size', 'builder-counter'),
                'options' => array(
                    'large' => __('Large', 'builder-counter'),
                    'medium' => __('Medium', 'builder-counter'),
                    'small' => __('Small', 'builder-counter'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr/>')
            ),
            array(
                'id' => 'add_css_counter',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-counter'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-counter')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'number_counter' => '50k',
            'label_counter' => esc_html__('Followers', 'builder-counter'),
            'circle_percentage_counter' => 50,
            'circle_stroke_counter' => 2,
            'circle_color_counter' => '47cbff',
            'circle_background_counter' => 'd8eaed',
            'size_counter' => 'medium',
        );
    }

    public function get_styling() {
        return array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-counter', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family('.module-counter'),
            self::get_color('.module-counter', 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-counter'),
            self::get_line_height('.module-counter'),
            self::get_text_align('.module-counter'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-counter'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-counter'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-counter')
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>
        <# var parts = data.number_counter 
        ? data.number_counter.match( /([\D]*)([\d\.]*)([\D]*)/ )
        : ['','','',''],
        counterSize = { small: 100, medium: 150, large: 200 }; #>

        <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_counter }}">
            <# if( data.mod_title_counter ) { #>
            <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_counter }}}
        <?php echo $module_args['after_title']; ?>
            <# } #>

        <?php do_action('themify_builder_before_template_content_render'); ?>

            <# if( data.circle_percentage_counter ) { #>
                <div class="counter-chart" data-percent="{{ data.circle_percentage_counter }}" data-color="<# data.circle_color_counter && print(themifybuilderapp.Utils.toRGBA(data.circle_color_counter))#>" data-trackcolor="rgba(0,0,0,.1)" data-linecap="butt" data-scalelength="0" data-rotate="0" data-size="<# data.size_counter && print( counterSize[ data.size_counter ] ) #>" data-linewidth="{{ data.circle_stroke_counter }}" data-animate="2000">
            <# }

            if( data.circle_background_counter ) { #>
                <div class="module-counter-background" style="background:<# print(themifybuilderapp.Utils.toRGBA(data.circle_background_counter))#>"></div>
            <# } #>

                <div class="number">
                    <span class="bc-timer" id="{{ Date.now() }}-bc-timer" data-from="0" data-to="{{ parts[2] }}" data-suffix="{{ parts[3] }}" data-prefix="{{ parts[1] }}" data-decimals="<# print( parts[2].split('.')[1] || 0 ) #>" data-grouping="{{ data.number_grouping }}"></span>
                </div>

            <# if( data.circle_percentage_counter ) { #>
                </div><!-- .chart -->
            <# } #>

            <div class="counter-text">{{{ data.label_counter }}}</div>

        <?php do_action('themify_builder_after_template_content_render'); ?>
        </div>
        <?php
    }

}

Themify_Builder_Model::register_module('TB_Counter_Module');
