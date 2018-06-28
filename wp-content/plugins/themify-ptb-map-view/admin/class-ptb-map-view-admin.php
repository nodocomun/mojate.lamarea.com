<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    PTB
 * @author     Themify <ptb@themify.me>
 */
class PTB_Map_View_Admin {

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
    public function __construct($plugin_name, $version, $options) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = $options;
    }

    /**
     * Register the script/stylesheets for the adminpanel.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        $screen = get_current_screen();
        if ($screen->id !== 'customize') {
            $pluginurl = plugin_dir_url(dirname(__FILE__));
            wp_enqueue_style($this->plugin_name, PTB_Utils::enque_min($pluginurl . 'admin/css/ptb-map.css'), array(), $this->version, 'all');
            if (!wp_style_is('themify-font-icons-css')) {
                wp_enqueue_style('themify-font-icons-css', dirname(plugin_dir_url(__DIR__)) . '/themify-ptb/admin/themify-icons/font-awesome.min.css', array(), $this->version, 'all');
            }
        }
    }

    /**
     * Deactivate plugin if PTB is deactivated
     *
     * @since    1.0.0
     */
    public function deactivate() {
        deactivate_plugins(plugin_basename(__FILE__));
    }

    
    
    public function add_shortcode_icon() {

        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }
        //shortcodes
        if ('true' == get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this, 'ptb_add_shortcodes_buttons'));
            add_filter('mce_buttons', array($this, 'ptb_register_button'));
            if (is_admin()) {
                add_action('admin_footer', array($this, 'ptb_shortcode_icon'));
            } else {
                add_action('wp_footer', array($this, 'ptb_shortcode_icon'));
            }
        }
    }
    
    
    /**
    * Add shortcode JS to the page
    *
    * @return HTML
    */
    public function ptb_shortcode_icon() {
        $cpt = $this->options->get_custom_post_types();
        $menu = array();
        foreach ($cpt as $cp) {
            $cmb = $this->options->get_cpt_cmb_options($cp->slug);
            $is_map_type = false;
            foreach ($cmb as $c){
                if($c['type']==='map'){
                    $is_map_type = true;
                    break;
                }
            }
            if($is_map_type){
                $name = PTB_Utils::get_label($cp->plural_label);
                $menu[] = "{'type':'{$cp->slug}','name':'{$name}'}";
            }
        }
        if (!empty($menu)) {
            echo '<script type="text/javascript">
                            var shortcodes_map_button = new Array();
                            var $ptb_map_url = "' . admin_url('admin-ajax.php?action=' . $this->plugin_name . '_ajax_get_post_type') . '";';
            foreach ($menu as $k => $post_themes) {
                echo "shortcodes_map_button['$k']=$post_themes;";
            }
            echo '</script>';
        }
    }
    
     /**
     * get post_type data
     * Used from shortcode trough ajax call.
     *
     * @since 1.0.0
     */
    public function ptb_ajax_get_post_type() {
        if (!empty($_POST['post_type'])) {
            $post_type = $_POST['post_type'];
            $result = array();
            $templateObject = $this->options->get_post_type_template_by_type($post_type);
            if ($templateObject) {
                $cmb = $this->options->get_cpt_cmb_options($post_type);
                $cmb_fields = array();
                foreach($cmb as $k=>$c){
                    if($c['type']==='map'){
                        $cmb_fields[] = array('text'=>PTB_Utils::get_label($c['name']),'value'=>$k);
                    }
                }
                if(empty($cmb_fields)){
                    return;
                }
                $result['data']['address_field']['label'] = __('Custom Field','ptb_map');
                $result['data']['address_field']['type'] = 'listbox';
                $result['data']['address_field']['values'] = $cmb_fields;
                
                
                $cmb_options = $post_support = $post_taxonomies = array();
                $this->options->get_post_type_data($post_type, $cmb_options, $post_support, $post_taxonomies);
                if (array_search('category', $post_support) !== false) {
                    $post_taxonomies[] = 'category';
                }

                if (!empty($post_taxonomies)) {
                    foreach ($post_taxonomies as $k => $taxes) {
                        $values = get_categories(array(
                            'type' => $post_type,
                            'hide_empty' => 1,
                            'taxonomy' => $taxes
                        ));
                        if (empty($values)) {
                            continue;
                        }
                        $tax = get_taxonomy($taxes);
                        if ($tax) {
                            $result['taxes'][$k]['values'] = $values;
                            $result['taxes'][$k]['label'] = $tax->labels->name;
                            $result['taxes'][$k]['name'] = $taxes;
                        }
                    }
                }

                $sortable = PTB_Form_PTT_Archive::get_sort_fields($cmb_options);
                $fields  = $by = array();
                foreach ($sortable as $key => $s) {
                    $fields[] = array('text' => $s, 'value' => $key);
                }
                unset($sortable);
                $by[] = array(
                    'text' => __('Ascending', 'ptb_map'),
                    'value' => 'asc'
                );
                $by[] = array(
                    'text' => __('Descending', 'ptb_map'),
                    'value' => 'desc'
                );
                $archive = $templateObject->get_archive();
                unset($archive['layout']);
                $archive['offset'] = 0;
                unset($archive['ptb_ptt_layout_post'],$archive['offset'], $archive['ptb_ptt_pagination_post'], $archive['posts_per_page'], $archive['ptb_ptt_offset_post']);
                foreach ($archive as $key => $arh) {
                    $key = str_replace(array('ptb_ptt_', '_post'), '', $key);
                    $name = ucfirst(str_replace('_', ' ', $key));
                    $result['data'][$key] = array(
                        'label' => $name,
                        'value' => $arh
                    );
                    switch ($key) {
                        case 'order':
                            $result['data'][$key]['type'] = 'listbox';
                            $result['data'][$key]['values'] = $by;
                            break;
                        case 'orderby':
                            $result['data'][$key]['type'] = 'listbox';
                            $result['data'][$key]['values'] = $fields;
                            break;
                        default:
                            $result['data'][$key]['type'] = 'textbox';
                            break;
                    }
                }
                if(isset($result['taxes'])){
                    $result['data']['logic']['label'] = __('Taxonomies Logic','ptb_map');
                   
                    $result['data']['logic']['type'] = 'listbox';
                    $result['data']['logic']['values'] = array( 
                                                            array(
                                                                'text' => __('OR', 'ptb_map'),
                                                                'value' => 'or'
                                                            ),
                                                            array(
                                                                'text' => __('AND', 'ptb_map'),
                                                                'value' => 'and'
                                                            )
                                                        );
                }
            }
            
            
            $result['data']['road_type']['label'] = __('Type','ptb_map');
            $result['data']['road_type']['type'] = 'listbox';
            $result['data']['road_type']['values'] = array(
                array('text'=>__('Road Map','ptb_map'),'value'=>'roadmap'),
                array('text'=>__('Satellite','ptb_map'),'value'=>'satellite'),
                array('text'=>__('Hybrid','ptb_map'),'value'=>'hybrid'),
                array('text'=>__('Terrain','ptb_map'),'value'=>'terrain')
            );
            
            $result['data']['width']['label'] = __('Width','ptb_map');
            $result['data']['width']['type'] = 'textbox';
            $result['data']['width']['value'] = '100%';
            
            $result['data']['height']['label'] = __('Height','ptb_map');
            $result['data']['height']['type'] = 'textbox';
            $result['data']['height']['value'] = '350px';
            
            $result['data']['marker']['label'] = __('Marker Icon','ptb_map');
            $result['data']['marker']['html'] = '<div class="ptb_map_marker"><div id="ptb_marker_preview"></div><input class="mce-textbox mce-abs-layout" type="text" id="marker"/><a title="'.__('Icon Picker', 'ptb_map').'" href="'.dirname(plugin_dir_url(__DIR__)) . '/themify-ptb/admin/themify-icons/list.html" class="ptb_custom_lightbox">'.__('Select Icon', 'ptb_map').'</a> / <button  id="ptb_marker_file">'.__('Choose Image','ptb_map').'</button></div>';
            
            $enable =  array(
                array('text'=>__('Disable','ptb_map'),'value'=>'disable'),
                array('text'=>__('Enable','ptb_map'),'value'=>'enable'),
            );
            $result['data']['scroll']['label'] = __('Scrollwheel','ptb_map');
            $result['data']['scroll']['type'] = 'listbox';
            $result['data']['scroll']['values'] = $enable;
            
            $result['data']['drag']['label'] = __('Draggable','ptb_map');
            $result['data']['drag']['type'] = 'listbox';
            $result['data']['drag']['values'] = array_reverse($enable);
            
            $result['data']['drag_m']['label'] = __('Disable draggable on mobile','ptb_map');
            $result['data']['drag_m']['type'] = 'listbox';
            $result['data']['drag_m']['values'] = array(
                array('text'=>__('Yes','ptb_map'),'value'=>'yes'),
                array('text'=>__('No','ptb_map'),'value'=>'no'),
            );
            
            $result['title'] = __('PTB Map View Options', 'ptb_map');
            $result = apply_filters('ptb_map_ajax_shortcode_result', $result, $post_type);
            die(json_encode($result));
        }
    }

    
    /**
     * Add new Javascript to the plugin scrippt array
     *
     * @param  Array $plugin_array - Array of scripts
     *
     * @return Array
     */
    public function ptb_add_shortcodes_buttons($plugin_array) {
      
        $plugin_array[$this->plugin_name] = PTB_Utils::enque_min(plugin_dir_url(__FILE__) . 'js/ptb-map-shortcode.js');

        return $plugin_array;
    }

    /**
     * Add new button to the buttons array
     *
     * @param  Array $buttons - Array of buttons
     *
     * @return Array
     */
    public function ptb_register_button($buttons) {
        $buttons[] = $this->plugin_name;
        return $buttons;
    }

}
