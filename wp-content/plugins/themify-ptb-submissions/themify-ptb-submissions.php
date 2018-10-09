<?php
/**
 * Plugin Name:       PTB Submissions
 * Plugin URI:        https://themify.me/ptb-addons/submissions
 * Description:       PTB addon to use with Post Type Builder plugin that allows users to submit and manage custom posts on frontend.
 * Version:           1.3.1
 * Author:            Themify
 * Author URI:        https://themify.me
 * Text Domain:       ptb-submission
 * Domain Path:       /languages
 *
 * @link              https://themify.me
 * @since             1.0.0
 * @package           PTB
 */
// If this file is called directly, abort.

defined('ABSPATH') or die('-1');
define('PTB_SLUG_AUTHOR', 'ptb-author');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('themify-ptb/post-type-builder.php')) {
    if (is_plugin_active_for_network('themify-ptb/post-type-builder.php') && class_exists('PTB')){
        ptb_submission();
    } else {
         add_action('ptb_loaded','ptb_submission');
    }
} else {
    add_action( 'admin_notices', 'ptb_submission_admin_notice' );
}
function ptb_submission() {
    include_once plugin_dir_path(__FILE__) . 'includes/class-ptb-submissions.php';
    $version = PTB::get_plugin_version(__FILE__);
    new PTB_Submission($version);
}

function ptb_submission_activate() {
    $slug = 'my-submissions';
    $slug_author = apply_filters('ptb_submission_author_page', PTB_SLUG_AUTHOR);
    $args = array(
        'name' => $slug,
        'post_type' => 'page',
        'post_status' => 'any',
        'numberposts' => 1,
        'no_found_rows' => true
    );
    $submissions_page = get_posts($args);
    if (!$submissions_page) {
        wp_insert_post(array(
            'post_name' => $slug,
            'post_title' => 'My Submissions',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_content' => '[ptb_submission_account]'
        ));
    }
    $args = array(
        'name' => $slug_author,
        'post_type' => 'page',
        'post_status' => 'any',
        'numberposts' => 1,
        'no_found_rows' => true
    );
    $author_page = get_posts($args);
    if (!$author_page) {
        wp_insert_post(array(
            'post_name' => $slug_author,
            'post_title' => 'PTB Authors',
            'post_status' => 'publish',
            'post_type' => 'page'
        ));
    }
	if ( get_role('ptb') ) {
		if ( !get_option('ptb_submission_updated_authors', false) ) {
			update_option('ptb_submission_updated_authors', false);
		}
		remove_role( 'ptb' );
	} else {
		update_option('ptb_submission_updated_authors', true);
	}
    $capabilities = array('read' => true, 'level_1' => true);
    $capabilities = apply_filters('ptb_submission_role', $capabilities);
    add_role('ptb', __('PTB Author', 'ptb-submission'), $capabilities);
}

register_activation_hook(__FILE__, 'ptb_submission_activate');

function ptb_submission_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e('Please, activate Post Type Builder plugin" to "In order to activate PTB Submission, you need to have Post Type Builder plugin activated first.', 'ptb-submission'); ?></p>
    </div>
    <?php
    deactivate_plugins(plugin_basename(__FILE__));
}

/**
 * Initialize updater.
 * 
 * @since 1.0.0
 */
add_action('ptb_check_update', 'ptb_submission_update');

function ptb_submission_update() {
    $plugin_basename = plugin_basename(__FILE__);
    $plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));
    $name = trim(dirname($plugin_basename), '/');
    new PTB_Update_Check(array(
        'name' => $name,
        'nicename' => $plugin_data['Name'],
        'update_type' => 'plugin',
            ), $plugin_data['Version'], $name);
}
add_filter( 'plugin_row_meta', 'themify_ptb_submissions_meta', 10, 2 );
function themify_ptb_submissions_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'ptb-submission' ) . '">' . esc_html__( 'View Changelogs', 'ptb-submission' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
