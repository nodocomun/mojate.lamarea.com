<?php

/**
 * Plugin Name:       Post Type Builder
 * Plugin URI:        http://themify.me/post-type-builder
 * Description:       This "all-in-one" plugin allows you to create Custom Post Types, Meta Boxes, Taxonomies, and Templates.
 * Version:           1.4.7 
 * Author:            Themify
 * Author URI:        http://themify.me
 * Text Domain:       ptb
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ptb-activator.php
 */
function activate_ptb() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ptb-activator.php';
    PTB_Activator::activate();
    set_transient('ptb_welcome_page', true, 30);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ptb-deactivator.php
 */
function deactivate_ptb() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-ptb-deactivator.php';
    Ptb_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_ptb');
register_deactivation_hook(__FILE__, 'deactivate_ptb');
add_filter( 'plugin_row_meta', 'themify_ptb_plugin_row_meta', 10, 2 );
function themify_ptb_plugin_row_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'ptb' ) . '">' . esc_html__( 'View Changelogs', 'ptb' ) . '</a>'
		);

		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-ptb.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ptb() {
    $version = PTB::get_plugin_version(__FILE__);
    PTB::get_instance()->set_constants( $version, plugin_dir_path( __FILE__ ), plugin_dir_url( __FILE__ ) );
	PTB::get_instance()->run();
}

run_ptb();

/**
 * Initialize updater.
 * 
 * @since 1.0.0
 */
function themify_ptb_start_updater() {
    // Include Updater
    if (is_admin() && current_user_can('update_plugins')) {
        require_once plugin_dir_path(__FILE__) . 'admin/class-ptb-update-check.php';
        if (!function_exists('get_plugin_data')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $plugin_basename = plugin_basename(__FILE__);
        $plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));

        new PTB_Update_Check(array(
            'name' => trim(dirname($plugin_basename), '/'),
            'nicename' => $plugin_data['Name'],
            'update_type' => 'plugin',
                ), $plugin_data['Version'], 'post-type-builder');

        do_action('ptb_check_update');
    }
}

add_action('after_setup_theme', 'themify_ptb_start_updater');
