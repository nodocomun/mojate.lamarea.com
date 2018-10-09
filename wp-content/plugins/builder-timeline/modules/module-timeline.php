<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Timeline
 */
class TB_Timeline_Module extends Themify_Builder_Component_Module {
	public function __construct() {
		parent::__construct(array(
			'name' => __( 'Timeline', 'builder-timeline' ),
			'slug' => 'timeline'
		));
	}

	public function get_assets() {
		$instance = Builder_Timeline::get_instance();
		return array(
			'selector'=>'.module-timeline',
			'css'=>themify_enque($instance->url.'assets/style.css'),
			'js'=>themify_enque($instance->url.'assets/scripts.js'),
			'ver'=>$instance->version,
			'external'=>Themify_Builder_Model::localize_js('builder_timeline',
					array( 
						'url'=> $instance->url . 'assets/'
					) 
			)
		);
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_timeline',
				'type' => 'text',
				'label' => __('Module Title', 'builder-timeline'),
				'class' => 'large',
                                'render_callback' => array(
                                    'live-selector'=>'.module-title'
                                )
			),
			array(
				'id' => 'template_timeline',
				'type' => 'radio',
				'label' => __('Timeline Layout', 'builder-timeline'),
				'options' => array(
					'list' => __( 'List View', 'builder-timeline' ),
					'graph' => __( 'Timeline Graph', 'builder-timeline' ),
				),
				'default' => 'list'
			),
			array(
				'id' => 'source_timeline',
				'type' => 'timeline_source',
			),
			array(
				'id' => 'config_source_timeline',
				'type' => 'timeline_source_config',
			),
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_timeline',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-timeline'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling (<a href="https://themify.me/docs/builder#additional-css-class" target="_blank">learn more</a>).', 'builder-timeline') )
			)
		);
	}

	public function get_default_settings() {
		return array(
			'template_timeline' => 'list',
			'source_timeline' => 'text',
			'post_per_page_post_timeline' => 4,
			'text_source_timeline' => array( array(
					'title_timeline' => esc_html__( 'Timeline Title', 'builder-timeline' ),
					'content_timeline' => esc_html__( 'Short description here...', 'builder-timeline' ),
					'date_timeline' => esc_html__( 'Sep 2014', 'builder-timeline' ),
					'icon_timeline' => 'fa-calendar-check-o'
				)
			)
		);
	}
        
        public function get_visual_type() {
            return 'ajax';
        }

	public function get_styling() {
		$general = array(
                         //bacground
                        self::get_seperator('image_bacground', __('Background', 'themify'), false),
                        self::get_color('.module-timeline', 'background_color', __('Background Color', 'themify'), 'background-color'),
			// Font
                        self::get_seperator('font', __('Font', 'themify')),
                        self::get_font_family('.module-timeline'),
                        self::get_color(array( '.module-timeline', '.module-timeline .module-timeline-title a' ), 'font_color', __('Font Color', 'themify')),
                        self::get_font_size('.module-timeline'),
                        self::get_line_height('.module-timeline'),
			// Link
                        self::get_seperator('link',__('Link', 'themify')),
                        self::get_color('.module-timeline a', 'link_color'),
                        self::get_color('.module-timeline a:hover', 'link_color_hover'),
                        self::get_text_decoration('.module-timeline a'),
			// Padding
                        self::get_seperator('padding',__('Padding', 'themify')),
                        self::get_padding('.module-timeline'),
			// Margin
                        self::get_seperator('margin',__('Margin', 'themify')),
                        self::get_margin('.module-timeline'),
			// Border
                        self::get_seperator('border',__('Border', 'themify')),
                        self::get_border('.module-timeline')
		);

		$timeline_title = array(
			// Font
                        self::get_seperator('font',__('Font', 'themify')),
                        self::get_font_family(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3'),'f_f_t_t'),
                        self::get_color(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'f_c_t_t',__('Font Color', 'themify')),
                        self::get_font_size(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'f_s_t_t'),
                        self::get_line_height(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'l_h_t_t'),
						self::get_letter_spacing(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ), 'l_s_t_t'),
                        self::get_text_align(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'t_a_t_t'),
                        self::get_text_transform(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'t_t_t_t'),
                        self::get_font_style(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'f_sy_t_t', 'f_b_t_t'),
                        self::get_text_decoration(array ( '.module-timeline.layout-list .module-timeline-title', ' .vco-storyjs h3' ),'t_d_t_t')
		);

		$timeline_date = array(
			// Font
                        self::get_seperator('font',__('Font', 'themify')),
                        self::get_font_family(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'f_f_t_d'),
                        self::get_color(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date' ),'f_c_t_d',__('Font Color', 'themify')),
                        self::get_font_size(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'f_s_t_d'),
                        self::get_line_height(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'l_h_t_d'),
                        self::get_text_align(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'t_a_t_d'),
                        self::get_text_transform(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'t_t_t_d'),
                        self::get_font_style(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'f_sy_t_d', 'f_b_t_d'),
                        self::get_text_decoration(array ( '.module-timeline.layout-list .module-timeline-date', ' .vco-slider .slider-item .content .content-container .text .container h2.date'),'t_d_t_d')
		);

		$content = array(
			// Background
                        self::get_seperator('image_bacground',__( 'Background', 'themify' ),false),
                        self::get_color('.ui.module-tab .tab-content', 'b_c_c',__( 'Background Color', 'themify' ),'background-color'),
			// Font
                        self::get_seperator('font',__('Font', 'themify')),
                        self::get_font_family('.ui.module-tab .tab-content','f_f_c'),
                        self::get_color('.ui.module-tab .tab-content,.module-tab .tab-content h1,.module-tab .tab-content h2,.module-tab .tab-content h3:not(.module-title),.module-tab .tab-content h4,.module-tab .tab-content h5,.module-tab .tab-content h6','f_c_c',__('Font Color', 'themify')),
                        self::get_font_size('.ui.module-tab .tab-content','f_s_c'),
                        self::get_line_height('.ui.module-tab .tab-content','l_h_c'),
			// Padding
                        self::get_seperator('padding_content',__('Padding', 'themify')),
                        self::get_padding('.ui.module-tab .tab-content','c_p'),
			// Border
                        self::get_seperator('border',__('Border', 'themify')),
                        self::get_border('.ui.module-tab .tab-content','c_b')
		);

		return array(
			array(
				'type' => 'tabs',
				'id' => 'module-styling',
				'tabs' => array(
					'general' => array(
						'label' => __('General', 'themify'),
						'fields' => $general
					),
					'module-title' => array(
						'label' => __( 'Module Title', 'themify' ),
						'fields' => $this->module_title_custom_style()
					),
					'timeline_title' => array(
						'label' => __('Timeline Title', 'themify'),
						'fields' => $timeline_title
					),
					'timeline_date' => array(
						'label' => __('Timeline Date', 'themify'),
						'fields' => $timeline_date
					),
					'timeline_content' => array(
						'label' => __('Timeline Content', 'themify'),
						'fields' => $content
					)
				)
			)
		);

	}
}

function themify_builder_field_timeline_source( $field, $mod_name ) {
	$options = array();
	foreach( Builder_Timeline::get_instance()->get_sources() as $key => $instance ) {
		$options[$key] = $instance->get_name();
	}
	$options = array_reverse($options);
	themify_builder_module_settings_field( array(
		array(
			'id' => $field['id'],
			'type' => 'radio',
			'label' => __('Display', 'builder-timeline'),
			'options' => $options,
			'default' => 'text',
			'option_js' => true
		)
	), $mod_name );
}
function themify_builder_field_timeline_source_config( $field, $mod_name ) {
	foreach( Builder_Timeline::get_instance()->get_sources() as $key => $instance ) {
		themify_builder_module_settings_field( array(
			array(
				'id' => $key,
				'type' => 'group',
				'fields' => $instance->get_options(),
				'wrap_with_class' => 'tb_group_element tb_group_element_' . $key
			)
		), $mod_name );
	}
}

Themify_Builder_Model::register_module( 'TB_Timeline_Module' );