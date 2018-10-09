<?php
/**
 * Plugin Name:       PTB Relation
 * Plugin URI:       https://themify.me/ptb-addons/relation
 * Description:       Addon to use with Post Type Builder plugin that allows users to set relation between PTB custom post types.
 * Version:           1.1.9
 * Author:            Themify
 * Author URI:        https://themify.me
 * Text Domain:       ptb-relation
 * Domain Path:       /languages
 *
 * @link              https://themify.me
 * @since             1.0.0
 * @package           PTB
 */
// If this file is called directly, abort.

defined('ABSPATH') or die('-1');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('themify-ptb/post-type-builder.php')) {
    if (is_plugin_active_for_network('themify-ptb/post-type-builder.php') && class_exists('PTB')){
        ptb_relation();
    } else {
        add_action('ptb_loaded', 'ptb_relation');
    }
} else {
    add_action('admin_notices', 'ptb_relation_admin_notice');
}
function ptb_relation() {
    include_once plugin_dir_path(__FILE__) . 'includes/class-ptb-relation.php';
    $version = PTB::get_plugin_version(__FILE__);
    new PTB_Relation($version);
}


function ptb_relation_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e('Please, activate Post Type Builder plugin" to "In order to activate PTB Relation, you need to have Post Type Builder plugin activated first.', 'ptb-relation'); ?></p>
    </div>
    <?php
    deactivate_plugins(plugin_basename(__FILE__));
}

/**
 * Initialize updater.
 * 
 * @since 1.0.0
 */
add_action('ptb_check_update', 'ptb_relation_update');

function ptb_relation_update() {
    $plugin_basename = plugin_basename(__FILE__);
    $plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));
    $name = trim(dirname($plugin_basename), '/');
    new PTB_Update_Check(array(
        'name' => $name,
        'nicename' => $plugin_data['Name'],
        'update_type' => 'plugin',
            ), $plugin_data['Version'], $name);
}
add_filter( 'plugin_row_meta', 'themify_ptb_relation_meta', 10, 2 );
function themify_ptb_relation_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'ptb-relation' ) . '">' . esc_html__( 'View Changelogs', 'ptb-relation' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
