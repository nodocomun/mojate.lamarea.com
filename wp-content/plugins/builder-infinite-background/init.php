<?php
/*
Plugin Name:  Builder Infinite Background
Plugin URI:   http://themify.me/addons/infinite-background
Version:      1.0.9
Description:  This Builder addon allows you to set infinite scrolling row background image either horizontally or vertically in Row > Options. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-infinite-background
Domain Path:  /languages
*/

class Builder_Infinite_Background {

	public $version;
	public $url;
        private $speed = 5;

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

	/**
	 * Constructor
	 *
	 * @access	private
	 * @return	void
	 */
	private function __construct() {
		$this->constants();
                $is_admin = is_admin();
                if($is_admin || Themify_Builder_Model::is_front_builder_activate()){
                    add_filter( 'themify_builder_row_fields_options', array( $this, 'add_row_scrolling_background_option' ) );
                    add_filter( 'themify_builder_column_fields_styling', array( $this, 'add_column_scrolling_background_option' ) );
                    add_filter( 'themify_builder_subrow_fields_styling', array( $this, 'add_column_scrolling_background_option' ) );
                    if($is_admin){
                       add_action( 'init', array( $this, 'updater' ) );
                    }
                    else{
                        add_filter( 'themify_builder_addons_assets', array( $this, 'assets' ), 15, 1 );
                    }
                }
                else{
                    // Front end control
                    add_action( 'themify_builder_background_styling', array( $this, 'set_infinity_bg' ), 10, 4 );
                }
	}

	function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	public function assets($assets){ 
	    $assets['builder-infinite-background-front'] = array(
	        'selector' => 'body.themify_builder_active',
	        'js' => themify_enque($this->url . 'assets/frontend-scripts.js'),
	        'ver' => $this->version,
                'external' => Themify_Builder_Model::localize_js( 'BuilderInfiniteBg', array(
                        'horizontal' => self::get_bg_type('horizontally'),
                        'vertical' => self::get_bg_type('vertically'),
                        'speed'=>$this->speed
                ) )
	    );
	    return $assets;
	}
        
        
	public function get_options() {
		return array(
			array(
				'id' => 'separator_infinite_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Infinite Background', 'builder-infinite-background') . '</h4>'),
			),
			array(
				'id' => 'row_scrolling_background',
				'type' => 'radio',
				'label' => __('Infinite Background Image', 'builder-infinite-background'),
				'meta' => array(
					array('value' => 'bg-scroll-horizontally', 'name' => __('Scroll horizontally', 'builder-infinite-background')),
					array('value' => 'bg-scroll-vertically', 'name' => __('Scroll vertically', 'builder-infinite-background')),
					array('value' => '', 'name' => __('Disable', 'builder-infinite-background'), 'selected' => true)
				),
				'description' => '<small>' . __('This will make the row background image to scroll infinitely','builder-infinite-background') . '</small>',
				'wrap_with_class' => 'tb-group-element tb-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_speed',
				'type' => 'text',
				'label' => __('Scrolling speed', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => '<br/><small>' . __('Insert speed in seconds', 'builder-infinite-background') . '</small>',
				'wrap_with_class' => 'tb-group-element tb-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_width',
				'type' => 'text',
				'label' => __('Background Width', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => __('px', 'builder-infinite-background'),
				'wrap_with_class' => 'tb-group-element tb-group-element-image',
			),
			array(
				'id' => 'row_scrolling_background_height',
				'type' => 'text',
				'label' => __('Background Height', 'builder-infinite-background'),
				'class' => 'xsmall',
				'description' => __('px', 'builder-infinite-background'),
				'wrap_with_class' => 'tb-group-element tb-group-element-image',
			)
		);
	}

	/**
	 * Append user role option to row module.
	 */
	function add_row_scrolling_background_option( $options ) {

		$new_options = $this->get_options();
		/* determine the position to insert the new options to */
		$position = 15;

		return array_merge(
			array_slice( $options, 0, $position, true ),
			$new_options,
			array_slice( $options, $position, count( $options ) - $position, true )
		);
	}

	/**
	 * Append user role option to row module.
	 */
	function add_column_scrolling_background_option( $options ) {

		$new_options = $this->get_options();
		return array_merge(
			$options,
			$new_options
		);
	}


	private function do_infinite_bg( $row, $selector, $unique_id ) {
		if (!empty($row['styling']['row_scrolling_background']) && $row['styling']['row_scrolling_background'] !== 'disable' && $row['styling']['background_type']==='image' && !empty($row['styling']['background_image'])) {
                        if(empty($row['styling']['row_scrolling_background_speed'])){
                            $row['styling']['row_scrolling_background_speed'] = $this->speed;
                        }
			$speed = $row['styling']['row_scrolling_background_speed'] . 's';
                        $replace = array('#speed#'=>$speed,'#unique_id#'=>$unique_id);
                        $template = null;
			if ( $row['styling']['row_scrolling_background'] === 'bg-scroll-horizontally' ) {
                                if(!empty($row['styling']['row_scrolling_background_width'])){
                                    $template = self::get_bg_type('horizontally');
                                    $replace['#width#'] = $row['styling']['row_scrolling_background_width'];
                                }
			} elseif(!empty($row['styling']['row_scrolling_background_height'])) {
                                $template = self::get_bg_type('vertically');
                                $replace['#height#'] = $row['styling']['row_scrolling_background_height'];
			}
                        if($template!==null){
                            $template = strtr($template,$replace);
                            echo '<style scoped type="text/css">'. $selector . $template.'</style>';
                        }
		}

	}
        
        private static function get_bg_type($type){
            $return='{-webkit-animation: bg-animation-'.$type.'-#unique_id# #speed# linear infinite !important;
                        -moz-animation: bg-animation-'.$type.'-#unique_id# #speed# linear infinite !important;
                        animation: bg-animation-'.$type.'-#unique_id# #speed# linear infinite !important;
                        }';
            if($type==='horizontally'){
                $return.='@-webkit-keyframes bg-animation-horizontally-#unique_id# {
                                from { background-position: 0 0; }
                                to { background-position: -#width#px 0px; }
                        }
                        @-moz-keyframes bg-animation-horizontally-#unique_id# {
                                0% { background-position: left; }
                                100% { background-position: -#width#px 0%; }
                        }
                        @keyframes bg-animation-horizontally-#unique_id# {
                                from { background-position: 0 0; }
                                to { background-position: -#width#px 0px; }
                        }';
            }
            else{
                $return.='@-webkit-keyframes bg-animation-vertically-#unique_id# {
				   from { background-position: 0 0;}
					to { background-position: 0% -#height#px; }
				}
				@-moz-keyframes bg-animation-vertically-#unique_id# {
					from { background-position: 0 0; }
					to { background-position: 0% -#height#px; }
				}
				@keyframes bg-animation-vertically-#unique_id# {
					from { background-position: 0 0; }
					to { background-position: 0% -#height#px; }
				}';
            }
            return $return;
        }
        

	/**
	 * Control front end display of row module.
	 * @access	public
	 * @return	array
	 */
	public function set_infinity_bg($builder_id,$row,$order,$type ) {
            $selector = '.themify_builder_content-'.$builder_id.' .module_'.$type.'_'.$order;
            $this->do_infinite_bg( $row, $selector, $builder_id.'-'.$order);
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

add_action('themify_builder_setup_modules', array('Builder_Infinite_Background', 'get_instance'));
