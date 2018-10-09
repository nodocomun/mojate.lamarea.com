<?php
/*
Plugin Name:  Builder Timeline
Plugin URI:   https://themify.me/addons/timeline
Version:      1.1.6
Author:       Themify
Description:  Display content in a timeline-styled layouts. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Text Domain:  builder-timeline
Domain Path:  /languages
*/

defined( 'ABSPATH' ) or die( '-1' );

class Builder_Timeline {
	public $url;
	private $dir;
	public $version;
	private $timeline_sources;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @return	A single instance of this class.
	 */
	public static function get_instance() {
            static $instance = null;
            if($instance===null){
                $instance = new self;
            }
            return $instance;
	}

	private function __construct() {
		$this->constants();
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 5 );
		add_action( 'plugins_loaded', array( $this, 'load_sources' ), 10 );
		add_action( 'themify_builder_setup_modules', array( $this, 'register_module' ) );
		add_filter( 'plugin_row_meta', array( $this, 'themify_plugin_meta'), 10, 2 );
                if(is_admin()){
                    add_action( 'init', array( $this, 'updater' ) );
                }
	}

	public function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	}

	public function themify_plugin_meta( $links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			$row_meta = array(
			  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'builder-timeline' ) . '">' . esc_html__( 'View Changelogs', 'builder-timeline' ) . '</a>'
			);
	 
			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	public function i18n() {
		load_plugin_textdomain( 'builder-timeline', false, '/languages' );
	}

	public function register_module() {
                //temp code for compatibility  builder new version with old version of addon to avoid the fatal error, can be removed after updating(2017.07.20)
                if(class_exists('Themify_Builder_Component_Module')){
                    Themify_Builder_Model::register_directory( 'templates', $this->dir . 'templates' );
                    Themify_Builder_Model::register_directory( 'modules', $this->dir . 'modules' );
                }
	}

	public function register_source( $name ) {
		$class_name = "Builder_Timeline_{$name}_Source";
		if( class_exists( $class_name ) ) {
                    $this->timeline_sources[$name] = new $class_name;
		}
	}

	public function load_sources() {
		foreach( array( 'post', 'text' ) as $source ) {
			include( $this->dir . 'includes/timeline-source-' . $source . '.php' );
			$this->register_source( $source );
		}
	}

	public function get_sources() {
		return apply_filters( 'builder_timeline_sources', $this->timeline_sources );
	}

	public function get_source( $name ) {
		if( isset( $this->timeline_sources[$name] ) ) {
			return $this->timeline_sources[$name];
		}
	}

	public function updater() {
		if( class_exists( 'Themify_Builder_Updater' ) ) {
			if ( ! function_exists( 'get_plugin_data') ){
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                        }
			$plugin_basename = plugin_basename( __FILE__ );
			$plugin_data = get_plugin_data( trailingslashit( plugin_dir_path( __FILE__ ) ) . basename( $plugin_basename ) );
			new Themify_Builder_Updater( array(
				'name' => trim( dirname( $plugin_basename ), '/' ),
				'nicename' => $plugin_data['Name'],
				'update_type' => 'addon',
			), $this->version, trim( $plugin_basename, '/' ) );
		}
	}
}
Builder_Timeline::get_instance();
