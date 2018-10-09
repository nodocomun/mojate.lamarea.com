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
 * @subpackage PTB/public
 * @author     Themify <ptb@themify.me>
 */
class PTB_Public {

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
    private static $options = false;
    private static $template = false;
    private static $is_single = false;
    private static $output = '';
    private static $is_working = false;
    public static $render_content = false;
    public static $shortcode = false;
    private static $is_disabled = false;
    private static $post_ids = array();
    private static $loop_start = 0;

    /**
     * List of directories where PTB must look for templates
     */
    public $template_directories = null;
    private $url = '';

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

        $this->plugin_name = PTB::$plugin_name;
        $this->version = PTB::get_version();
        self::$options = PTB::get_option();
        
        add_action('wp_head', array($this, 'ptb_filter_wp_head'));
        add_action('body_class', array($this, 'ptb_filter_body_class'));
        add_filter('pre_get_posts', array($this, 'ptb_filter_cpt_category_archives'), 99, 1);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 99);
        add_action('wp_head', array($this, 'define_ajaxurl'));
        add_action('loop_start', array($this, 'loop_start'));
        add_filter('the_title', array($this, 'ptb_filter_post_type_title'), 100, 1);
        add_filter('post_thumbnail_html', array($this, 'ptb_post_thumbnail'), 100, 1);
        
        add_shortcode($this->plugin_name, array($this, 'ptb_shortcode'));
        add_filter('widget_text', array($this, 'widget_text'), 10, 2);
        add_filter('widget_text_content', array($this, 'get_ptb_shortcode'), 12);
        add_filter('widget_posts_args', array($this, 'disable_ptb'), 10, 1);
        add_action('init', array($this, 'register_template_directories'));
        add_action( 'admin_bar_menu', array( $this, 'remove_builder_bar_button' ), 999 );	
		
        // #6316 issue Fix
        add_filter('redirect_canonical',array( $this, 'pif_disable_redirect_canonical' ));
		
    }
    public function remove_builder_bar_button( $wp_admin_bar ) {
        if( is_singular() ) {
            $template = self::$options->get_post_type_template_by_type( get_post_type() );
            $has_editor = ! empty( $template ) && $template->get_single()
                    ? preg_match_all( '/\"type\":\"editor\"/', json_encode( $template->get_single() ) ) !== 0 : true;

            ! $has_editor && $wp_admin_bar->remove_node( 'themify_builder' );
        }
    }
    public function pif_disable_redirect_canonical($redirect_url) {
        if (is_singular()){
            $redirect_url = false;
        }
        return $redirect_url;
    }
    
    function register_template_directories() {
        if (empty($this->template_directories)) {
            $defaults = array(
                4 => trailingslashit(PTB::get_instance()->dir) . 'templates',
                9 => trailingslashit(get_template_directory()) . 'plugins/themify-ptb/templates',
            );
            if (is_child_theme()) {
                $defaults[10] = trailingslashit(get_stylesheet_directory()) . 'plugins/themify-ptb/templates';
            }
            $template_directories = apply_filters('themify_ptb_template_directories', $defaults);
            ksort($template_directories, SORT_NUMERIC);
            $this->template_directories = array_reverse($template_directories);
        }

        return $this->template_directories;
    }

    /**
     * Retrieve list of directories where PTB should look for template files
     *
     * Higher priority directories are higher in the list
     *
     * @return array
     */
    public function get_template_directories() {
        return $this->template_directories;
    }

    /**
     * Search for a template file inside all registered template directories
     *
     * @return string|false
     */
    public function locate_template($names,$type) {
        static $result = array();
        $names = (array) $names;
        if (!isset($result[$type])) {
            $dirs = $this->get_template_directories();
            foreach ($names as $file) {
                foreach ($dirs as $dir) {
                    $f = trailingslashit($dir) . $file;
                    if (file_exists($f)) {
                        $result[$type] = $f;
                        return $f;
                    }
                }
            }
            $result[$type] = false;
        } else {
            return $result[$type];
        }

        return false;
    }

    /**
     * Register the Javascript/Stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in PTB_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The PTB_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $this->url = $plugin_url = plugin_dir_url(__FILE__);
        $min_files = array(
            'css'=>array(
                'lightbox'=>PTB_Utils::enque_min($this->url . 'css/lightbox.css',true)
            ),
            'js'=>array()
        );
        $min_files = apply_filters('ptb_min_filess',$min_files);
        $translation_ = array(
            'url' => $plugin_url,
            'ver' => $this->version,
            'min'=>$min_files,
            'include'=>includes_url('js/')
        );

        wp_register_script($this->plugin_name, PTB_Utils::enque_min($plugin_url . 'js/ptb-public.js'), array(), $this->version, false);
        wp_localize_script($this->plugin_name, 'ptb', $translation_);
        global $wp_styles;
        $is_fontawesome_loaded = false;

        $srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src'));
        foreach ($srcs as $handler => $sr) {
            if ((strpos($sr, 'font-awesome') !== false || strpos($sr, 'fontawesome') !== false) && in_array($handler, $wp_styles->queue,true)) {
                $is_fontawesome_loaded = true;
                break;
            }
        }
        if (!$is_fontawesome_loaded) {
            wp_enqueue_style('themify-font-icons-css2', plugin_dir_url(dirname(__FILE__)) . 'admin/themify-icons/font-awesome.min.css', array(), $this->version, 'all');
        }
        wp_enqueue_style($this->plugin_name . '-colors', PTB_Utils::enque_min(plugin_dir_url(dirname(__FILE__)) . 'admin/themify-icons/themify.framework.css'), array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, PTB_Utils::enque_min($plugin_url . 'css/ptb-public.css'), array(), $this->version, 'all');
        wp_enqueue_script($this->plugin_name);
        add_filter('script_loader_tag', array($this, 'defer_js'), 11, 3);
        self::$is_single = is_singular();
        if(self::$is_single && !PTB_Utils::is_themify_theme()){
            $templateObject = self::$options->get_post_type_template_by_type(get_post_type());
            remove_action('loop_start', array($this, 'loop_start'));
            if(isset($templateObject) && $templateObject->has_single()){
                add_filter('post_class', array($this, 'filter_class'));
            }
        }
    }

    /**
     * Register the ajax url
     *
     * @since    1.0.0
     */
    public static function define_ajaxurl() {
        ?>
        <script type="text/javascript">
            ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }

    public function ptb_filter_wp_head() {
        $option = PTB::get_option();
        $custom_css = $option->get_custom_css();
        if ($custom_css) {
            echo '<!-- PTB CUSTOM CSS --><style type="text/css">' . $custom_css . '</style><!--/PTB CUSTOM CSS -->';
        }
    }

    public function ptb_filter_body_class($classes) {
        $post_type = get_post_type();
        $templateObject = self::$options->get_post_type_template_by_type($post_type);
        if (is_null($templateObject)) {
            return $classes;
        }
        $type = self::$template ? 'archive' : (self::$is_single && $templateObject->has_single() && is_singular($post_type) ? 'single' : false);
        if ($type !== false) {
            $classes[] = $this->plugin_name . '_' . $type;
            $classes[] = $this->plugin_name . '_' . $type . '_' . $post_type;
        }
        return $classes;
    }

    /**
     * Enable shortcodes in Text widget
     *
     * @return string
     */
    function widget_text($text, $instance = array()) {
		global $wp_widget_factory;

		/* check for WP 4.8.1+ widget */
		if( isset( $wp_widget_factory->widgets['WP_Widget_Text'] ) && method_exists( $wp_widget_factory->widgets['WP_Widget_Text'], 'is_legacy_instance' ) && ! $wp_widget_factory->widgets['WP_Widget_Text']->is_legacy_instance( $instance ) ) {
			return $text;
		}

		/*
		 * if $instance['filter'] is set to "content", this is a WP 4.8 widget,
		 * leave it as is, since it's processed in the widget_text_content filter
		 */
		if( isset( $instance['filter'] ) && 'content' === $instance['filter'] ) {
			return $text;
		}

		return $this->get_ptb_shortcode($text);
    }

    public function get_ptb_shortcode($text) {
        if ($text && has_shortcode($text, $this->plugin_name)) {
            $text = PTB_CMB_Base::format_text($text);
            $text = shortcode_unautop(do_shortcode($text));
        }
        return $text;
    }

    /** 		
     * @param $title		
     * @param null $id		
     * 		
     * @return string		
     */
    public function ptb_filter_post_type_title($title, $id = null) {
        if ($id !== get_the_ID() || self::$shortcode) {
            return $title;
        }
        $post_type = get_post_type();
        $templateObject = self::$options->get_post_type_template_by_type($post_type);
        return isset($templateObject) && ((self::$is_single && is_singular($post_type) && $templateObject->has_single()) || (self::$template && $templateObject->has_archive())) ? '' : $title;
    }

    /* 		
     * @since 1.0.0		
     * 		
     * @param $content		
     * 		
     * @return string		
     */

    public function get_content() {
        global $post;
        $post_type = $post->post_type;
        $template = '';
        self::$is_working = true;
        $templateObject = self::$options->get_post_type_template_by_type($post_type);
        if($templateObject){
            $id = get_the_ID();
            if (post_password_required()){
                 return get_the_password_form($id);
             } elseif (function_exists('wpml_get_language_information')) {
                 $post_lang = wpml_get_language_information($id);
                 $lang = PTB_Utils::get_current_language_code();
                 if(!empty($post_lang['language_code']) && strtolower($post_lang['language_code']) !== $lang && ($lang !== 'all' || !empty($lang))){
                     self::$is_working = false;
                     return $template;
                 }
             }
             $single = self::$is_single && $templateObject->has_single() && is_singular($post_type);
             $archive = !$single && self::$template;
             if ($single || $archive) {
                 $cmb_options = $post_support = $post_taxonomies = array();
                 self::$options->get_post_type_data($post_type, $cmb_options, $post_support, $post_taxonomies);
                 $post_meta = array_merge(array(), get_post_custom(), get_post('', ARRAY_A));
                 $post_meta['post_url'] = get_permalink();
                 $post_meta['taxonomies'] = !empty($post_taxonomies) ? wp_get_post_terms($id, array_values($post_taxonomies)) : array();
                 $themplate = new PTB_Form_PTT_Them($this->plugin_name, $this->version);
                 $themplate_layout = $single ? $templateObject->get_single() : $templateObject->get_archive();

                 if (isset($themplate_layout['layout']) && ($single || !in_array($id, self::$post_ids))) {
                        self::$post_ids[] = $id;
                        $template= '<article id="post-' . $id . '" class="' . implode(' ', get_post_class(array('ptb_post', 'clearfix'))) . '">';
                        $template.= $themplate->display_public_themplate($themplate_layout, $post_support, $cmb_options, $post_meta, $post_type, $single);
                        $template.='</article>';
                 }
             }
        }
        self::$is_working = false;
        return $template;
    }
    public function filter_class($classes) {
         if (!self::$shortcode) {
            if(self::$is_single && !PTB_Utils::is_themify_theme()){
                if(self::$is_working){
                    return $classes;
                }
                self::$output = $this->get_content();
                if(self::$output){
                        add_filter('the_content', array($this, 'single_content'),100,1);
                        add_filter('the_excerpt', array($this, 'single_content'),100,1);
                        $classes[]='ptb_single_content';
                }
            }
            $classes[] = 'ptb_post clearfix';
            $categories = wp_get_object_terms( get_the_id(), 'category' );
            if ( ! is_wp_error( $categories ) ) {
                foreach ( $categories as $cat ) {
                    if ( is_object( $cat ) ) {
                            $classes[] = 'cat-' . $cat->term_id;
                    }
                }
            }
        }
        return $classes;
    }
    

    public function loop_start() {
        if (self::$shortcode) {
            return;
        }
        if (!self::$is_disabled) {
			
            ++self::$loop_start;
            if (self::$loop_start > 1) {
                     return;
            }
            $register = false;
            if (self::$template) {
                self::$post_ids = array();
                $data = self::$template->get_archive();
                if (!is_category()) {
                    $grid = 'ptb_' . $data[self::$options->prefix_ptt_id . 'layout_post'];
                } else {
                    $grid = '';
                }
                if(!empty($data[self::$options->prefix_ptt_id . 'masonry']) && $grid!=='list-post'){
                    $grid.=' ptb_masonry';
                }
                unset($data);
                $register = true;
                echo '<div class="ptb_loops_wrapper ' . $grid . ' clearfix">';
            } elseif (self::$is_single) {
                $templateObject = self::$options->get_post_type_template_by_type(get_post_type());
                $register = isset($templateObject) && $templateObject->has_single();
            }
            if ($register === true) {
                add_filter('post_class', array($this, 'filter_class'));
                add_action('the_post', array($this, 'output'), 100, 1);
                if (self::$template) {
                    add_action('loop_end', array($this, 'loop_end'));
                    ob_start();
                } elseif (self::$is_single) {
                    add_action('themify_content_start', array($this, 'single_loop'));
                }
            }
        } else {
            add_action('loop_end', array($this, 'enable_loop'));
        }
    }
    public function output($post) {
         if (!self::$shortcode && !self::$is_working) {
            self::$output.=$this->get_content();
            if (self::$is_single && self::$output) {
                 remove_action('the_post', array($this, 'output'), 100, 1);
             }
          }
    }
    
    public function single_content($content){
        if(self::$is_working){
            return $content;
        }
        echo  self::$output;
        remove_filter('the_content', array($this, 'single_content'),100,1);
        remove_filter('the_excerpt', array($this, 'single_content'),100,1);
        remove_filter('post_class', array($this, 'filter_class'));
        return '';
    }

    public function single_loop() {
         add_action('themify_content_end', array($this, 'loop_end'));
         ob_start();
    }

    public function enable_loop() {
        if (!self::$shortcode) {
            self::$is_disabled = false;
            remove_action('loop_end', array($this, 'enable_loop'));
        }
    }

    public function loop_end() {
        if (!self::$shortcode) {
            --self::$loop_start;
            if(self::$loop_start === 0){
                    ob_end_clean();
                    echo self::$output;
                    if(self::$is_single){
                        remove_action('loop_start', array($this, 'loop_start'));
                        remove_filter('the_title', array($this, 'ptb_filter_post_type_title'), 100, 1);
                        remove_filter('post_thumbnail_html', array($this, 'ptb_post_thumbnail'), 100, 1);
                    }
                    elseif (self::$template) {
                            echo '</div>';
                    }
                    self::$output = '';
                    remove_filter('post_class', array($this, 'filter_class'));
                    remove_action('the_post', array($this, 'output'), 100, 1);
                    remove_action('loop_end', array($this, 'loop_end'));
                    remove_action('themify_content_end', array($this, 'loop_end'));
                    remove_action('themify_content_start', array($this, 'single_loop'));

            }
        }
    }

    public function ptb_post_thumbnail($html) {
        if (!self::$shortcode) {

            $post_type = get_post_type();
            $templateObject = self::$options->get_post_type_template_by_type($post_type);
            return isset($templateObject) && ((is_singular($post_type) && $templateObject->has_single()) || (self::$template && $templateObject->has_archive())) ? '' : $html;
        }
        return $html;
    }

    /** 		
     * @param WP_Query $query		
     * 		
     * @return WP_Query		
     */
    public function ptb_filter_cpt_category_archives(&$query) {
        if (self::$shortcode || !empty($query->query['ptb_disable']) || self::$is_disabled || is_admin()) {
            return $query;
        }
        if (!is_singular() && !is_feed($query) && ($query->is_category() || $query->is_post_type_archive() || $query->is_tag() || $query->is_tax()) && (!isset($query->query_vars['suppress_filters']) || $query->query_vars['suppress_filters'])) {
            $post_type = false;
            if (isset($query->query['post_type']) && $query->is_post_type_archive()) {
                $args = array();
                $t = self::$options->get_post_type_template_by_type($query->query['post_type']);
                if ($t && $t->has_archive()) {
                    self::$template = $t;
                    $post_type = $query->query['post_type'];
                    $args[] = $post_type;
                }
            } elseif (!empty($query->tax_query->queries)) {
                $tax = $query->tax_query->queries;
                ksort( $tax );
                $tax = current($tax);
                $taxonomy = ! empty( $tax['taxonomy'] ) ? get_taxonomy( $tax['taxonomy'] ) : false;	
                if ($taxonomy && !empty($tax['terms'])) {
                    $args = $taxonomy->object_type;
                    if ($args) {
                        array_reverse($args);
                        self::$is_disabled = true;
                        $tmp_args = array(
                            'post_status' => 'publish',
                            'posts_per_page' => 1,
                            'no_found_rows' => true,
                            'orderby' => 'none',
                            'tax_query' => array($tax)
                        );
                        unset($tax);
                        foreach ($args as $type) {
                            $t = self::$options->get_post_type_template_by_type($type);
                            if ($t && $t->has_archive()) {
                                $tmp_args['post_type'] = $type;

                                $tmp_query = get_posts($tmp_args);
                                if (!empty($tmp_query)) {
                                    self::$template = $t;
                                    $post_type = $type;
                                    unset($tmp_query);
                                    break;
                                }
                            }
                        }
                        if ($post_type) {
                            wp_reset_postdata();
                        }
                        self::$is_disabled = false;
                    }
                }
            }
            if ($post_type) {
                $archive = self::$template->get_archive();

                if ($archive['ptb_ptt_pagination_post'] > 0) {
                    if ($archive['ptb_ptt_offset_post'] > 0) {
                        $query->set('posts_per_page', intval($archive['ptb_ptt_offset_post']));
                    }
                } else {
                    $query->set('nopaging', 1);
                    $query->set('no_found_rows', 1);
                }
                if (isset(PTB_Form_PTT_Archive::$sortfields[$archive['ptb_ptt_orderby_post']])) {
                    $query->set('orderby', $archive['ptb_ptt_orderby_post']);
                } else {
                    $cmb_options = self::$options->get_cpt_cmb_options($post_type);
                    if (isset($cmb_options[$archive['ptb_ptt_orderby_post']])) {
                        $sort = $cmb_options[$archive['ptb_ptt_orderby_post']]['type'] === 'number' && empty($cmb_options[$archive['ptb_ptt_orderby_post']]['range']) ? 'meta_value_num' : 'meta_value';
                        $query->set('orderby', $sort);
                        $query->set('meta_key', $this->plugin_name . '_' . $archive['ptb_ptt_orderby_post']);
                    }
                }
                $query->set('order', $archive['ptb_ptt_order_post']);
                $query->set('post_type', $args);
                if ($query->is_main_query()) {
                    $query->set('suppress_filters', true); //wpml filter	
                }
            }
        } elseif ($query->is_main_query() && is_search()) {
            $post_types = self::$options->get_custom_post_types();
            if (!empty($post_types)) {
                $searchable_types = array('post', 'page');
                foreach ($post_types as $type) {
                    if (empty($type->ad_exclude_from_search)) {
                        $searchable_types[] = $type->slug;
                    }
                }
                $query->set('post_type', $searchable_types);
            }
        }

        return apply_filters( 'ptb_filter_cpt_category_archives', $query, self::$template );
    }

    /**
     * @since 1.0.0
     *
     * @param $atts
     *
     * @return string|void
     */
    public function ptb_shortcode($atts) {

        $post_types = explode(',', esc_attr($atts['type']));
        $type = current($post_types);
        $template = self::$options->get_post_type_template_by_type($type);
        if (null == $template) {
            return;
        }
        unset($atts['type']);
        // WP_Query arguments
        $args = array(
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => $type,
            'post_status' => 'publish',
            'nopaging' => false,
            'style' => 'list-post',
            'post__in' => isset($atts['ids']) && $atts['ids'] ? explode(',', $atts['ids']) : '',
            'posts_per_page' => isset($atts['posts_per_page']) && intval($atts['posts_per_page']) > 0 ? $atts['posts_per_page'] : get_option('posts_per_page'),
            'paged' => isset($atts['paged']) && $atts['paged'] > 0 ? intval($atts['paged']) : (is_front_page() ? get_query_var('page', 1) : get_query_var('paged', 1)),
            'logic' => 'AND',
            'not_found' => '',
        );
        if (isset($atts['offset']) && intval($atts['offset']) > 0) {
            $args['offset'] =(int)$atts['offset'];
        }
		if (isset($atts['ptb_widget'])) {
			unset($atts['ptb_widget']);
            $ptb_widget = true;
            $args['paged'] = isset($atts['paged']) && $atts['paged'] > 0 ? intval($atts['paged']) : get_query_var('ptb_paged', 1);
        }
        $args = wp_parse_args($atts, $args);
		$return = isset( $atts['return'] ) ? $atts['return'] : 'html';
        unset($atts);
        if (!$args['paged'] || !is_numeric($args['paged'])) {
            $args['paged'] = 1;
        }
        if (empty($args['pagination'])) {
            $args['no_found_rows'] = 1;
        }
        if (isset($args['post_id']) && is_numeric($args['post_id'])) {
            $args['p'] = $args['post_id'];
            $args['style'] = '';
        } else {
            $taxes = $conditions = $meta = array();
            $post_taxonomies = $cmb_options = $post_support = array();
            self::$options->get_post_type_data($type, $cmb_options, $post_support, $post_taxonomies);
            if (isset($post_support['category'])) {
                $post_taxonomies[] = 'category';
            }
            if (isset($post_support['post_tag'])) {
                $post_taxonomies[] = 'post_tag';
            }
            foreach ($args as $key => $value) {
                if (!is_array($value)) {
                    $value = trim($value);
                }
                if ($value || $value == '0') {
                    if (strpos($key, 'ptb_tax_') === 0) {
                        $origk = str_replace('ptb_tax_', '', $key);
                        if (in_array($origk, $post_taxonomies)) {
                            $taxes[] = array(
                                'taxonomy' => sanitize_key($origk),
                                'field' => 'slug',
                                'terms' => explode(',', $value),
                                'operator' => !empty($args[$origk . '_operator']) ? $args[$origk . '_operator'] : 'IN',
                                'include_children' => !empty($args[$origk . '_children']) ? false : true,
                            );
                        }
                        unset($args[$key], $args[$origk . '_operator'], $args[$origk . '_children']);
                    } elseif (strpos($key, 'ptb_meta_') === 0) {
                        $origk = sanitize_key(str_replace('ptb_meta_', '', $key));
                        if (!isset($cmb_options[$origk]) && strpos($origk, '_exist') !== false) {
                            $origk = str_replace('_exist', '', $origk);
                        }
                        if (isset($cmb_options[$origk]) || isset($args[$origk . '_from']) || isset($args[$origk . '_to'])) {
                           /* if (!empty($args[$key . '_exist'])) { */
                            if (!empty($args['ptb_meta_' . $origk . '_exist'])) {
                                $meta[$origk] = array(
                                    'key' => 'ptb_' . $origk,
                                    'compare' => '!=',
                                    'value'=>''
                                );
                            } else {
                                $cmb = $cmb_options[$origk];
                                $mtype = isset($args[$origk . '_from']) || isset($args[$origk . '_to']) ? 'number' : $cmb['type'];
                                switch ($mtype) {
                                    case 'checkbox':
                                    case 'select':
                                    case 'radio_button':
                                        if (empty($cmb['options'])) {
                                            continue 2;
                                        }
                                        if ($mtype === 'select' || $mtype === 'checkbox') {
                                            $value = explode(',', $value);
											$value = array_map(function($val) { return '"'.$val.'"'; }, $value); // Similar to #7153
                                            $args['post__in'] = self::parse_multi_query($value, $type, $origk, $args['post__in']);

                                            if (!$args['post__in']) {
                                                return '';
                                            }
                                        } else {
                                            $temp_found = false;
                                            foreach($cmb['options'] as $temp_opt){
                                                    if(in_array($value, $temp_opt)){
                                                            $temp_found = true;
                                                            break;
                                                    }
                                            }
                                            if (!$temp_found) {
                                                return '';
                                            }
                                            $meta[$origk] = array(
                                                'key' => 'ptb_' . $origk,
                                                'compare' => '=',
                                                'value' => $value
                                            );
                                        }

                                        break;
                                    case 'text':
                                        $slike = !empty($args[$origk . '_slike']);
                                        $elike = !empty($args[$origk . '_elike']);

                                        if (!$cmb['repeatable']) {
                                            $meta[$origk] = array(
                                                'key' => 'ptb_' . $origk,
                                                'compare' => '=',
                                                'value' => $value
                                            );
                                            if ($slike && $elike) {
                                                $meta[$origk]['compare'] = 'LIKE';
                                            } elseif ($slike) {
                                                $meta[$origk]['compare'] = 'REGEXP';
                                                $meta[$origk]['value'] = '^' . $meta[$origk]['value'];
                                            } elseif ($elike) {
                                                $meta[$origk]['compare'] = 'REGEXP';
                                                $meta[$origk]['value'] = $meta[$origk]['value'] . '$';
                                            }
                                        } else {
                                            $post_id = self::parse_multi_query(explode(',', $value), $type, $origk, $args['post__in'], true);
                                            if (empty($post_id)) {
                                                return '';
                                            }
                                            foreach ($post_id as $i => $p) {
                                                $m = get_post_meta($p, 'ptb_' . $origk, true);
                                                if (empty($m)) {
                                                    unset($post_id[$i]);
                                                } else {
                                                    if (!is_array($m)) {
                                                        $m = array($m);
                                                    }
                                                    if (!$slike && !$elike && !in_array($value, $m)) {// compare =
                                                        unset($post_id[$i]);
                                                    } else {//compare like %s%,%s or s%
                                                        $find = false;
                                                        $reg = $slike ? '/^' . $value . '/iu' : '/' . $value . '$/iu';
                                                        foreach ($m as $m1) {
                                                            if ($slike && $elike) {
                                                                if (strpos($m1, $value) !== false) {//compare  %s%
                                                                    $find = true;
                                                                    break;
                                                                }
                                                            } else {
                                                                if (preg_match($reg, $m1)) {
                                                                    $find = true;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if (!$find) {
                                                            unset($post_id[$i]);
                                                        }
                                                    }
                                                }
                                            }
                                            if (empty($post_id)) {
                                                return '';
                                            }
                                            $args['post__in'] = $post_id;
                                        }
                                        unset($args[$origk . '_elike'], $args[$origk . '_slike']);
                                        break;
                                    case 'number':
                                        if (empty($cmb['range']) && !isset($meta[$origk])) {
                                            $from_val = isset($args[$origk . '_from']) && is_numeric($args[$origk . '_from']) ? $args[$origk . '_from'] : false;
                                            $to_val = isset($args[$origk . '_to']) && is_numeric($args[$origk . '_to']) ? $args[$origk . '_to'] : false;
                                            $from_sign = $from_val && !empty($args[$origk . '_from_sign']) ? html_entity_decode($args[$origk . '_from_sign'], ENT_QUOTES, 'UTF-8') : false;
                                            $to_sign = $to_val && $from_sign !== '=' && !empty($args[$origk . '_to_sign']) ? html_entity_decode($args[$origk . '_to_sign'], ENT_QUOTES, 'UTF-8') : false;
                                            $meta[$origk] = array(
                                                'key' => 'ptb_' . $origk,
                                                'compare' => '=',
                                                'value' => $from_val,
                                                'type' => 'DECIMAL'
                                            );
                                            if ($from_sign !== '=') {
                                                if ($from_sign === '>=' && $to_sign === '<=') {
                                                    $meta[$origk]['compare'] = 'BETWEEN';
                                                    $meta[$origk]['value'] = array($from_val, $to_val);
                                                } elseif ($from_sign === '>' || $from_sign === '>=') {
                                                    $meta[$origk]['compare'] = $from_sign;
                                                }
                                                if ($to_sign === '<' || $to_sign === '<=') {
                                                    $meta[$origk . '_to'] = $meta[$origk];
                                                    $meta[$origk . '_to']['compare'] = $to_sign;
                                                    $meta[$origk . '_to']['value'] = $to_val;
                                                }
                                            }
                                        }
                                        unset($args[$origk . '_to_sign'], $args[$origk . '_from_sign'], $args[$origk . '_from'], $args[$origk . '_to']);
                                        break;
                                    default:
                                        $meta[$origk] = array(
                                            'key' => 'ptb_' . $origk,
                                            'compare' => '=',
                                            'value' => $value
                                        );
                                        break;
                                }
                            }
                        }
                    } elseif (strpos($key, 'ptb_field_') === 0) {
                        $origk = sanitize_key(str_replace(array('ptb_field_', '_exist', '_from', '_to'), array('', '', '', ''), $key));

                        if (isset($post_support[$origk]) || isset($args['ptb_field_' . $origk . '_from']) || isset($args['ptb_field_' . $origk . '_to'])) {
                            $slike = !empty($args[$origk . '_slike']) ? '%' : '';
                            $elike = !empty($args[$origk . '_elike']) ? '%' : '';


                            switch ($origk) {
                                case 'thumbnail':
                                    $meta['field_' . $origk] = array(
                                        'key' => '_thumbnail_id',
                                        'compare' => '!=',
                                        'value'=>''
                                    );
                                    break;
                                case 'title':
                                case 'editor':
                                case 'excerpt':

                                    if (!empty($args['ptb_field_' . $origk . '_exist'])) {
                                        if ($origk === 'editor') {
                                            $origk = 'content';
                                        }
                                        $conditions[$origk] = " `post_$origk` !='' ";
                                    } else {
                                        if ($origk === 'editor') {
                                            $origk = 'content';
                                        }
                                        $conditions[$origk] = '`post_' . $origk . '` LIKE ' . "'" . $slike . esc_sql($value) . $elike . "'";
                                    }
                                    break;
                                case 'author':
                                    $args['author__in'] = explode(',', $value);
                                    break;
                                case 'comment_count':

                                    if (!empty($args['ptb_field_' . $origk . '_exist'])) {
                                        $conditions[$origk] = "`comment_count`>'0'";
                                    } elseif (!isset($conditions[$origk])) {
                                        $query_comment = array();
                                        $from_val = isset($args['ptb_field_' . $origk . '_from']) && is_numeric($args['ptb_field_' . $origk . '_from']) ? (int) $args['ptb_field_' . $origk . '_from'] : false;
                                        $to_val = isset($args['ptb_field_' . $origk . '_to']) && is_numeric($args['ptb_field_' . $origk . '_to']) ? (int) $args['ptb_field_' . $origk . '_to'] : false;
                                        $from_sign = $from_val && !empty($args[$origk . '_from_sign']) ? html_entity_decode($args[$origk . '_from_sign'], ENT_QUOTES, 'UTF-8') : false;
                                        $to_sign = $to_val && $from_sign !== '=' && !empty($args[$origk . '_to_sign']) ? html_entity_decode($args[$origk . '_to_sign'], ENT_QUOTES, 'UTF-8') : false;

                                        if ($from_sign) {
                                            if (in_array($from_sign, array('>', '>=', '='))) {
                                                $query_comment[] = '`comment_count`' . $from_sign . "'" . $from_val . "'";
                                            }
                                        }
                                        if ($to_sign) {
                                            if (in_array($from_sign, array('>', '>='))) {
                                                $query_comment[] = '`comment_count`' . $to_sign . "'" . $to_val . "'";
                                            }
                                        }
                                        if (!empty($query_comment)) {
                                            $conditions[$origk] = implode(' AND ', $query_comment);
                                        }
                                    }
                                    break;
                            }
                            unset($args[$origk . '_elike'], $args[$origk . '_slike']);
                        }
                    }
                }
            }
            if (!empty($conditions)) {
                if (!empty($args['post__in'])) {
                    $conditions[] = 'ID IN(' . implode(',', $args['post__in']) . ')';
                }
                $conditions = implode(' AND ', $conditions);
                global $wpdb;
                $result_query = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE `post_status`='publish' AND post_type='" . esc_sql($type) . "' AND $conditions");
                if (empty($result_query)) {
                    return '';
                }
                $args['post__in'] = array();
                foreach ($result_query as $p) {
                    $args['post__in'][] = $p->ID;
                }
            }
            if (!empty($taxes)) {
                $args['tax_query'] = $taxes;
                $args['tax_query']['relation'] = $args['logic'];
                unset($args['logic']);
            }
            if (!empty($meta)) {
                $args['meta_query'] = $meta;
                $args['meta_query']['relation'] = 'AND';
            }

            if (!isset(PTB_Form_PTT_Archive::$sortfields[$args['orderby']])) {
                $args['meta_key'] = 'ptb_' . $args['orderby'];
                $args['orderby'] = isset($cmb_options[$args['orderby']]['type']) && $cmb_options[$args['orderby']]['type'] === 'number' && empty($cmb_options[$args['orderby']]['range']) ? 'meta_value_num' : 'meta_value';
            }
        }
        self::$shortcode = true;
        if (empty($args['offset'])) {
            unset($args['offset']);
        }
        $style = $args['style'];
        unset($args['style']);

        global $post;
        if (is_object($post)) {
            $saved_post = clone $post;
        }

		// if 'return' parameter with value of 'query' is sent to the function,
		//  it will return the raw $args for WP_Query.
		if ( $return === 'query' ) {
			return $args;
		}
        // The Query
        $query = new WP_Query(apply_filters('themify_ptb_shortcode_query', $args));
        // The Loop
        $html = '';
        if ($query->have_posts()) {
            $themplate = new PTB_Form_PTT_Them($this->plugin_name, $this->version);
            $themplate_layout = isset($args['p']) ? $template->get_single() : $template->get_archive();
            $cmb_options = $post_support = $post_taxonomies = array();
            self::$options->get_post_type_data($type, $cmb_options, $post_support, $post_taxonomies);

            $terms = array();
            if(!empty($args['masonry']) && $style!=='list-post'){
                $style.= ' ptb_masonry';
            }
            $html = '<div class="ptb_loops_wrapper ptb_loops_shortcode clearfix ptb_' . $style . '">';
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $post_meta = array();
                $class = array('ptb_post', 'clearfix');
                $post_meta['post_url'] = get_permalink();
                $post_meta['taxonomies'] = !empty($post_taxonomies) ? wp_get_post_terms($post_id, array_values($post_taxonomies)) : array();
                if (isset($args['post_filter']) && !empty($post_meta['taxonomies'])) {
                    foreach ($post_meta['taxonomies'] as $p) {
                        $class[] = 'ptb-tax-' . $p->term_id;
                        $terms[] = $p->term_id;
                    }
                }
                $post_meta = array_merge($post_meta, get_post_custom(), get_post('', ARRAY_A));
                $html .= '<article id="post-' . $post_id . '" class="' . implode(' ', get_post_class($class)) . '">';
                $html .= $themplate->display_public_themplate($themplate_layout, $post_support, $cmb_options, $post_meta, $type, false);
                $html .= '</article>';
            }
            $html .= '</div>';
            if (isset($args['pagination']) && $query->max_num_pages > 1) {
					$paginate_args = array(
						'total' => $query->max_num_pages,
						'current' => $args['paged']
					);
					if ( isset($ptb_widget) ) {
						$paginate_args['base'] = @add_query_arg('ptb_paged','%#%');
						$paginate_args['format'] = '';
					}
                $html.='<div class="ptb_pagenav">';
                $html .= paginate_links($paginate_args);
                $html.='</div>';
            }
            if (isset($args['post_filter']) && !isset($args['post_id']) && !empty($terms)) {
                $terms = array_unique($terms);
                $query_terms = get_terms($post_taxonomies, array('hide_empty' => 1, 'hierarchical' => 1, 'pad_counts' => false));

                if (!empty($query_terms)) {
                    $cats = array();
                    foreach ($query_terms as $cat) {
                        if ($cat->parent == 0 || in_array($cat->term_id, $terms)) {
                            $cats[$cat->parent][$cat->term_id] = $cat->name;
                        }
                    }
                    unset($query_terms);
                    foreach ($cats[0] as $pid => &$parent) {
                        if (!isset($cats[$pid]) && !in_array($pid, $terms)) {
                            unset($cats[0][$pid]);
                        }
                    }

                    $filter = '';
                    foreach ($cats[0] as $tid => $cat) {

                        $filter.='<li data-tax="' . $tid . '"><a onclick="return false;" href="' . get_term_link(intval($tid)) . '">' . $cat . '</a>';
                        if (isset($cats[$tid])) {
                            $filter.='<ul class="ptb-post-filter-child">';
                            $filter.=$this->get_Child($cats[$tid], $cats);
                            $filter.='</ul>';
                        }
                        $filter.='</li>';
                    }
                    $html = '<ul class="ptb-post-filter">' . $filter . '</ul>' . $html;
                }
            }

            // Restore original Post Data
            if (isset($saved_post) && is_object($saved_post)) {
                $post = $saved_post;
                /**
                 * WooCommerce plugin resets the global $product on the_post hook,
                 * call setup_postdata on the original $post object to prevent fatal error from WC
                 */
                setup_postdata($saved_post);
            }
        }
        elseif($args['not_found']!==''){
            $html = $args['not_found'];
        }
        self::$shortcode = false;
        return $html;
    }

    public static function parse_multi_query(array $value, $type, $k, $post_id = array(), $like = false) {
        $like = $like ? "meta_value LIKE '%%s%'" : "LOCATE('%s',`meta_value`)>0";
        global $wpdb;
        if(!is_array($post_id)){
                $post_id = array();
        }
        foreach ($value as $v) {
            $v = sanitize_text_field(trim($v));
            $condition = str_replace('%s', $v, $like);
            if(!empty($post_id)){
                $condition.=' AND post_id IN(' . implode(',', $post_id) . ')';
            }
            $get_values = $wpdb->get_results("SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = 'ptb_$k' AND $condition");
            if (empty($get_values)) {
                return false;
            }

            $ids = array();
            foreach ($get_values as $val) {
                $ids[] = $val->post_id;
            }
            $ids = implode(',', $ids);
            $get_posts = $wpdb->get_results("SELECT `ID` FROM `{$wpdb->posts}` WHERE  ID IN({$ids}) AND `post_type` = '$type' AND `post_status`='publish'");
            if (empty($get_posts)) {
                return false;
            }

            foreach ($get_posts as $p) {
                $post_id[] = $p->ID;
            }
            $post_id = array_unique($post_id);
        }
        return $post_id;
    }

    public function single_lightbox() {
        if (!empty($_GET['id'])) {
            $id = (int)$_GET['id'];
            $post = get_post($id);
            if ($post && $post->post_status === 'publish') {
                $short_code = '[ptb post_id=' . $id . ' type=' . $post->post_type . ']';
                echo '<div class="ptb_single_lightbox">' . do_shortcode($short_code) . '</div>';
            }
            wp_die();
        }
    }

    private function get_Child($term, $cats) {
        $filter = '';
        foreach ($term as $tid => $cat) {
            $filter.='<li data-tax="' . $tid . '"><a onclick="return false;" href="' . get_term_link(intval($tid)) . '">' . $cat . '</a></li>';
            if (isset($cats[$tid])) {
                $filter.=$this->get_Child($cats[$tid], $cats);
            }
        }
        return $filter;
    }

    public function disable_ptb($args) {
        self::$is_disabled = true;
        return $args;
    }

    public function defer_js($tag, $handle, $src) {
        if (strpos($src, $this->url) !== false) {
            $tag = str_replace(' src', ' defer="defer" src', $tag);
        }
        return $tag;
    }

}
