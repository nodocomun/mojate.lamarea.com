<?php

/*
  Plugin Name:  Builder Countdown
  Plugin URI:   http://themify.me/addons/countdown
  Version:      1.1.4
  Description:  It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
  Text Domain:  builder-countdown
  Domain Path:  /languages
 */

defined('ABSPATH') or die('-1');

class Builder_Countdown {

    public $url;
    private $dir;
    public $version;

    /**
     * Creates or returns an instance of this class.
     *
     * @return	A single instance of this class.
     */
    public static function get_instance() {
        static $instance = null;
        if ($instance === null) {
            $instance = new self;
        }
        return $instance;
    }

    private function __construct() {
        $this->constants();
        add_action('plugins_loaded', array($this, 'i18n'), 5);
        add_action('themify_builder_setup_modules', array($this, 'register_module'));
        if (is_admin()) {
            add_action('themify_builder_admin_enqueue', array($this, 'admin_enqueue'), 15);
            add_action('init', array($this, 'updater'));
        } else {
            add_action('themify_js_top_frame', array($this, 'frontend_js_enqueue'), 10, 1);
            add_filter('themify_styles_top_frame', array($this, 'frontend_style_enqueue'), 10, 1);
        }
    }

    public function constants() {
        $data = get_file_data(__FILE__, array('Version'));
        $this->version = $data[0];
        $this->url = trailingslashit(plugin_dir_url(__FILE__));
        $this->dir = trailingslashit(plugin_dir_path(__FILE__));
    }

    public function i18n() {
        load_plugin_textdomain('builder-countdown', false, '/languages');
    }

    private function localization() {
        return apply_filters('builder_stopwatch_admin_script_vars', array(
            'closeButton' => __('Close', 'builder-countdown'),
            'buttonText' => __('Pick Date', 'builder-countdown'),
            'dateFormat' => 'yy-mm-dd',
            'timeFormat' => 'HH:mm:ss',
            'timepicker_url' => THEMIFY_METABOX_URI . 'js/jquery-ui-timepicker.min.js',
            'separator' => ' ',
            'url' => includes_url('js/jquery/ui/')
        ));
    }

    public function admin_enqueue() {
        wp_enqueue_script( 'themify-main-script', themify_enque(THEMIFY_URI.'/js/main.js'), array('jquery'), THEMIFY_VERSION, true );
        wp_enqueue_script('themify-builder-countdown-admin', themify_enque($this->url . 'assets/admin.js'), array('jquery'), $this->version, true);
        wp_localize_script('themify-builder-countdown-admin', 'builderCountDown', $this->localization());
        wp_enqueue_style('themify-builder-countdown-admin', themify_enque($this->url . 'assets/admin.css'), null, $this->version);
    }

    public function frontend_js_enqueue($js) {
        $js[] = array('src' => themify_enque($this->url . 'assets/admin.js'),
            'ver' => $this->version,
            'external' => Themify_Builder_Model::localize_js('builderCountDown', $this->localization())
        );
        return $js;
    }

    public function frontend_style_enqueue($styles) {
        $styles[] = themify_enque($this->url . 'assets/admin.css');
        $styles[] = THEMIFY_METABOX_URI . 'css/jquery-ui-timepicker.min.css';
        return $styles;
    }

    public function register_module() {
        //temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
        if (class_exists('Themify_Builder_Component_Module')) {
            Themify_Builder_Model::register_directory('templates', $this->dir . 'templates');
            Themify_Builder_Model::register_directory('modules', $this->dir . 'modules');
        }
    }

    public function updater() {
        if (class_exists('Themify_Builder_Updater')) {
            if (!function_exists('get_plugin_data')) {
                include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_basename = plugin_basename(__FILE__);
            $plugin_data = get_plugin_data(trailingslashit(plugin_dir_path(__FILE__)) . basename($plugin_basename));
            new Themify_Builder_Updater(array(
                'name' => trim(dirname($plugin_basename), '/'),
                'nicename' => $plugin_data['Name'],
                'update_type' => 'addon',
                    ), $this->version, trim($plugin_basename, '/'));
        }
    }

}

Builder_Countdown::get_instance();
