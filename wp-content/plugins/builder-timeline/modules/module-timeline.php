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
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-timeline') )
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
		return array(
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
				'wrap_with_class' => 'tb-group-element tb-group-element-' . $key
			)
		), $mod_name );
	}
}

Themify_Builder_Model::register_module( 'TB_Timeline_Module' );