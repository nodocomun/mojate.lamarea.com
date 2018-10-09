<?php
/**
 * Plugin Name:       PTB Map View
 * Plugin URI:        https://themify.me/ptb-addons/map-view
 * Description:       This PTB addon allows to show custom post types on Google Maps.
 * Version:           1.2.1
 * Author:            Themify
 * Author URI:        https://themify.me
 * Text Domain:       ptb_map
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
if (is_plugin_active('themify-ptb-extra-fields/themify-ptb-extra-fields.php')) {
    if (is_plugin_active_for_network('themify-ptb-extra-fields/themify-ptb-extra-fields.php') && class_exists('PTB')){
        ptb_map_load();
    } else {
        add_action('ptb_loaded','ptb_map_load');
    }
}
else{
    add_action( 'admin_notices', 'ptb_map_admin_notice' );
    
}

function ptb_map_load() {
    include_once plugin_dir_path( __FILE__ ) . 'includes/class-ptb-map-view.php';
    $version  = PTB::get_plugin_version(__FILE__);
    new PTB_Map_View($version);
}

function ptb_map_admin_notice() {
?>
    <div class="error">
        <p><?php _e('Please, activate PTB Extra Fields plugin','ptb_map' ); ?></p>
    </div>
 <?php
 deactivate_plugins(plugin_basename( __FILE__ ));
}


/**
 * Initialize updater.
 * 
 * @since 1.0.0
 */
add_action('ptb_check_update','ptb_map_check_update');
function ptb_map_check_update(){
    $plugin_basename = plugin_basename( __FILE__ );
    $plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
    $name = trim( dirname( $plugin_basename ), '/' );
    new PTB_Update_Check( array(
            'name' => $name,
            'nicename' => $plugin_data['Name'],
            'update_type' => 'plugin',
    ), $plugin_data['Version'], $name);
}
add_filter( 'plugin_row_meta', 'themify_ptb_map_view_plugin_meta', 10, 2 );
function themify_ptb_map_view_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'ptb_map' ) . '">' . esc_html__( 'View Changelogs', 'ptb_map' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
