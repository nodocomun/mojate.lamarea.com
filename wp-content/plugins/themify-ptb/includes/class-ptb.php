<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       https://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB {

    

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    public static $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of the plugin.
     */
    public static $version;

    /**
     * Absolute path to the plugin's main directory
     *
     * @var string
     */
    public $dir;

    /**
     * URL to plugin's main directory
     *
     * @var string
     */
    public $uri;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the Dashboard and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public static $options = null;

    /**
     * Creates or returns an instance of this class.
     *
     * @return	A single instance of this class.
     */
    public static function get_instance() {
        static $instance = null;
        return null === $instance ? $instance = new self : $instance;
    }

    private function __construct() {
        self::$plugin_name = 'ptb';
        $this->load_dependencies();
        $this->set_locale();
    }

    public function set_constants($version, $dir, $uri) {
        self::$version = $version;
        $this->dir = $dir;
        $this->uri = $uri;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - PTB_Loader. Orchestrates the hooks of the plugin.
     * - PTB_i18n. Defines internationalization functionality.
     * - PTB_Admin. Defines all hooks for the dashboard.
     * - PTB_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        $plugindir = plugin_dir_path(dirname(__FILE__));

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once $plugindir . 'includes/class-ptb-i18n.php';
        require_once $plugindir . 'includes/class-ptb-utils.php';
        require_once $plugindir . 'includes/class-ptb-cmb-base.php';
        require_once $plugindir . 'includes/class-ptb-cmb-text.php';
        require_once $plugindir . 'includes/class-ptb-cmb-email.php';
        require_once $plugindir . 'includes/class-ptb-cmb-textarea.php';
        require_once $plugindir . 'includes/class-ptb-cmb-select.php';
        require_once $plugindir . 'includes/class-ptb-cmb-checkbox.php';
        require_once $plugindir . 'includes/class-ptb-cmb-radio-button.php';
        require_once $plugindir . 'includes/class-ptb-cmb-image.php';
        require_once $plugindir . 'includes/class-ptb-cmb-link-button.php';
        require_once $plugindir . 'includes/class-ptb-cmb-number.php';
        require_once $plugindir . 'includes/class-ptb-cpt.php';
        require_once $plugindir . 'includes/class-ptb-ctx.php';
        require_once $plugindir . 'includes/class-ptb-ptt.php';
        require_once $plugindir . 'includes/class-ptb-options.php';
        require_once $plugindir . 'includes/class-ptb-form-cpt.php';
        require_once $plugindir . 'includes/class-ptb-form-ctx.php';
        require_once $plugindir . 'includes/class-ptb-form-ptt.php';
        require_once $plugindir . 'includes/class-ptb-form-import-export.php';
        require_once $plugindir . 'includes/class-ptb-form-css.php';

        //classes for working with themplates
        require_once $plugindir . 'includes/class-ptb-form-ptt-them.php';
        require_once $plugindir . 'includes/class-ptb-form-ptt-archive.php';
        require_once $plugindir . 'includes/class-ptb-form-ptt-single.php';

        require_once $plugindir . 'includes/class-ptb-list-cpt.php';
        require_once $plugindir . 'includes/class-ptb-list-ctx.php';
        require_once $plugindir . 'includes/class-ptb-list-ptt.php';
        require_once $plugindir . 'includes/widgets/class-ptb-widget-recent-posts.php';
        /**
         * The class responsible for defining all actions that occur in the Dashboard.
         */
        require_once $plugindir . 'admin/class-ptb-admin.php';


        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once $plugindir . 'public/class-ptb-public.php';

        do_action('ptb_loaded');
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the PTB_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new PTB_i18n();
        $plugin_i18n->set_domain(self::get_plugin_name());
        add_action('plugins_loaded', array($plugin_i18n, 'load_plugin_textdomain'));
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public static function get_plugin_name() {
        return self::$plugin_name;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public static function get_version() {
        return self::$version;
    }

    /**
     * Register all of the hooks related to the dashboard functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new PTB_Admin(self::$plugin_name, self::$version, self::$options);
        add_action('init', array(self::$options, 'ptb_register_custom_taxonomies'), 10);
        add_action('init', array(self::$options, 'ptb_register_custom_post_types'), 100);
        add_action('widgets_init', array(self::$options, 'ptb_load_widget'));
        add_action('save_post', array(self::$options, 'save_custom_meta'), 10, 3);
        add_action('delete_attachment',array($plugin_admin,'ptb_delete_attachment'),10,1);
        //Ajax actions registration
        if (PTB_Utils::is_ajax()) {
            add_action('wp_ajax_ptb_ajax_post_type_name_validate', array($plugin_admin, 'ptb_ajax_post_type_name_validate'));
            add_action('wp_ajax_ptb_ajax_taxonomy_name_validate', array($plugin_admin, 'ptb_ajax_taxonomy_name_validate'));
            add_action('wp_ajax_ptb_remove_dialog', array($plugin_admin, 'remove_disalog'));
            add_action('wp_ajax_ptb_ajax_remove', array($plugin_admin, 'ptb_remove'));
            add_action('wp_ajax_ptb_register', array($plugin_admin, 'ptb_register'));
            add_action('wp_ajax_ptb_copy', array($plugin_admin, 'ptb_copy'));
            add_action('wp_ajax_ptb_ajax_get_post_type', array($plugin_admin, 'ptb_ajax_get_post_type'));
            add_action('wp_ajax_ptb_ajax_themes', array($plugin_admin, 'ptb_ajax_theme'));
            add_action('wp_ajax_ptb_ajax_themes_save', array($plugin_admin, 'ptb_ajax_theme_save'));
            $plugin_public = PTB_Public::get_instance();
            add_action('wp_ajax_nopriv_ptb_single_lightbox', array($plugin_public, 'single_lightbox'));
            add_action('wp_ajax_ptb_single_lightbox', array($plugin_public, 'single_lightbox'));
        } else {
            add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));
            add_action('admin_menu', array($plugin_admin, 'add_plugin_admin_menu'));
            add_action('admin_init', array($plugin_admin, 'register_plugin_settings'), 11);
        }
        add_action('init', array($plugin_admin, 'add_ptb_shortcode'));
        $this->init_custom_meta_box_types();
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

       PTB_Public::get_instance();
		
    }

    /**
     * Creates instances of custom meta boxes
     *
     * @since 1.0.0
     */
    private function init_custom_meta_box_types() {

        new PTB_CMB_Text('text', self::$plugin_name, self::$version);
        new PTB_CMB_Email('email', self::$plugin_name, self::$version);
        new PTB_CMB_Textarea('textarea', self::$plugin_name, self::$version);
        new PTB_CMB_Radio_Button('radio_button', self::$plugin_name, self::$version);
        new PTB_CMB_Checkbox('checkbox', self::$plugin_name, self::$version);
        new PTB_CMB_Select('select', self::$plugin_name, self::$version);
        new PTB_CMB_Image('image', self::$plugin_name, self::$version);
        new PTB_CMB_Link_Button('link_button', self::$plugin_name, self::$version);
        new PTB_CMB_Number('number', self::$plugin_name, self::$version);
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        self::get_option();
        $this->define_admin_hooks();
        if (!is_admin()) {
            $this->define_public_hooks();
        }
    }

    public static function get_option() {
        if (!isset(self::$options)) {
            self::$options = new PTB_Options(self::$plugin_name, self::$version);
        }
        return self::$options;
    }

    /**
     * Returns current plugin version.
     * 
     * @return string Plugin version
     */
    public static function get_plugin_version($plugin_url) {
        $plugin_data = get_file_data($plugin_url, array('ver' => 'Version'));
        return $plugin_data['ver'];
    }

}
