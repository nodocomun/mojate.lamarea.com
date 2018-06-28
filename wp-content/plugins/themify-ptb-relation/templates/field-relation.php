<?php
if (!empty($meta_data[$args['key']])) {
    $value = array_filter(explode(', ', $meta_data[$args['key']]));
    if (!$value) {
        return;
    }
    $is_shortcode = PTB_Public::$shortcode;
    PTB_Public::$shortcode = true;
    $post_type = get_post_type();
    $rel_post_type = $args['post_type'];
    $rel_options = PTB_Relation::get_option();
    $template = $rel_options->get_relation_template($rel_post_type, $post_type);
    if (!$template) {
        return;
    }
    $ptb_options = PTB::get_option();
    $themplate_layout = $ptb_options->get_post_type_template($template['id']);
    if (!isset($themplate_layout['relation']['layout'])) {
        return;
    }
    $many = !empty($args['many']);
    if (!$many) {
        $value = array(current($value));
    }
    $value = array_unique($value);
    $ver = $this->get_plugin_version();
    $content = '';
    $themplate = new PTB_Form_PTT_Them('ptb', $ver);
    $args = array(
        'post_type' => $rel_post_type,
        'post_status' => 'publish',
        'post__in' => $value,
        'order' => !empty($data['order']) ? $data['order'] : 'ASC',
        'orderby' => !empty($data['orderby']) ? $data['orderby'] : 'post__in',
        'posts_per_page' => count($value),
        'no_found_rows' => 1
    );
    if (!empty($data['orderby']) && !isset(PTB_Form_PTT_Archive::$sortfields[$data['orderby']])) {
        $cmb_options = $ptb_options->get_cpt_cmb_options($rel_post_type);
        if (isset($cmb_options[$data['orderby']])) {
            $args['meta_key'] = 'ptb_' . $data['orderby'];
            $args['orderby'] = $cmb_options[$args['orderby']]['type'] === 'number' && empty($cmb_options[$data['orderby']]['range']) ? 'meta_value_num' : 'meta_value';
        }
    }
    global $post;
    $old_post = clone $post;
    $args = apply_filters('ptb_relation_query_args', $args, $data, $post_type, $rel_post_type);
    $query = new WP_Query;
    $rel_posts = $query->query($args);
    foreach ($rel_posts as $p) {
        $post = $p;
        setup_postdata($post);

        $cmb_options = $post_support = $post_meta = $post_taxonomies = array();
        $ptb_options->get_post_type_data($rel_post_type, $cmb_options, $post_support, $post_taxonomies);
        $post_meta['post_url'] = get_permalink();
        $post_meta['taxonomies'] = !empty($post_taxonomies) ? wp_get_post_terms(get_the_ID(), array_values($post_taxonomies)) : array();
        $post_meta = array_merge($post_meta, get_post_custom(), get_post('', ARRAY_A));
        $content.='<li class="ptb_relation_item">' . $themplate->display_public_themplate($themplate_layout['relation'], $post_support, $cmb_options, $post_meta, $rel_post_type, false) . '</li>';
    }
    PTB_Public::$shortcode = $is_shortcode;
    $post = $old_post;
    setup_postdata($post);
    if (!$content) {
        return;
    }
    $js_data = array();
    if ($many && isset($data['mode']) && $data['mode'] === 'slider') {
        if (!wp_script_is('ptb-relation')) {
            wp_enqueue_style('ptb-bxslider');
            wp_enqueue_script('ptb-relation');
        }
        foreach ($data as $key => $arg) {
            if (!in_array($key, array('text_after', 'text_before', 'text_after', 'css', 'type', 'key', 'mode', 'columns'), true)) {
                if (!is_array($arg)) {
                    $js_data[$key] = $arg;
                } elseif (isset($arg[$lang])) {
                    $js_data[$key] = $arg[$lang];
                }
            }
        }
    }
    ?>
    <div class="ptb_loops_shortcode clearfix ptb_relation_<?php echo $many && isset($data['mode']) ? $data['mode'] : ''; ?>">
        <ul <?php if (!empty($js_data)): ?>data-slider="<?php echo esc_attr(json_encode($js_data)); ?>" class="ptb_relation_post_slider"<?php else: ?>class="ptb_relation_posts ptb_relation_columns_<?php echo isset($data['columns']) ? $data['columns'] : '' ?>"<?php endif; ?>>
            <?php echo $content ?>    
        </ul>
    </div>
    <?php
}