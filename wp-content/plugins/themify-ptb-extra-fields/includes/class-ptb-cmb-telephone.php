<?php
/**
 * Custom meta box class of type Telephone
 *
 * @link       http://themify.me
 * @since      1.2.8
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * Custom meta box class of type Telephone
 *
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_CMB_Telephone extends PTB_Extra_Base {

    /**
     * Adds the custom meta type to the plugin meta types array
     *
     * @since 1.0.0
     *
     * @param array $cmb_types Array of custom meta types of plugin
     *
     * @return array
     */
    public function filter_register_custom_meta_box_type($cmb_types) {

        $cmb_types[$this->get_type()] = array(
            'name' => __('Telephone', 'ptb')
        );

        return $cmb_types;
    }
	public function __construct($type, $plugin_name, $version) {
        parent::__construct($type, $plugin_name, $version);
    }

    /**
     * Renders the meta boxes for themplates
     *
     * @since 1.0.0
     *
     * @param string $id the metabox id
     * @param string $type the type of the page(Archive or Single)
     * @param array $args Array of custom meta types of plugin
     * @param array $data saved data
     * @param array $languages languages array
     */
    public function action_them_themplate($id, $type, $args, $data = array(), array $languages = array()) {

        ?>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[placement]"><?php _e('Text In place', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input ptb_back_text">
                <input type="text" id="ptb_<?php echo $id ?>[placement]"
                       name="[<?php echo $id ?>][placement]" value="<?php echo (isset($data['placement']) ? esc_attr($data['placement']) : '') ?>"
                       />
				<?php _e('(e.g Call Us) leave it empty to display the phone number', 'ptb_extra'); ?>
            </div>
        </div>
        <?php
    }
    /**
     * Renders the meta boxes on post edit dashboard
     *
     * @since 1.0.0
     *
     * @param WP_Post $post
     * @param string $meta_key
     * @param array $args
     */
    public function render_post_type_meta($post, $meta_key, $args) {
        $value = get_post_meta($post->ID, 'ptb_' . $meta_key, true);
        if (!$value) {
            $value = '';
        }
        ?>
        <input type="text" id="<?php echo $meta_key; ?>" size="11" name="<?php echo $meta_key; ?>" value="<?php echo esc_attr($value); ?>"/>
        <?php
    }

}