<?php
/*
Plugin Name:  Builder BG Video Slider
Plugin URI:   http://themify.me/addons/bg-video-slider
Version:      1.0.5
Description:  This Builder addon allows you to set a video slider in Row > Options. It requires to use with the latest version of any Themify theme or the Themify Builder plugin.
Author:       Themify
Author URI:   http://themify.me/
Text Domain:  builder-bg-video-slider
Domain Path:  /languages
*/

class Builder_Slider_Videos {
        private $version;
	private $url;

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
                if(is_admin()){
                    add_action( 'init', array( $this, 'updater' ) );
                }
		else{
                    add_filter('themify_builder_addons_assets',array($this,'assets'),10,1);
                    add_filter('themify_main_script_vars',array( $this, 'minify_vars' ),10,1);
                }
		add_filter( 'themify_builder_row_fields_styling', array( $this, 'add_row_slider_videos_styling' ), 15 );
		add_filter( 'themify_builder_row_attributes', array( $this, 'row_attributes' ), 10, 3 );
	}

	private function constants() {
		$data = get_file_data( __FILE__, array( 'Version' ) );
		$this->version = $data[0];
		$this->url = trailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Append user role option to row module.
	 */
	function add_row_slider_videos_styling( $options ) {
		$slider_videos_options = array(
			array(
				'id' => 'background_slider_videos_autoplay',
				'label' => __( 'Auto play', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'yes', 'name' => __( 'Yes', 'builder-slider-videos' ) ),
					array('value' => 'no', 'name' => __( 'No', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_progressbar',
				'label' => __( 'Video progress bar', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'show', 'name' => __( 'Show', 'builder-slider-videos' ) ),
					array('value' => 'hide', 'name' => __( 'Hide', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_controls',
				'label' => __( 'Video controls', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'show', 'name' => __( 'Show', 'builder-slider-videos' ) ),
					array('value' => 'hide', 'name' => __( 'Hide', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos_mute',
				'label' => __( 'Video mute', 'builder-slider-videos' ),
				'type' => 'select',
				'meta' => array(
					array('value' => 'no', 'name' => __( 'No', 'builder-slider-videos' ) ),
					array('value' => 'yes', 'name' => __( 'Yes', 'builder-slider-videos' ) ),
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-slidervideos'
			),
			array(
				'id' => 'background_slider_videos',
				'type' => 'builder',
				'new_row_text' => __( 'Add new video', 'builder-slider-videos' ),
				'options' => array(
					array(
						'id' => 'background_slider_videos_video',
						'type' => 'video',
						'label' => __( 'Video', 'builder-slider-videos' ),
						'description' => __( 'Video format: mp4. Note: video background does not play on mobile, image below will be used as fallback.', 'builder-slider-videos' ),
						'class' => 'fullwidth',
                                                'render_callback' => array(
                                                    'repeater' => 'background_slider_videos',
                                                    'control_type'=>'change',
                                                    'binding'=>'live'
                                                )
					),
					array(
						'id' => 'background_slider_videos_image',
						'type' => 'image',
						'label' => __( 'Fallback image', 'builder-slider-videos' ),
						'class' => 'fullwidth',
                                                'render_callback' => array(
                                                    'repeater' => 'background_slider_videos',
                                                    'control_type'=>'change',
                                                    'binding'=>'live'
                                                )
					)
				),
				'wrap_with_class' => 'tb-group-element tb-group-element-slidervideos tb-group-element-slidervideos-videos'
			)
		);

		foreach ( $options as $key => $option ) {
			if ( isset( $option['id'] ) && $option['id'] === 'background_type' ) {
				$options[$key]['meta'][] = array( 'value' => 'slidervideos', 'name' => __( 'Slider Videos', 'builder-slider-videos' ),'wrap_with_class'=>'reponive_disable' );
				$position = $key+1;

				$options = array_merge(
					array_slice( $options, 0, $position, true ),
					$slider_videos_options,
					array_slice( $options, $position, count( $options ) - $position, true )
				);
			}
		}

		return $options;
	}

	/**
	 * Control front end display of row module.
	 * @access	public
	 * @return	array
	 */
	public function row_attributes( $attr, $row,$builder_id ) {
            if (isset( $row['background_type'] )
                && $row['background_type'] === 'slidervideos'
                && ! empty( $row['background_slider_videos'] )
                && is_array( $row['background_slider_videos'] )
                ) {
                if(isset($row['background_slider_videos_autoplay']) && $row['background_slider_videos_autoplay']==='yes'){
                    $attr['data-tb_slider_autoplay'] = 'yes';
                }
                if(isset($row['background_slider_videos_progressbar']) && $row['background_slider_videos_progressbar']==='show'){
                    $attr['data-tb_slider_progressbar'] = 'show';
                }
                if(isset($row['background_slider_videos_controls']) && $row['background_slider_videos_controls']==='show'){
                    $attr['data-tb_slider_controls'] = 'show';
                }
                if(isset($row['background_slider_videos_mute']) && $row['background_slider_videos_mute']==='yes'){
                    $attr['data-tb_slider_mute'] = 'yes';
                }
                $attr['data-tb_slider_videos'] = json_encode($row['background_slider_videos']);
            }
            return $attr;
	}
        
        public function assets($assets){
            $localization = array( 'url' => $this->url );
            if(!Themify_Builder_Model::is_front_builder_activate()){
                $assets['builder-bg-video-slider']=array(
                            'selector'=>'[data-tb_slider_videos]',
                            'css'=>themify_enque($this->url .'assets/frontend-styles.css'),
                            'js'=>themify_enque($this->url .'assets/frontend-scripts.js'),
                            'ver'=>$this->version,
                            'external'=>Themify_Builder_Model::localize_js('tb_slider_videos_vars',$localization)
                
                        );
            }
            else{
                wp_register_script( 'themify_enque-slider-videos-admin-scripts', themify_enque($this->url . 'assets/admin-scripts.js'), array( 'jquery' ), $this->version,true );
                wp_localize_script('themify_enque-slider-videos-admin-scripts', 'tb_slider_videos_vars', $localization);
                wp_enqueue_script( 'themify_enque-slider-videos-admin-scripts');
            }
            return $assets;
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
        public function minify_vars($vars){
            if(Themify_Builder_Model::is_front_builder_activate()){
                $vars['minify']['js']['frontend-scripts'] = themify_enque($this->url . 'assets/frontend-scripts.js',true);
                $vars['minify']['css']['frontend-styles'] = themify_enque($this->url . 'assets/frontend-styles.css',true);
            }
            return $vars;
        }
}

Builder_Slider_Videos::get_instance();
