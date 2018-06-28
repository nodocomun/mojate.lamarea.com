<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Base class
 *
 */
class PTB_Map_View {

    /**
     * The ID of plugin.
     *
     * @since    1.0.0
     * @access   public
     * @var      string $plugin_name The ID of this plugin.
     */
    private static $plugin_name;

    /**
     * The version of plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private static $version;

    /**
     * The type of custom meta box.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $type The type of custom meta box.
     */
    private static $options;

    public function __construct($version) {

        self::$plugin_name = 'ptb_map';
        self::$version = $version;
        self::load_dependencies();
        self::set_locale();
        if (is_admin()) {
            $this->define_admin_hooks();
        }
        else{
            $this->define_public_hooks();
        }
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
    private static function load_dependencies() {
        $plugin_dir = plugin_dir_path(dirname(__FILE__));

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once $plugin_dir . 'includes/class-ptb-map-view-i18n.php';
        if (is_admin()) {
            require_once $plugin_dir . 'admin/class-ptb-map-view-admin.php';
        }
        else{
             require_once $plugin_dir . 'public/class-ptb-map-view-public.php';
        }
      
        do_action('ptb_map_view_loaded');
    }

    private function define_admin_hooks() {
        $options = PTB::get_option();
        $plugin_admin = new PTB_Map_View_Admin(self::$plugin_name, self::$version, $options);
        add_action('init', array($plugin_admin, 'add_shortcode_icon'));
        add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));
        add_action('ptb_deactivated', array($plugin_admin, 'deactivate'));
        add_action('wp_ajax_ptb_map_ajax_get_post_type', array($plugin_admin, 'ptb_ajax_get_post_type'));
        
    }

    private function define_public_hooks() {
        new PTB_Map_View_Public(self::$plugin_name, self::$version);
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
    private static function set_locale() {

        $plugin_i18n = new PTB_Map_View_i18n();
        $plugin_i18n->set_domain(self::$plugin_name);
        $plugin_i18n->load_plugin_textdomain();
    }

}
