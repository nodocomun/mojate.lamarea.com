<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    PTB
 * @author     Themify <ptb@themify.me>
 */
class PTB_Map_View_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string $plugin_name The name of the plugin.
     * @var      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_shortcode('ptb_map_view', array($this,'shortcode'));
    }


    private function enqueue_scripts() {
       if(!wp_script_is($this->plugin_name)){
            $plugin_url = plugin_dir_url(__FILE__);
            wp_register_script($this->plugin_name,PTB_Utils::enque_min($plugin_url . 'js/ptb-map.js'), array('ptb_extra'), $this->version, true);
            wp_localize_script($this->plugin_name, 'ptb_map', array(
                'lng'=> PTB_Utils::get_current_language_code(),
                'url'=>$plugin_url,
                'ver'=>$this->version,
                'map_key'=>PTB_Extra_Base::get_google_map_key()
            ));
            wp_enqueue_script($this->plugin_name);
            wp_enqueue_style($this->plugin_name, PTB_Utils::enque_min($plugin_url . 'css/ptb-map.css'), array(),$this->version, 'all');
            if (!wp_style_is('themify-font-icons-css')) {
                wp_enqueue_style('themify-font-icons-css', dirname(plugin_dir_url(__DIR__)) . '/themify-ptb/admin/themify-icons/font-awesome.min.css', array(), $this->version, 'all');
            }
       }
    }
    
    public function shortcode($atts){
        PTB_Public::$shortcode = true;
        $post_types = explode(',', esc_attr($atts['post_type']));
        $post_types = current($post_types);
        // WP_Query arguments
        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => $post_types,
            'post_status' => 'publish',
            'nopaging' => 1,
            'logic' => !empty($atts['logic']) ? $atts['logic'] : 'AND',
            'scroll'=>'enable',
            'drag'=>'enable',
            'drag_m'=>'yes',
            'road_type'=>'roadmap',
            'width'=>'100%',
            'height'=>'350px',
            'address_field'=>'',
            'marker'=>''
        );
        $args = wp_parse_args($atts, $args);
        if(empty($args['address_field'])){
            return '';
        }
        unset($atts);
        $taxes = array();
        foreach ($args as $key => $value) {

            if (strpos($key, 'ptb_tax_') !== false) {
                $key = str_replace('ptb_tax_', '', $key);
                $taxes[] = array(
                    'taxonomy' => esc_attr($key),
                    'field' => 'slug',
                    'terms' => explode(',', $value),
                    'operator' => $args['logic']
                );
            }
        }
        if (!empty($taxes)) {
            $args['tax_query'] = $taxes;
            $args['tax_query']['relation'] = $args['logic'];
            unset($args['logic']);
        }
        if (!isset(PTB_Form_PTT_Archive::$sortfields[$args['orderby']])) {
            $args['meta_key'] = 'ptb_' . $args['orderby'];
            $args['orderby'] = 'meta_value';
        }
     
        $args['meta_query'] = array(
                                    array(
                                        'key'=>'ptb_'.$args['address_field'],
                                        'compare'=>'EXISTS'
                                    )
        );
           
        // The Query
        $query = new WP_Query(apply_filters('themify_ptb_map_shortcode_query', $args));
        $html = ''; 
        // The Loop
        if ($query->have_posts()) {
            $this->enqueue_scripts();
            $data = array();
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $location = get_post_meta($id,'ptb_'.$args['address_field'],true);
                if($location){
                    $icon = wp_get_attachment_image_src( get_post_thumbnail_id($id),'thumbnail',true);
                    $data[] = array('u'=>apply_filters('themify_ptb_map_post_permalink',get_permalink(),$id),
                                    'i'=>apply_filters('themify_ptb_map_post_image', $icon[0]),
                                    't'=>apply_filters('themify_ptb_map_post_title', get_the_title(),$id),
                                    'l'=>$location
                                    );
                }
            }
            $data = esc_attr(base64_encode(json_encode($data)));
            if($args['marker'] && strpos($args['marker'],'http')!==false){
                $args['marker'] = PTB_CMB_Base::ptb_resize($args['marker'], 32, 32);
            }
            $map = array(
                'r'=>$args['road_type'],
                's'=>$args['scroll']==='enable'?1:0,
                'd'=>$args['drag']==='enable'?1:0,
                'dm'=>$args['drag_m']==='yes'?1:0,
                'w'=>$args['width'],
                'h'=>$args['height'],
                'm'=>$args['marker']
            );
            $map = esc_attr(base64_encode(json_encode($map)));
            $html='<div class="ptb_map_view_content">';
            $html.='<div data-map="'.$map.'" data-posts="'.$data.'" class="ptb_map_view"></div>';
            $html.='<input type="text" class="ptb_map_view_control ptb_map_view_input" placeholder="'.__('Enter a location', 'ptb_extra').'" />';
            $html.='</div>';
        }
        $query->reset_postdata();
        PTB_Public::$shortcode = false;
        return $html;
    }

}
