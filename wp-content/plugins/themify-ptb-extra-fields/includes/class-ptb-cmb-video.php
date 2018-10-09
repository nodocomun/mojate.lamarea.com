<?php

/**
 * Custom meta box class to create gallery
 *
 * @link       https://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_CMB_Video extends PTB_Extra_Base {

    private $data = array();

    public function __construct($type, $plugin_name, $version) {
        add_action('wp_ajax_ptb_extra_video', array($this, 'lightbox_video'));
        add_action('wp_ajax_nopriv_ptb_extra_video', array($this, 'lightbox_video'));
        parent::__construct($type, $plugin_name, $version);
    }

    /**
     * Adds the custom meta type to the plugin meta types array
     *
     * @since 1.0.0
     *
     * @param array $cmb_types Array of custom meta types of plugin
     *
     * @return array
     */
    public function filter_register_custom_meta_box_type($cmb_types) {

        $cmb_types[$this->get_type()] = array(
            'name' => __('Video', 'ptb_extra')
        );

        return $cmb_types;
    }

    /**
     * Renders the meta boxes for themplates
     *
     * @since 1.0.0
     *
     * @param string $id the metabox id
     * @param string $type the type of the page(Arhive or Single)
     * @param array $args Array of custom meta types of plugin
     * @param array $data saved data
     * @param array $languages languages array
     */
    public function action_them_themplate($id, $type, $args, $data = array(), array $languages = array()) {
        $default = empty($data);
        ?>

        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[columns]"><?php _e('Columns', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input ptb_back_text">
                <div class="ptb_custom_select">
                    <select id="ptb_<?php echo $id ?>[columns]" name="[<?php echo $id ?>][columns]">
                        <?php for ($i = 1; $i <= 4; ++$i): ?>
                            <option <?php if (isset($data['columns']) && $data['columns'] == $i): ?>selected="selected"<?php endif; ?> value="<?php echo $i ?>">
                                <?php echo $i ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[preview]"><?php _e('Show Image Preview', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input ptb_back_text">
                <input class="ptb_extra_video_preview" type="checkbox" id="ptb_<?php echo $id ?>[preview]"
                       name="[<?php echo $id ?>][preview]" value="1"
                       <?php if ($default || !empty($data['preview'])): ?>checked="checked"<?php endif; ?>
                       />
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[lightbox]"><?php _e('Open in Lightbox', 'ptb_extra') ?></label>
            </div>
            <div  class="ptb_back_active_module_input ptb_back_text">
                <input id="ptb_<?php echo $id ?>[lightbox]" type="checkbox"
                       class="ptb_extra_lightbox" name="[<?php echo $id ?>][lightbox]"
                       <?php if (!empty($data['lightbox'])): ?>checked="checked"<?php endif; ?>  />
                       <?php if ($type == PTB_Post_Type_Template::ARCHIVE): ?>
                    <div class="ptb_extra_inline">
                        <label><?php _e('Link to post permalink', 'ptb') ?>&nbsp;&nbsp;</label>
                        <input type="radio" id="ptb_<?php echo $id ?>_link_yes"
                               name="[<?php echo $id ?>][link]" value="1"
                               <?php if (!isset($data['link']) || !empty($data['link'])): ?>checked="checked"<?php endif; ?>/>
                        <label for="ptb_<?php echo $id ?>_link_yes"><?php _e('Yes', 'ptb') ?></label>
                        <input type="radio" id="ptb_<?php echo $id ?>_link_no"
                               name="[<?php echo $id ?>][link]" value="0"
                               <?php if (isset($data['link']) && $data['link'] == 0): ?>checked="checked"<?php endif; ?> />
                        <label for="ptb_<?php echo $id ?>_link_no"><?php _e('No', 'ptb') ?></label>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <?php
    }

    /**
     * Renders the meta boxes on post edit dashboard
     *
     * @since 1.0.0
     *
     * @param WP_Post $post
     * @param string $meta_key
     * @param array $args
     */
    public function render_post_type_meta($post, $meta_key, $args) {
        $wp_meta_key = sprintf('%s_%s', $this->get_plugin_name(), $meta_key);
        $value = get_post_meta($post->ID, $wp_meta_key, true);
        $url_name = sprintf('%s[url][]', $meta_key);
        $title_name = sprintf('%s[title][]', $meta_key);
        $description_name = sprintf('%s[description][]', $meta_key);
        ?>
        <fieldset class="ptb_cmb_input">
            <ul id="<?php echo $meta_key; ?>_options_wrapper" class="ptb_cmb_options_wrapper">
                <?php $values = is_array($value) && isset($value['url']) ? $value['url'] : array($value); 
                    if(!isset($values[0])){
                        $values = array(0=>'');
                    }
                ?>
                <?php foreach ($values as $index => $v): ?>
                    <?php
                    $title =  isset($value['title'][$index]) ? esc_attr($value['title'][$index]) : '';
                    $description = isset($value['description'][$index]) ? esc_textarea($value['description'][$index]) : '';
                    ?>

                    <li class="<?php echo $meta_key; ?>_option_wrapper ptb_cmb_option">
                        <span class="ti-split-v ptb_cmb_option_sort"></span>
                        <div class="ptb_post_cmb_image_wrapper">
                            <a href="#" class="ptb_post_cmb_image <?php if ($v): ?>ptb_uploaded<?php endif; ?>">
                                <span class="ti-plus"></span>
                            </a>
                        </div>
                        <input type="text" name="<?php echo $url_name; ?>" value="<?php echo esc_url_raw($v); ?>" placeholder="<?php _e('Video Url(youtube/vimeo)', 'ptb_extra') ?>"/>
                        <input type="text" name="<?php echo $title_name; ?>" value="<?php esc_attr_e($title) ?>" placeholder="<?php _e('Title', 'ptb_extra') ?>" class="ptb_extra_row_margin"/>
                        <textarea name="<?php echo $description_name ?>" placeholder="<?php _e('Description', 'ptb_extra') ?>"><?php echo $description; ?></textarea>
                        <span class="<?php echo $meta_key; ?>_remove remove ti-close"></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div id="<?php echo $meta_key; ?>_add_new" class="ptb_cmb_option_add">
                <span class="ti-plus"></span>
                <?php _e('Add new', 'ptb_extra') ?>
            </div>
        </fieldset>
        <?php
    }

    public function lightbox_video() {
        if (isset($_GET['post_id']) && isset($_GET['v']) && isset($_GET['k'])) {
            $post_id = (int)$_GET['post_id'];
            $v = (int)$_GET['v'];
            if ($v >= 0 && $post_id) {
                global $post;
                $post = get_post($post_id);
                if (!$post || $post->post_status !== 'publish') {
                    wp_die();
                }
                $k = sanitize_key($_GET['k']);
                $wp_meta_key = sprintf('%s_%s', $this->get_plugin_name(), $k);
                $video = get_post_meta($post_id, $wp_meta_key, true);
                if (!$video || !isset($video['url'][$v])) {
                    wp_die();
                }
                
                ?>
                <?php if(empty($_GET['embed'])):?>
                    <video width="100%" height="90%" controls="1" autoplay="1">
                        <source src="<?php echo $video['url'][$v] ?>">
                    </video>
                <?php else:?>
                    <?php 
                        global $wp_embed;
                        $html = $wp_embed->run_shortcode( '[embed]'.$video['url'][$v]. '[/embed]' );
                        if($html){
                            echo preg_replace('/src=\"(.+?)\"/', 'src="${1}?autoplay=1"', $html);
                        }
                    ?>
                <?php endif;?>
                <?php
            } 
            wp_die();
        }
    }


    /*
     * parse_video_url() PHP function
     * Author: takien, slaffko
     * URL: http://takien.com, http://slaffko.name
     *
     * @param string $url URL to be parsed, eg:
     * http://vimeo.com/1515151
     * http://youtu.be/zc0s358b3Ys,
     * http://www.youtube.com/embed/zc0s358b3Ys
     * http://www.youtube.com/watch?v=zc0s358b3Ys
     * @param string $return what to return
     * - embed, return embed code
     * - thumb, return URL to thumbnail image
     * - thumbmed, return URL to thumbnail mediul image(for vimeo only)
     * - hqthumb, return URL to high quality thumbnail image.
     * @param string $width width of embeded video, default 560
     * @param string $height height of embeded video, default 349
     * @param string $rel whether embeded video to show related video after play or not.
     */

    public static function parse_video_url($url, $return = 'embed', $width = '100%', $height = '100%', $rel = 0, $check = false) {
        $urls = parse_url($url);
        if (!isset($urls['path'])) {
            return false;
        }
        $vid = $yid = false;
        //url is http://vimeo.com/xxxx
        if ($urls['host'] === 'vimeo.com') {
            $v = ltrim($urls['path'], '/');
            $v = explode('/', $v);
            $vid = end($v);
        }
        //url is http://youtu.be/xxxx
        else if ($urls['host'] === 'youtu.be') {
            $yid = ltrim($urls['path'], '/');
        }
        //url is http://www.youtube.com/embed/xxxx
        else if (strpos($urls['path'], 'embed') === 1) {
            $yid = end(explode('/', $urls['path']));
        }
        //url is xxxx only
        else if (strpos($url, '/') === false) {
            $yid = $url;
        }
       
        //http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI
        //url is http://www.youtube.com/watch?v=xxxx
        else {
            parse_str($urls['query']);
            $yid = $v;
            if (!empty($feature)) {
                $yid = end(explode('v=', $urls['query']));
                $arr = explode('&', $yid);
                $yid = $arr[0];
            }
        } 
        if ($check) {
            return $vid || $yid;
        }
        if ($yid) {

            //return embed iframe
            if ($return === 'embed') {
                return array('url' => '//www.youtube.com/embed/' . $yid . '?rel=' . $rel, 'data' => '<iframe width="' . $width . '" height="' . $height . '" src="//www.youtube.com/embed/' . $yid . '?rel=' . $rel . '" frameborder="0" ebkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
            }
            //return normal thumb
            else if ($return === 'thumb' || $return === 'thumbmed') {
                return array('url' => '//www.youtube.com/embed/' . $yid . '?rel=' . $rel, 'data' => '//i1.ytimg.com/vi/' . $yid . '/default.jpg');
            }
            //return hqthumb
            else if ($return === 'hqthumb') {
                return array('url' => '//www.youtube.com/embed/' . $yid . '?rel=' . $rel, 'data' => '//i1.ytimg.com/vi/' . $yid . '/hqdefault.jpg');
            } else {
                return false;
            }
        } else if ($vid) {

            $video = wp_safe_remote_get("https://vimeo.com/api/v2/video/" . $vid . ".json");

            if (!$video || !isset($video['body']) || $video['response']['code'] === '404') {
                return FALSE;
            }
            $vimeoObject = json_decode($video['body']);
            if (!empty($vimeoObject)) {
                //return embed iframe
                if ($return === 'embed') {
                    return array('url' => '//player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0', 'data' => '<iframe width="' . ($width ? $width : $vimeoObject[0]->width) . '" height="' . ($height ? $height : $vimeoObject[0]->height) . '" src="//player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
                }
                //return normal thumb
                else if ($return === 'thumb') {
                    return array('url' => '//player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0', 'data' => $vimeoObject[0]->thumbnail_small);
                }
                //return medium thumb
                else if ($return === 'thumbmed') {
                    return array('url' => '//player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0', 'data' => $vimeoObject[0]->thumbnail_medium);
                }
                //return hqthumb
                else if ($return === 'hqthumb') {
                    return array('url' => '//player.vimeo.com/video/' . $vid . '?title=0&byline=0&portrait=0', 'data' => $vimeoObject[0]->thumbnail_large);
                }
            }
        }
        return FALSE;
    }

    public function ptb_submission_themplate($id, array $args, array $module = array(), array $post_support, array $languages = array()) {
        $max_upload = wp_max_upload_size();
        if (!isset($module['size'])) {
            $module['size'] = $max_upload;
        }
        if (empty($module['extensions'])) {
            $module['extensions'] = array('all');
        }
        $size = PTB_Submissiion_Options::max_upload_size($module['size']);
        $can_be_allowed = array_keys(PTB_Submissiion_Options::get_allow_ext(array(), 'video'));
        $all = in_array('all', $module['extensions'],true);
        ?>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>_extensions"><?php _e('Allowed extensions', 'ptb-submission') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <select size="10" class="ptb-select" multiple="multiple" name="[<?php echo $id ?>][extensions][arr]" id="ptb_<?php echo $id ?>_extensions">
                    <option <?php if ($all): ?>selected="selected"<?php endif; ?> value="all"><?php _e('ALL', 'ptb-submission') ?></option>
                    <?php foreach ($can_be_allowed as $ext): ?>
                        <option <?php echo $all ? 'disabled="disabled"' : (in_array($ext, $module['extensions'],true) ? 'selected="selected"' : '') ?>  value="<?php echo $ext ?>"><?php echo $ext ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>_size"><?php _e('Maximum image size(b)', 'ptb-submission') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <input type="number" name="[<?php echo $id ?>][size]" value="<?php echo $size ?>" min="1" max="<?php echo $max_upload ?>" id="ptb_<?php echo $id ?>_size" />
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>_max"><?php _e('Maximum files upload', 'ptb-submission') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <div class="ptb-submission-small-input">
                    <input type="number" name="[<?php echo $id ?>][max]" value="<?php echo !empty($module['max'])?(int)$module['max']:0 ?>" min="0"  id="ptb_<?php echo $id ?>_max" />
                    <small class="ptb-submission-small-description"><?php _e('Set "0" to unlimit file upload','ptb-submission')?></small>
                </div>
            </div>
        </div>
        <?php
    }

    public function ptb_submission_form($post_type, array $args, array $module, $post, $lang, $languages) {
        if(!wp_script_is(self::$plugin_name . '-submission-video')){
            $pluginurl = plugin_dir_url(dirname(__FILE__));
            $translation_ = array(
                'video_error' => __('Incorrect (Youtube/Vimeo) url', 'ptb-submission')
            );
            wp_enqueue_script(self::$plugin_name . '-fileupload-video', $pluginurl . 'public/submission/js/jquery.fileupload-video.min.js', array('ptb-submission-fileupload-image'), self::$version, true);
            wp_register_script(self::$plugin_name . '-submission-video', PTB_Utils::enque_min($pluginurl . 'public/submission/js/video.js'), array(), self::$version, true);
            wp_localize_script(self::$plugin_name . '-submission-video', 'ptb_submission_video', $translation_);
            wp_enqueue_script(self::$plugin_name . '-submission-video');
        }
        $data = isset($post->ID) ? get_post_meta($post->ID, 'ptb_' . $args['key'], TRUE) : array();
        if ( empty($data['url'])) {
            $data = array('url' => array(false));
            $title = array();
        } else {
            if (!$data['title']) {
                $data['title'] = array();
            }
            if (!$data['description']) {
                $data['description'] = array();
            }
            $title = $this->ptb_submission_lng_data($data['title'], $args['key'], 'title', $post->ID, $post_type, $languages);
            $desc = $this->ptb_submission_lng_data($data['description'], $args['key'], 'description', $post->ID, $post_type, $languages);
        }
        if (!isset($module['size'])) {
            $module['size'] = false;
        }
        if ( empty($module['extensions'])) {
            $module['extensions'] = array('all');
        }
        $module['extensions'] = array_keys(PTB_Submissiion_Options::get_allow_ext($module['extensions'], 'video'));
        $size = PTB_Submissiion_Options::max_upload_size($module['size']);
        $max = !empty($module['max'])?(int)$module['max']:false;
        if($max===0){
            $max = false;
        }
        $i = 0;
        ?>
        <div class="ptb_back_active_module_input ptb-submission-multi-text ptb_extra_submission_images ptb_extra_submission_video"<?php if($max):?> data-max="<?php echo $max?>"<?php endif;?>>
            <ul>
                <?php foreach ($data['url'] as $k => $v): ?>
                    <?php if($max && $i>$max):?>
                        <?php break;?>
                    <?php endif;?>
                    <?php $video = $v && strpos($v, 'youtube.com') === false && strpos($v, 'vimeo.com') === false ?>
                    <li class="ptb-submission-text-option">
                        <i title="<?php _e('Sort', 'ptb_extra') ?>" class="fa fa-sort ptb-submission-option-sort"></i>
                        <div class="ptb-submission-priview-wrap" style="width: 1px;">
                            <div class="ptb-submission-priview">
                                <?php if ($video): ?>
                                    <video src="<?php echo $v ?>" width="80" height="80"></video>
                                    <input type="hidden" value="<?php echo esc_url_raw($v) ?>" name="submission[<?php echo $args['key'] ?>][f]" />
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ptb_back_active_module_input">
                            <div class="ptb_extra_image_title"><label data-label="<?php _e('Upload Video', 'ptb_extra') ?>" class="fa fa-upload ptb-submission-upload-btn" for="ptb_submission_<?php echo $args['key'] ?>"><?php _e('Upload Video', 'ptb_extra') ?></label></div>
                            <input class="ptb_extra_video_url" value="<?php echo!$video && $v ? $v : '' ?>" type="text" placeholder="<?php _e('Or video Url(Youtube/Vimeo)', 'ptb_extra') ?>" name="submission[<?php echo $args['key'] ?>][url]" />
                            <div class="ptb-submission-file-wrap">
                                <input data-extension="<?php echo esc_attr(str_replace(',', '|', implode('|', $module['extensions']))) ?>" data-size="<?php echo $size ?>" data-width="80" data-height="80" id="ptb_submission_<?php echo $args['key'] ?>" class="ptb-submission-file" type="file" name="<?php echo $args['key'] ?>" />
                            </div>
                        </div>
                        <?php PTB_CMB_Base::module_language_tabs('submission', isset($title[$k]) ? $title[$k] : array(), $languages, $args['key'] . '_title', 'text', __('Video Title', 'ptb_extra'), true); ?>
                        <?php PTB_CMB_Base::module_language_tabs('submission', isset($desc[$k]) ? $desc[$k] : array(), $languages, $args['key'] . '_description', 'textarea', __('Video Description', 'ptb_extra'), true); ?>
                        <i title="<?php _e('Remove', 'ptb_extra') ?>" class="ptb-submission-remove fa fa-times-circle"></i>
                    </li>
                    <?php ++$i;?>
                <?php endforeach; ?>
            </ul>
            <div class="ptb-submission-option-add">
                <i class="fa fa-plus-circle"></i>
                <?php _e('Add new', 'ptb_extra') ?>                           
            </div>
            <?php if (isset($module['show_description'])): ?>
                <div class="ptb-submission-description ptb-submission-<?php echo $args['key'] ?>-description"><?php echo PTB_Utils::get_label($args['description']); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function ptb_submission_validate(array $post_data, array $args, array $module, $post_type, $post_id, $lang, array $languages) {
        $error = false;
        $key = $module['key'];
        $file = isset($_FILES[$key]['tmp_name']) ? $_FILES[$key] : array();
        $data = $post_id && isset($post_data[$key]['f']) ? $post_data[$key]['f'] : array();
        $url = isset($post_data[$key]['url']) ? $post_data[$key]['url'] : array();
        if (!isset($module['size'])) {
            $module['size'] = false;
        }
        if ( empty($module['extensions'])) {
            $module['extensions'] = array('all');
        }
        $allow = PTB_Submissiion_Options::get_allow_ext($module['extensions'], 'video');
        $fsize = PTB_Submissiion_Options::max_upload_size($module['size']);
        if ($data) {
            $extensions = str_replace(',', '|', implode('|', array_keys($allow)));
        }
        $this->data[$key]['url'] = array();
        if (isset($post_data[$key . '_title'][$lang])) {
            $max = !empty($module['max'])?(int)$module['max']:false;
            if($max===0){
                $max = false;
            }
            if($max && count($post_data[$key . '_title'][$lang])>$max){
                $post_data[$key . '_title'][$lang] = array_slice($post_data[$key . '_title'][$lang], $max);
            }
            foreach ($post_data[$key . '_title'][$lang] as $k => $v) {
                $error = false;
                if (!empty($url[$k])) {
                    $f = esc_url_raw($url[$k]);
                    if ($f && self::parse_video_url($f, false, false, false, false, true)) {
                        $this->data[$key]['url'][$k] = $f;
                    }
                } elseif (!empty($data[$k])) {
                    $f = esc_url_raw($data[$k]);
                    $path = ABSPATH . trim(parse_url($f, PHP_URL_PATH), '/');
                    if (file_exists($path)) {
                        $ext = pathinfo($f, PATHINFO_EXTENSION);
                        if (preg_match('/(' . $extensions . ')/i', $ext, $m)) {
                            $size = filesize($path);
                            if ($size <= $fsize) {
                                $this->data[$key]['url'][$k] = $f;
                            }
                        }
                    }
                } elseif (isset($file['tmp_name'][$k])) {
                    $f = array('name' => $file['name'][$k], 'size' => $file['size'][$k], 'tmp_name' => $file['tmp_name'][$k]);
                    $check = PTB_Submissiion_Options::validate_file($f, $allow, $fsize);
                    if (!isset($check['error'])) {
                        $this->data[$key]['url'][$k] = $check['file']['url'];
                        PTB_Submission_Public::$files[$key][] = $check['file'];
                    } else {
                        $error = $check['error'];
                    }
                }
                if (isset($this->data[$key]['url'][$k])) {
                    foreach ($languages as $code => $lng) {
                        $this->data[$key]['description'][$code][$k] = isset($post_data[$key . '_description'][$code][$k]) ? esc_textarea($post_data[$key . '_description'][$code][$k]) : false;
                        $this->data[$key]['title'][$code][$k] = isset($post_data[$key . '_title'][$code][$k]) ? sanitize_text_field($post_data[$key . '_title'][$code][$k]) : false;
                    }
                }
            }
        }

        if (isset($module['required']) && empty($this->data[$key]['url'])) {
            return $error ? $error : PTB_Utils::get_label($args['name']) . __(' is required', 'ptb_extra');
        }
        if(empty($this->data[$key]['url'])){
            $post_data[$key]['url'] = array();
        }
        return $post_data;
    }

    public function ptb_submission_save(array $m, $key, array $post_data, $post_id, $lng) {
        return array('url' => !empty($this->data[$key]['url']) ? $this->data[$key]['url'] : array(),
            'title' => isset($this->data[$key]['title'][$lng]) ? $this->data[$key]['title'][$lng] : false,
            'description' => isset($this->data[$key]['description'][$lng]) ? $this->data[$key]['description'][$lng] : false
        );
    }

}
