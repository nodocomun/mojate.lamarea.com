<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Fittext
 * Description: Display responsive heading
 */
class TB_Fittext_Module extends Themify_Builder_Component_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('FitText', 'builder-fittext'),
			'slug' => 'fittext'
		));
	}

	function get_assets() {
		$instance = Builder_Fittext::get_instance();
		return array(
			'selector' => '.module-fittext, .module-type-fittext',
			'css' => themify_enque($instance->url . 'assets/style.css'),
			'js' => themify_enque($instance->url . 'assets/fittext.js'),
			'ver' => $instance->version,
			'external' => Themify_Builder_Model::localize_js( 'builderFittext', array(
				'webSafeFonts' => themify_get_web_safe_font_list( true )
			) )
		);
	}

	public function get_options() {
		return array(
			array(
				'id' => 'fittext_text',
				'type' => 'text',
				'label' => __('Text', 'builder-fittext'),
				'class' => 'fullwidth',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'fittext_link',
				'type' => 'text',
				'label' => __('Link', 'builder-fittext'),
				'class' => 'fullwidth',
				'render_callback' => array(
					'binding' => 'live'
				)
			),
			array(
				'id' => 'fittext_params',
				'type' => 'checkbox',
				'label' => false,
				'pushed' => 'pushed',
				'options' => array(
					array( 'name' => 'lightbox', 'value' => __('Open link in lightbox', 'builder-fittext') ),
					array( 'name' => 'newtab', 'value' => __('Open link in new tab', 'builder-fittext') )
				),
				'new_line' => false,
			)
                        ,
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_fittext',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'builder-fittext'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-fittext') ),
				'render_callback' => array(
					'binding' => 'live'
				)
			)
		);
	}

	public function get_default_settings() {
		return array(
			'fittext_text' => esc_html__( 'FitText Heading', 'builder-fittext' )
		);
	}


	public function get_styling() {
		return array(
                        //bacground
                        self::get_seperator('image_bacground', __('Background', 'themify'), false),
                        self::get_color('.module-fittext', 'background_color', __('Background Color', 'themify'), 'background-color'),
			// Font
                        self::get_seperator('font', __('Font', 'themify')),
                        self::get_font_family( array( '.module-fittext', '.module-fittext span', '.module-fittext a' )),
                        self::get_color(array( '.module-fittext', '.module-fittext span', '.module-fittext a' ), 'font_color', __('Font Color', 'themify')),
                        self::get_text_align( array( '.module-fittext', '.module-fittext span', '.module-fittext a' )),
			// Link
                        self::get_seperator('link',__('Link', 'themify')),
                        self::get_text_decoration('.module-fittext a'),
			// Padding
                        self::get_seperator('padding',__('Padding', 'themify')),
                        self::get_padding('.module-fittext'),
			// Margin
                        self::get_seperator('margin',__('Margin', 'themify')),
                        self::get_margin('.module-fittext'),
			// Border
                        self::get_seperator('border',__('Border', 'themify')),
                        self::get_border('.module-fittext')
		);
	}

	protected function _visual_template() {
        ?>
		<div class="module module-<?php echo $this->slug; ?> {{ data.add_css_fittext }}" data-font-family="<# print( data.font_family || 'default' ) #>">
			<?php do_action( 'themify_builder_before_template_content_render' ); ?>

			<# if( data.fittext_link ) { #><a href="#"><# } #>

			<span>{{{ data.fittext_text }}}</span>

			<# if( data.fittext_link ) { #></a><# } #>

			<?php do_action( 'themify_builder_after_template_content_render' ); ?>
		</div>
	<?php
	}
}

Themify_Builder_Model::register_module( 'TB_Fittext_Module' );