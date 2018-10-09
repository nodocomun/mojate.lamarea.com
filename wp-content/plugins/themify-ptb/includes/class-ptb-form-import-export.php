<?php

class PTB_Form_ImportExport {

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    private $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    private $version;
    private $slug_admin_io;
    private $settings_section_import='settings_section_import';
    private $settings_section_export='settings_section_export';
    private $settings_section_prebuild='settings_section_pre';
    /**
     * The options management class of the the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      PTB_Options $options Manipulates with plugin options
     */
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $plugin_name
     * @param string $version
     * @param PTB_Options $options the plugin options instance
     *
     */
    public function __construct($plugin_name, $version, $options) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = $options;
        add_action('wp_ajax_ptb_import_pre_cpt',array($this,'import_predesign'));
        add_action('wp_ajax_ptb_upload_blob',array($this,'upload_images'));
    }

    public function add_settings_fields($slug_admin_io) {

        $this->slug_admin_io = $slug_admin_io;
        $sections  = array();
        $sections[$this->settings_section_prebuild] = array('name'=>__('Pre-built Samples', 'ptb'), 'action'=>array($this, 'pre_section_cb'));
        $sections[$this->settings_section_export] = array('name'=>__('Export', 'ptb'), 'action'=>array($this, 'export_section_cb'));
        $sections[$this->settings_section_import] = array('name'=>__('Import', 'ptb'), 'action'=>array($this, 'import_section_cb'));
        $sections = apply_filters('ptb_import_export_sections',$sections);
        foreach($sections as $k=>$v){
            add_settings_section(
                    $k,$v['name'], $v['action'], $this->slug_admin_io
            );
        }
    }
    
    public function pre_section_cb(){
        $cpt = $this->options->get_custom_post_types();
        $res = array();
        if(!empty($cpt)){
            foreach($cpt as $c){
                $res[] = $c->slug;
            }
        }
        ?>
            <form id="ptb_pre_wrapper" class="ptb_interface" data-cpt="<?php esc_attr_e(json_encode($res))?>">
                <ul></ul>
                <input type="hidden" value="<?php echo wp_create_nonce($this->plugin_name . '_ptb_pre'); ?>" name="nonce"/>
            </form>
        <?php
    }

    public function export_section_cb() {

        $lng = PTB_Utils::get_current_language_code();
        ?>

        <div class="ptb_interface ptb_export_wrapper">

            <div class="ptb_export_radio_wrapper">
                <label for="ptb_export_option_linked">
                    <input type="radio" name="ptb_export_mode" value="linked" id="ptb_export_option_linked"
                           checked="checked">
        <?php _e('Export Post Types and its associated Taxonomies & Templates', 'ptb'); ?>
                </label>
                <br/>
                <label for="ptb_export_option_separately">
                    <input type="radio" name="ptb_export_mode" value="separately" id="ptb_export_option_separately">
        <?php _e('Export Separately', 'ptb'); ?>
                </label>
            </div>

            <h2 class="nav-tab-wrapper">
                <a href="#ptb_export_cpt_list" class="nav-tab nav-tab-active"
                   data-target="cpt"><?php _e('Post Types'); ?></a>
                <a href="#ptb_export_ctx_list" class="nav-tab" style="display: none;"
                   data-target="ctx"><?php _e('Taxonomies'); ?></a>
                <a href="#ptb_export_ptt_list" class="nav-tab" style="display: none;"
                   data-target="ptt"><?php _e('Templates'); ?></a>
            </h2>

            <div class="ptb_tab_content_wrapper">
                <div id="ptb_export_cpt_list" class="ptb_tab_content">
                    <?php $cpt_collection = $this->options->get_custom_post_types();
                    foreach ($cpt_collection as $cpt) :?>
                            <label for="ptb_cpt_export_<?php echo $cpt->slug; ?>">
                                <input type="checkbox" name="ptb_cpt_export[]" id="ptb_cpt_export_<?php echo $cpt->slug; ?>" value="<?php echo $cpt->slug; ?>"> <?php echo PTB_Utils::get_label($cpt->plural_label); ?>
                            </label>
                    <?php endforeach; ?>
                </div>

                <div id="ptb_export_ctx_list" class="ptb_tab_content" style="display: none;">
        <?php
        $ctx_collection = $this->options->get_custom_taxonomies();
        foreach ($ctx_collection as $ctx) :
            ?>
                        <label for="ptb_ctx_export_<?php echo $ctx->slug; ?>">
                            <input type="checkbox" name="ptb_ctx_export[]" id="ptb_ctx_export_<?php echo $ctx->slug; ?>" value="<?php echo $ctx->slug; ?>"> <?php echo $ctx->plural_label[$lng]; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div id="ptb_export_ptt_list" class="ptb_tab_content" style="display: none;">
                    <?php
                    $ptt_collection = $this->options->get_post_type_templates();
                    foreach ($ptt_collection as $key => $ptt) :
                        ?>
                        <label for="ptb_ctx_export_<?php echo $key; ?>">
                            <input type="checkbox" name="ptb_ctx_export[]" id="ptb_ctx_export_<?php echo $key; ?>" value="<?php echo $key; ?>"> <?php echo $ptt->get_name(); ?>
                        </label>
                    <?php endforeach; ?>
                </div>

            </div>

            <form method="post" action="options.php" id="ptb_form_export">
                <?php settings_fields('ptb_plugin_options'); ?>
                <input type="hidden" name="ptb_plugin_options[ptb_ie_export]" value=""/>
                <a href="#export" class="ptb_ie_button" id="ptb_export"><?php _e('Export', 'ptb') ?></a>
            </form>

        </div>

        <?php
    }

    public function import_section_cb() {
        ?>

        <div class="ptb_interface ptb_import_wrapper">

            <form method="post" action="options.php" enctype="multipart/form-data" id="ptb_form_import">
                <?php settings_fields('ptb_plugin_options'); ?>
                <input type="hidden" name="ptb_plugin_options[ptb_ie_import]"/>
                <input type="file" name="ptb_import_file" class="ptb_import_file"/>
                <a href="#import" class="ptb_ie_button" id="ptb_import"><?php _e('Import', 'ptb') ?></a>
            </form>
            <br/>
            Import Post Type Builder data exported from the plugin or import the sample demo data from the plugin > sample folder (<a href="https://themify.me/docs/post-type-builder-plugin-documentation#import-samples" target="_blank">learn more</a>)
        </div>

        <?php
    }

    public function export($input) {

        $export = json_decode($input['ptb_ie_export'], true);

        $mode = $export['mode'];
        $target = $export['target'];
        $list = !empty($export['list']) ? $export['list'] : array();

        $result = $this->options->get_options_blueprint();

        if ($mode === 'separately') {

            switch ($target) {
                case 'cpt':
                    $collection = $this->options->get_custom_post_types_options();
                    break;
                case 'ctx':
                    $collection = $this->options->get_custom_taxonomies_options();
                    break;
                case 'ptt':
                    $collection = $this->options->get_templates_options();
                    break;
                default:
                    $collection = array();
            }

            foreach ($collection as $key => &$value) {
                if (!in_array($key, $list)) {
                    unset($collection[$key]);
                } elseif ($target === 'cpt') {
                    $value[PTB_Custom_Post_Type::TAXONOMIES] = array();
                } elseif ($target === 'ctx') {
                    $value[PTB_Custom_Taxonomy::ATTACH_TO] = array();
                }
            }

            $result[$target] = $collection;
        } elseif ($mode === 'linked') {

            $cpt_collection = $this->options->get_custom_post_types_options();
            $ctx_collection = $this->options->get_custom_taxonomies_options();
            $ptt_collection = $this->options->get_templates_options();

            $post_types = array();
            $taxonomies = array();
            $templates = array();

            foreach ($cpt_collection as $cpt_slug => &$value) {

                if (in_array($cpt_slug, $list,true)) {

                    $post_types[$cpt_slug] = $cpt_collection[$cpt_slug];

                    foreach ($value[PTB_Custom_Post_Type::TAXONOMIES] as $ctx_slug) {

                        if (isset($ctx_collection[$ctx_slug])) {

                            $taxonomies[$ctx_slug] = $ctx_collection[$ctx_slug];
                        }
                    }
                }
            }

            foreach ($taxonomies as $ctx_slug => &$ctx) {

                foreach ($ctx[PTB_Custom_Taxonomy::ATTACH_TO] as $cpt_slug) {

                    if (!in_array($cpt_slug, $list,true)) {

                        PTB_Utils::remove_from_array($cpt_slug, $ctx[PTB_Custom_Taxonomy::ATTACH_TO]);
                    }
                }
            }

            foreach ($ptt_collection as $ptt_id => $ptt) {

                if (in_array($ptt['post_type'], $list,true)) {

                    $templates[$ptt_id] = $ptt;
                }
            }

            $result['cpt'] = $post_types;
            $result['ctx'] = $taxonomies;
            $result['ptt'] = $templates;
        }

        ignore_user_abort(true);

        nocache_headers();
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=ptb-settings-export-' . date('m-d-Y') . '.json');
        header('Expires: 0');

        echo json_encode($result);
        exit;
    }

    public function import($input) {
        $tmp = explode('.', $_FILES['ptb_import_file']['name']);
        $extension = end($tmp);

        if ($extension !== 'json') {

            add_settings_error($this->plugin_name . '_notices', '', __('Please upload a valid .json file', "ptb"), 'error');

            return;
        }

        $import_file = $_FILES['ptb_import_file']['tmp_name'];

        if (empty($import_file)) {

            add_settings_error($this->plugin_name . '_notices', '', __('Please upload a file to import', "ptb"), 'error');

            return;
        }

        // Retrieve the settings from the file and convert the json object to an array.
        $data = json_decode(file_get_contents($import_file), true);

        if (isset($data['plugin']) && $this->plugin_name === $data['plugin']) {

            $options = $this->options;

            $cpt_collection = $options->get_custom_post_types_options();
            $ctx_collection = $options->get_custom_taxonomies_options();
            $ptt_collection = $options->get_templates_options();

            $post_types = isset($data['cpt']) ? $data['cpt'] : array();
            $taxonomies = isset($data['ctx']) ? $data['ctx'] : array();
            $templates = isset($data['ptt'])? $data['ptt'] : array();

            foreach ($post_types as $cpt_key => $cpt) {

                $cpt_collection[$cpt_key] = $cpt;
            }

            foreach ($taxonomies as $ctx_key => $ctx) {

                $ctx_collection[$ctx_key] = $ctx;
            }

            foreach ($templates as $ptt_key => $ptt) {

                $ptt_collection[$ptt_key] = $ptt;
            }

            $options->set_custom_post_types_options($cpt_collection);
            $options->set_custom_taxonomies_options($ctx_collection);
            $options->set_templates_options($ptt_collection);
            global $wpdb;
            $options->set_flush();
            $options->update();
            if (!empty($wpdb->last_error)) {
                add_settings_error($this->plugin_name . '_notices', '', __('Import failed', 'ptb'), 'error');
            } else {
                add_settings_error($this->plugin_name . '_notices', '', __('Imported data has been successfully processed', 'ptb'), 'updated');
                flush_rewrite_rules();
            }
        } else {

            add_settings_error($this->plugin_name . '_notices', '', __('Imported data has wrong format', 'ptb'), 'error');
        }
    }
    
    public function import_predesign(){
        
        if(!empty($_POST['slug']) && !empty($_POST['cpt'])){
            $response = array('status'=>false);
            $data = json_decode(stripslashes_deep($_POST['cpt']),true);   
            if(isset($data['ptt']) && !empty($data['cpt'])){
                set_time_limit(0);
                ini_set('memory_limit', '512M');
                $cpt_collection = $this->options->get_custom_post_types_options();
                $ptt_collection = $this->options->get_templates_options();
                $cpt_slug = sanitize_key($_POST['slug']);  
                $cpt_collection[$cpt_slug] = $data['cpt'];
                $cpt_collection[$cpt_slug]['id'] = $cpt_collection[$cpt_slug]['slug'] = $cpt_slug;
                
                unset($data['cpt']);
                foreach($ptt_collection as $k=>$p){
                    if($p['post_type']===$cpt_slug && (!empty($p['archive']) || !empty($p['single']))){
                        unset($ptt_collection[$k]);
                    }
                }
                $ptt_id = $this->options->get_next_id('ptt', $this->options->prefix_ptt_id);
                
                $ptt_collection[$ptt_id] = $data['ptt'];
                $ptt_collection[$ptt_id]['post_type'] = $cpt_slug;
                $this->options->set_templates_options($ptt_collection);
                unset($data['ptt'],$ptt_collection);
                if(!empty($data['ctx'])){
                    $ctx_collection = $this->options->get_custom_taxonomies_options();
                    foreach($data['ctx'] as $ctx){
                        $ctx_slug = sanitize_key($ctx['slug']);
                        $ctx['attach_to'] = isset($ctx_collection[$ctx_slug])?$ctx_collection[$ctx_slug]['attach_to']:array($cpt_slug);
                        $ctx_collection[$ctx_slug] = $ctx;
                        if(array_search($cpt_slug, $ctx_collection[$ctx_slug]['attach_to'],true)===false){
                            $ctx_collection[$ctx_slug]['attach_to'][] = $cpt_slug;
                        }
                    }
                    $this->options->set_custom_taxonomies_options($ctx_collection);
                    $cpt_collection[$cpt_slug]['taxonomies'] = array_keys($ctx_collection);
                    unset($data['ctx'],$ctx_collection);
                }
                $this->options->set_custom_post_types_options($cpt_collection);
                unset($cpt_collection);
                
                if(!empty($_POST['samples'])){
                    if(!post_type_exists($cpt_slug)){
                        register_post_type($cpt_slug);
                    }
                    $user = get_current_user_id();
                    $samples = json_decode(stripslashes_deep($_POST['samples']),true);
                    $post_ids = $tax = $meta = array();
                    foreach($samples as $s){
                        $id = wp_insert_post( array(
                            'post_title'=>$s['title'],
                            'post_content'=>$s['content'],
                            'post_status'=>'publish',
                            'post_author'=>  $user,
                            'post_type'=>$cpt_slug,
                            'post_excerpt'=>$s['excerpt'],
                            'meta_input'=>!empty($s['meta'])?$s['meta']:false
                        ),true );
                                      
                        if(is_wp_error($id)){
                            if(!empty($s['img'])){
                                wp_delete_attachment($s['img']);
                            }
                            continue;
                        }
                        if(!empty($s['img'])){
                            set_post_thumbnail( $id, $s['img'] );
                        }
                        if(!empty($s['tax'])){
                            foreach($s['tax'] as $k=>$terms){
                                if(!taxonomy_exists($k) ){
                                    register_taxonomy($k, $cpt_slug);
                                }
                                $terms_arr = array();
                                foreach($terms as $slug=>$t){
                                    $term =get_term_by('slug',$slug,$k);
                                    if(!$term){
                                        if(!is_wp_error($term)){
                                            $term = wp_insert_term($t, $k,array('slug'=>$slug));
                                            if(is_wp_error($term)){
                                                var_dump($k,$slug,$term);
                                                continue;
                                            }
                                        }
                                        else{
                                            continue;
                                        }
                                    }
                                    else{
                                        $term = (array)$term;
                                    }
                                    $terms_arr[] =$term['term_id'];
                                }
                                if(!empty($terms_arr)){
                                    wp_set_post_terms($id,$terms_arr,$k,false);
                                }
                            }
                        }
                    }
                }
                global $wpdb;
                $this->options->set_flush(); 
                if (!$this->options->update() || !empty($wpdb->last_error)) {
                    $response['msg'] = __('Import failed', 'ptb');
                    
                } else {
                    $response['msg'] = __('Imported data has been successfully processed', 'ptb');
                    $response = array('status'=>'success');
                    flush_rewrite_rules();
                  
                }
               
            }
            else{
                $response['msg'] = __('The custom post can`t be imported','ptb');
            }
            echo wp_json_encode($response);
        }
        wp_die();
    }
    
    public function upload_images(){
        if(!empty($_FILES['file'])){
            $f = $_FILES['file'];
            $types = wp_get_mime_types();
            $ext = array_search($f['type'],$types,true);
            unset($types);
            $result = array('success'=>false);
            if($ext!==false){
                $ext = explode('|',$ext);
                $ext = $ext[0];
                if($ext==='jpg' || $ext==='png'){
                    $f['name'].='.'.$ext;
                    $movefile = wp_handle_upload( $f,array('test_form' => FALSE) );
                    if ( $movefile && empty($movefile['error']) ) {
                        $attach_id = wp_insert_attachment(  array(
                                'guid'           => $movefile['url'], 
                                'post_mime_type' => $movefile['type'],
                                'post_title'     => !empty($_POST['title'])?$_POST['title']:'',
                                'post_content'   => !empty($_POST['caption'])?$_POST['caption']:'',
                                'post_status'    => 'inherit'
                        ), $movefile['file'] );
                        if($attach_id && !is_wp_error($attach_id)){
                            require_once( ABSPATH . 'wp-admin/includes/image.php' );
                            $attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
                            wp_update_attachment_metadata( $attach_id, $attach_data );
                            $result['success'] = true;
                            $result['id'] = $attach_id;
                        }
                        else{
                            wp_delete_file($movefile['file']);
                        }

                    } 
                }
            }
            wp_delete_file($f['tmp_name']);
            echo wp_json_encode($result);
        }
        wp_die();
    }
    
}
