<?php
/**
 * Plugin Name:       PTB Extra Fields
 * Plugin URI:        https://themify.me/ptb-addons/extra-fields
 * Description:       This PTB addon adds more additional field types in meta box builder in Post Type Builder.
 * Version:           1.3.8
 * Author:            Themify
 * Author URI:        https://themify.me
 * Text Domain:       ptb_extra
 * Domain Path:       /languages
 *
 * @link              https://themify.me
 * @since             1.0.0
 * @package           PTB
 *
 */

// If this file is called directly, abort.

defined( 'ABSPATH' ) or die( '-1' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('themify-ptb/post-type-builder.php')) {
    if (is_plugin_active_for_network('themify-ptb/post-type-builder.php') && class_exists('PTB')){
        ptb_extra_load();
    } else {
         add_action('ptb_loaded','ptb_extra_load');
    }
} else {
    add_action( 'admin_notices', 'ptb_extra_admin_notice' );
}
function ptb_extra_load() {
    include_once plugin_dir_path( __FILE__ ) . 'includes/ptb-extra-base.php';
    $version  = PTB::get_plugin_version(__FILE__);
    PTB_Extra_Base::Init($version);
}

function ptb_extra_admin_notice() {
?>
    <div class="error">
        <p><?php _e('Please, activate Post Type Builder plugin','ptb_extra' ); ?></p>
    </div>
 <?php
 deactivate_plugins(plugin_basename( __FILE__ ));
}


/**
 * Initialize updater.
 * 
 * @since 1.0.0
 */
add_action('ptb_check_update','ptb_extra_check_update');
function ptb_extra_check_update(){
    $plugin_basename = plugin_basename( __FILE__ );
    $plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
    $name = trim( dirname( $plugin_basename ), '/' );
    new PTB_Update_Check( array(
            'name' => $name,
            'nicename' => $plugin_data['Name'],
            'update_type' => 'plugin',
    ), $plugin_data['Version'], $name);
}
add_filter( 'plugin_row_meta', 'themify_ptb_extra_fields_plugin_meta', 10, 2 );
function themify_ptb_extra_fields_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'ptb_extra' ) . '">' . esc_html__( 'View Changelogs', 'ptb_extra' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
