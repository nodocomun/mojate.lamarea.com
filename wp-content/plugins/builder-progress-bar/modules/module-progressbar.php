<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Module Name: Progress Bar
 */

class TB_ProgressBar_Module extends Themify_Builder_Component_Module {

    public function __construct() {
        parent::__construct(array(
            'name' => __('Progress Bar', 'builder-progressbar'),
            'slug' => 'progressbar'
        ));
    }

    public function get_assets() {
        $instance = Builder_ProgressBar::get_instance();
        return array(
            'selector' => '.module.module-progressbar .tb-progress-bar',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/scripts.js'),
            'ver' => $instance->version
        );
    }

    public function get_options() {
        return array(
            array(
                'id' => 'mod_title_progressbar',
                'type' => 'text',
                'label' => __('Module Title', 'builder-progressbar'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'progress_bars',
                'type' => 'builder',
                'options' => array(
                    array(
                        'id' => 'bar_label',
                        'type' => 'text',
                        'label' => __('Label', 'builder-progressbar'),
                        'class' => 'large',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'progress_bars'
                        )
                    ),
                    array(
                        'id' => 'bar_percentage',
                        'type' => 'text',
                        'label' => __('Percentage', 'builder-progressbar'),
                        'after' => '%',
                        'class' => 'small',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'progress_bars'
                        )
                    ),
                    array(
                        'id' => 'bar_color',
                        'type' => 'text',
                        'colorpicker' => true,
                        'label' => __('Color', 'builder-progressbar'),
                        'class' => 'small',
                        'render_callback' => array(
                            'binding' => 'live',
                            'repeater' => 'progress_bars'
                        )
                    ),
                ),
                'render_callback' => array(
                    'binding' => 'live',
                    'control_type' => 'repeater'
                )
            ),
            array(
                'id' => 'hide_percentage_text',
                'type' => 'select',
                'label' => __('Hide Percentage Text', 'builder-progressbar'),
                'options' => array(
                    'no' => __('No', 'builder-progressbar'),
                    'yes' => __('Yes', 'builder-progressbar'),
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
                'id' => 'add_css_progressbar',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-progressbar'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-progressbar')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'hide_percentage_text' => 'no',
            'progress_bars' => array(array(
                    'bar_label' => esc_html__('Label', 'builder-progressbar'),
                    'bar_percentage' => 80,
                    'bar_color' => '4a54e6_1'
                ))
        );
    }

    public function get_styling() {
        return array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-progressbar', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Font
            self::get_seperator('font', __('Font', 'themify')),
            self::get_font_family('.module-progressbar'),
            self::get_color('.module .progressbar', 'font_color', __('Font Color', 'themify')),
            self::get_font_size('.module-progressbar'),
            self::get_line_height('.module-progressbar'),
            self::get_text_align('.module-progressbar'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-progressbar'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-progressbar'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-progressbar')
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>

        <div class="module module-<?php echo $this->slug; ?> {{ data.add_css_progressbar }}">
            <# if( data.mod_title_progressbar ) { #>
        <?php echo $module_args['before_title']; ?>
            {{{ data.mod_title_progressbar }}}
            <?php echo $module_args['after_title']; ?>
            <# } #>

        <?php do_action('themify_builder_before_template_content_render'); ?>

            <div class="tb-progress-bar-wrap">
                <# _.each( data.progress_bars, function( bar, index ) { #>
                <div class="tb-progress-bar">

                    <i class="tb-progress-bar-label">{{{ bar.bar_label }}}</i>
                    <span class="tb-progress-bar-bg" data-percent="{{ bar.bar_percentage }}" style="width: 0; background-color: <# bar.bar_color && print(themifybuilderapp.Utils.toRGBA(bar.bar_color)) #>">

                        <# if( data.hide_percentage_text === 'no' ) { #>
                        <span class="tb-progress-tooltip" id="{{ index }}-progress-tooltip" data-to="{{ bar.bar_percentage }}" data-suffix="%" data-decimals="0"></span>
                        <# } #>

                    </span>

                </div><!-- .tb-progress-bar -->
                <# } ); #>
            </div><!-- .tb-progress-bar-wrap -->

        <?php do_action('themify_builder_after_template_content_render'); ?>
        </div>
        <?php
    }

}

Themify_Builder_Model::register_module('TB_ProgressBar_Module');
