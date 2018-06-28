<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly
/**
 * Module Name: A/B Image
 */

class TB_AB_Image_Module extends Themify_Builder_Component_Module {

    function __construct() {
        parent::__construct(array(
            'name' => __('A/B Image', 'builder-ab-image'),
            'slug' => 'ab-image'
        ));
    }

    function get_assets() {
        $instance = Builder_AB_Image::get_instance();
        return array(
            'selector' => '.module-ab-image',
            'css' => themify_enque($instance->url . 'assets/style.css'),
            'js' => themify_enque($instance->url . 'assets/scripts.js'),
            'ver' => $instance->version,
            'external' => Themify_Builder_Model::localize_js('builderABImage', apply_filters('builder_ab_image_script_vars', array(
                'default_offset_pct' => 0.5
            )))
        );
    }

    public function get_options() {
        $img_disabled = Themify_Builder_Model::is_img_php_disabled();
        $image_sizes = !$img_disabled ? themify_get_image_sizes_list(false) : array();
        return array(
            array(
                'id' => 'mod_title_image_compare',
                'type' => 'text',
                'label' => __('Module Title', 'builder-ab-image'),
                'class' => 'large',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'url_image_a',
                'type' => 'image',
                'label' => __('Image URL', 'builder-ab-image'),
                'class' => 'xlarge',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'url_image_b',
                'type' => 'image',
                'label' => __('Second Image URL', 'builder-ab-image'),
                'class' => 'xlarge',
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'title_image',
                'type' => 'text',
                'label' => __('Image Alt', 'builder-ab-image'),
                'class' => 'fullwidth',
                'after' => '<small>' . __('Optional: Image alt is the image "alt" attribute. Primarily used for SEO describing the image.', 'builder-ab-image') . '</small>',
                'render_callback' => array(
                    'binding' => ''
                )
            ),
            array(
                'id' => 'image_size_image_compare',
                'type' => 'select',
                'label' => __('Image Size', 'builder-ab-image'),
                'empty' => array(
                    'val' => '',
                    'label' => ''
                ),
                'hide' => !$img_disabled,
                'options' => $image_sizes,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'width_image_compare',
                'type' => 'text',
                'label' => __('Width', 'builder-ab-image'),
                'class' => 'xsmall',
                'help' => 'px',
                'value' => 300,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'height_image_compare',
                'type' => 'text',
                'label' => __('Height', 'builder-ab-image'),
                'class' => 'xsmall',
                'help' => 'px',
                'value' => 200,
                'render_callback' => array(
                    'binding' => 'live'
                )
            ),
            array(
                'id' => 'orientation_compare',
                'type' => 'select',
                'label' => __('Orientation', 'builder-ab-image'),
                'options' => array(
                    'horizontal' => __('Horizontal', 'builder-ab-image'),
                    'vertical' => __('Vertical', 'builder-ab-image'),
                ),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
            ,
            // Additional CSS
            array(
                'type' => 'separator',
                'meta' => array('html' => '<hr/>')
            ),
            array(
                'id' => 'css_ab_image',
                'type' => 'text',
                'label' => __('Additional CSS Class', 'builder-ab-image'),
                'class' => 'large exclude-from-reset-field',
                'help' => sprintf('<br/><small>%s</small>', __('Add additional CSS class(es) for custom styling', 'builder-ab-image')),
                'render_callback' => array(
                    'binding' => 'live'
                )
            )
        );
    }

    public function get_default_settings() {
        return array(
            'orientation_compare' => 'horizontal',
            'url_image_a' => 'https://themify.me/demo/themes/themes/wp-content/uploads/addon-samples/ab-image-grayscale.jpg',
            'url_image_b' => 'https://themify.me/demo/themes/themes/wp-content/uploads/addon-samples/ab-image-color.jpg'
        );
    }

    public function get_styling() {
        return array(
            //bacground
            self::get_seperator('image_bacground', __('Background', 'themify'), false),
            self::get_color('.module-ab-image', 'background_color', __('Background Color', 'themify'), 'background-color'),
            // Padding
            self::get_seperator('padding', __('Padding', 'themify')),
            self::get_padding('.module-ab-image'),
            // Margin
            self::get_seperator('margin', __('Margin', 'themify')),
            self::get_margin('.module-ab-image'),
            // Border
            self::get_seperator('border', __('Border', 'themify')),
            self::get_border('.module-ab-image')
        );
    }

    protected function _visual_template() {
        $module_args = self::get_module_args();
        ?>

        <div class="module module-<?php echo $this->slug; ?> {{ data.css_ab_image }}">

            <# if ( data.mod_title_image_compare ) { #>
            <?php echo $module_args['before_title'] ?> 
            {{{ data.mod_title_image_compare }}}
            <?php echo $module_args['after_title']; ?>
            <# } #>

            <div class="twentytwenty-container ab-image" data-orientation="{{ data.orientation_compare }}" <?php echo isset($module_args['width_image_compare']) ? 'style="max-width: {{ data.width_image_compare }}px;"' : '' ?>>
                <img src="{{ data.url_image_a }}" alt="{{ data.title_image }}" width="{{ data.width_image_compare }}" height="{{ data.height_image_compare }}" style="height: {{ data.height_image_compare }}px; object-fit: cover;">
                <img src="{{ data.url_image_b }}" alt="{{ data.title_image }}" width="{{ data.width_image_compare }}" height="{{ data.height_image_compare }}" style="height: {{ data.height_image_compare }}px; object-fit: cover;">
            </div>

        </div>
        <?php
    }

}
Themify_Builder_Model::register_module('TB_AB_Image_Module');
