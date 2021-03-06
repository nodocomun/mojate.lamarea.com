<?php

/**
 * Custom meta box class to create event-date
 *
 * @link       https://themify.me
 * @since      1.0.0
 *
 * @package    PTB
 * @subpackage PTB/includes
 * @author     Themify <ptb@themify.me>
 */
class PTB_CMB_Event_Date extends PTB_Extra_Base {
    
    private $search_date = array();
    
    public function __construct($type, $plugin_name, $version) {
        parent::__construct($type, $plugin_name, $version);
        add_filter('ptb_ajax_shortcode_result',array($this,'add_date_field'),10,2);
        add_filter('themify_ptb_shortcode_query',array($this,'date_filter'),10,1);
		add_filter('ptb_filter_cmb_body',array($this,'before_save'),10,2);
        if(!is_admin() || (defined('DOING_AJAX') &&  DOING_AJAX)){
            add_action('ptb_search_event_date',array($this,'search_date_template'),10,8);
            add_filter('ptb_search_by_event_date',array($this,'search_date'),10,6);
            add_filter('ptb_search_filter_by_slug',array($this,'filter_value'),10,6);
			
        }
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
            'name' => __('Date', 'ptb_extra')
        );
        return $cmb_types;
    }

	/**
     * Converts the date and time to and From 'Y-m-d H:i:s'
     *
     * @since 1.3.5 
     *
     * @param string $datetime
     * @param string $to_mysql only values 'yes' and 'no'
	 *
     * @return string
     */
	private function convert_datetime($datetime , $to_mysql = 'no'){
		if ( empty($datetime) ) {
			return '';
		}
		switch ($to_mysql) {
			case 'no':
				if (strpos($datetime, '@') === FALSE) {
					$tmp_val = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);
					$datetime = $tmp_val ? $tmp_val->format('Y-m-d@h:i a') : $datetime;
				}
				break;
			default :
				if (strpos($datetime, '@') !== FALSE) {
					$tmp_val = DateTime::createFromFormat('Y-m-d@h:i a', $datetime);
                    $datetime = $tmp_val ? $tmp_val->format('Y-m-d H:i:s') : $datetime;
				}
		}
		return $datetime;
	}
	
	public function before_save($args, $post){
		$path = plugin_dir_path(dirname(__FILE__));
		
		if( empty( $_POST ) ) {
			return $args;
		}
		foreach ($args as $key => $data) {
			if ($data['type'] === 'event_date' && !$data['deleted']) {
				if ( ! empty($_POST[$key]) ) {
					if (is_array($_POST[$key])) {
						$_POST[$key]['start_date'] = $this->convert_datetime($_POST[$key]['start_date'], 'yes');
						$_POST[$key]['end_date'] = $this->convert_datetime($_POST[$key]['end_date'], 'yes');
					} else {
						$_POST[$key] = $this->convert_datetime($_POST[$key], 'yes');
					}
				}
			}
		}
		return $args;
    }
    /**
     * @param string $id the id template
     * @param array $languages
     */
    public function action_template_type($id, array $languages) {
        ?>

        <div class="ptb_cmb_input_row">
            <label for="<?php echo $id; ?>_showrange" class="ptb_cmb_input_label">
                <?php _e("Show as range", 'ptb_extra'); ?>
            </label>
            <div class="ptb_cmb_input">
                <input type="checkbox" id="<?php echo $id; ?>_showrange" name="<?php echo $id; ?>_showrange" value="1" />
            </div>
        </div>
        <?php
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
        ?>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[start][dateformat]"><?php _e('Start Date format', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <input type="text" id="ptb_<?php echo $id ?>[start][dateformat]"
                       name="[<?php echo $id ?>][start][dateformat]" value="<?php echo isset($data['start']['dateformat']) ? $data['start']['dateformat'] : 'M j,Y' ?>"
                       />
                <input type="text" id="ptb_<?php echo $id ?>[start][separator]"
                       name="[<?php echo $id ?>][start][separator]" value="<?php echo isset($data['start']['separator']) ? $data['start']['separator'] : '@' ?>"
                       size="1" /> 
				<input type="text" id="ptb_<?php echo $id ?>[start][timeformat]"
                       name="[<?php echo $id ?>][start][timeformat]" value="<?php echo isset($data['start']['timeformat']) ? $data['start']['timeformat'] : 'H:i' ?>"
                       size="4" />
				<?php _e('(e.g. M j,Y @ H:i)', 'ptb_extra') ?>
				<a href="//codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e('More info', 'ptb_extra') ?></a>
            </div>
        </div>
		<div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label>&nbsp;</label>
            </div>
			<div class="ptb_back_active_module_input">
                <input type="checkbox" id="ptb_<?php echo $id ?>_start_hide_data"
                        name="[<?php echo $id ?>][start][hide_date]" value="1"
                        <?php if (isset($data['start']['hide_date'])): ?>checked="checked"<?php endif; ?>/>
				<label for="ptb_<?php echo $id ?>_start_hide_data"><?php _e('Hide Date','ptb_extra'); ?></label>
				<input type="checkbox" id="ptb_<?php echo $id ?>_start_hide_time"
                        name="[<?php echo $id ?>][start][hide_time]" value="1"
                        <?php if (isset($data['start']['hide_time'])): ?>checked="checked"<?php endif; ?>/>
				<label for="ptb_<?php echo $id ?>_start_hide_time"><?php _e('Hide Time','ptb_extra'); ?></label>
            </div>
        </div>
        <?php if(isset($args['showrange']) && $args['showrange'] == 1):?>
		<div class="ptb_back_active_module_row">
			<div class="ptb_back_active_module_label">
				<label for="ptb_<?php echo $id ?>[rangeseperator]"><?php _e('Date Range separator', 'ptb_extra') ?></label>
			</div>
			<div class="ptb_back_active_module_input">
				<input type="text" id="ptb_<?php echo $id ?>[rangeseperator]"
					   name="[<?php echo $id ?>][rangeseperator]" value="<?php echo isset($data['rangeseperator']) ? $data['rangeseperator'] : '-' ?>"
					   />
			</div>
		</div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[end][dateformat]"><?php _e('End Date format', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <input type="text" id="ptb_<?php echo $id ?>[end][dateformat]"
                       name="[<?php echo $id ?>][end][dateformat]" value="<?php echo isset($data['end']['dateformat']) ? $data['end']['dateformat'] : 'M j,Y' ?>"
                       />
                <input type="text" id="ptb_<?php echo $id ?>[end][separator]"
                       name="[<?php echo $id ?>][end][separator]" value="<?php echo isset($data['end']['separator']) ? $data['end']['separator'] : '@' ?>"
                       size="1" /> 
				<input type="text" id="ptb_<?php echo $id ?>[end][timeformat]"
                       name="[<?php echo $id ?>][end][timeformat]" value="<?php echo isset($data['end']['timeformat']) ? $data['end']['timeformat'] : 'H:i' ?>"
                       size="4" />
				<?php _e('(e.g. M j,Y @ H:i)', 'ptb_extra') ?>
				<a href="//codex.wordpress.org/Formatting_Date_and_Time" target="_blank"><?php _e('More info', 'ptb_extra') ?></a>
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label>&nbsp;</label>
            </div>
			<div class="ptb_back_active_module_input">
                <input type="checkbox" id="ptb_<?php echo $id ?>_end_hide_data"
                        name="[<?php echo $id ?>][end][hide_date]" value="1"
                        <?php if (isset($data['end']['hide_date'])): ?>checked="checked"<?php endif; ?>/>
				<label for="ptb_<?php echo $id ?>_end_hide_data"><?php _e('Hide Date','ptb_extra'); ?></label>
				<input type="checkbox" id="ptb_<?php echo $id ?>_end_hide_time"
                        name="[<?php echo $id ?>][end][hide_time]" value="1"
                        <?php if (isset($data['end']['hide_time'])): ?>checked="checked"<?php endif; ?>/>
				<label for="ptb_<?php echo $id ?>_end_hide_time"><?php _e('Hide Time','ptb_extra'); ?></label>
            </div>
        </div>
        <?php endif; ?>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[show_text]"><?php _e('Show text if date has been expired', 'ptb_extra') ?></label>
            </div>
            <?php self::module_language_tabs($id, $data, $languages, 'show_text') ?>
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
        ?>
        <?php if (!isset($args['showrange'])): ?>
            <?php
            if ($value && is_array($value) && isset($value['start_date'])) {
                $value = esc_attr($value['start_date']);
            }
			$value = isset($value) ? $this->convert_datetime($value) : '';
            ?>
            <input  id="ptb_extra_<?php echo $meta_key; ?>" 
                    type="text" 
                    name="<?php echo sprintf('%s', $meta_key); ?>"
                    value="<?php echo $value ?>" class="ptb_extra_input_datepicker"/>

        <?php else: ?>
            <?php
            if (!is_array($value) && $value) {
                $tmp_val = $value;
                $value = array();
                $value['start_date'] = $tmp_val;
            }
			
			$value['start_date'] = isset($value['start_date']) ? $this->convert_datetime($value['start_date']) : '';
			$value['end_date'] = isset($value['end_date']) ? $this->convert_datetime($value['end_date']) : '';
            ?>
            <div class="ptb_table_row">
                <div class="ptb_table_cell">
                    <input
                        type="text" name="<?php echo sprintf('%s[start_date]', $meta_key); ?>"
                        placeholder="<?php _e('Starts on', 'ptb_extra') ?>" class="ptb_extra_input_datepicker" id="<?php echo $meta_key; ?>_start" type="text"
                        value="<?php echo esc_attr($value['start_date']) ?>"/>
                </div>
                <span class="ti-arrow-right"></span>
                <div class="ptb_table_cell">
                    <input 
                        placeholder="<?php _e('Ends on', 'ptb_extra') ?>" class="ptb_extra_input_datepicker" id="<?php echo $meta_key; ?>_end" type="text" name="<?php echo sprintf('%s[end_date]', $meta_key); ?>"
                        value="<?php echo esc_attr($value['end_date']); ?>"/>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }
    
    public function add_date_field($result,$post_type){
        $options = PTB::get_option();
        $cmb = $options->get_cpt_cmb_options($post_type);
        if(!empty($cmb)){
            $values = array(array('text'=>__('Show All','ptb_extra'),'value'=>''),
                            array('text'=>__('Show Past Posts','ptb_extra'),'value'=>'past'),
                            array('text'=>__('Show Upcoming Posts','ptb_extra'),'value'=>'upcoming')
                           );
            foreach($cmb as $key=>$c){
                if(($c['type']==='event_date' && !isset($c['showrange']))){
                    $result['data'][$key]['type'] = 'listbox';
                    $result['data'][$key]['values'] = $values;
                    $result['data'][$key]['label'] = PTB_Utils::get_label($c['name']);
                    $result['data'][$key]['value'] = '';      
                }
            }
        }
        return $result;
    }
    
    public function date_filter(array $args=array()){
        if(!empty($args)){
            $options = PTB::get_option();
            $cmb = $options->get_cpt_cmb_options($args['post_type']);
            $query = array();
            $now = current_time('Y-m-d H:i:s');
            foreach($args as  $key=>$v){
                if (isset($cmb[$key]) && $cmb[$key]['type']==='event_date' &&  !isset($cmb[$key]['showrange']) && $v) {
                    $query[] = array(
                                    'key'=>'ptb_'.$key,
                                    'value'=>$now,
                                    'compare'=>$v==='upcoming'?'>=':'<=',
									'type' => 'DATETIME'
                                );
                    unset($args[$key]);
                }
            }
            if(!empty($query)){
                $args['meta_query'] = $query;
                $args['meta_query']['relation'] = 'AND';
            }
        }
        return $args;
    }

    public function ptb_submission_themplate($id, array $args, array $module = array(), array $post_support, array $languages = array()) {
        ?>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[time]"><?php _e("Show Time", 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input ptb_change_disable" data-disabled="1" data-action="1">
                <input type="checkbox"  id="ptb_<?php echo $id ?>[time]" name="[<?php echo $id ?>][time]" value="1" <?php echo !empty($module['time'])? 'checked="checked"' : '' ?>/>
            </div>
        </div>
        <div class="ptb_back_active_module_row">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[dateformat]"><?php _e('Date format', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <input type="text" id="ptb_<?php echo $id ?>[dateformat]"
                       name="[<?php echo $id ?>][dateformat]" value="<?php echo isset($module['dateformat']) ? $module['dateformat'] : '' ?>"
                       />
                <?php _e('(e.g. yy-mm-dd)', 'ptb_extra') ?> <a href="//api.jqueryui.com/datepicker/#utility-formatDate" target="_blank"><?php _e('More info', 'ptb_extra') ?></a>
            </div>
        </div>
        <div class="ptb_back_active_module_row ptb_maybe_disabled">
            <div class="ptb_back_active_module_label">
                <label for="ptb_<?php echo $id ?>[timeformat]"><?php _e('Time format', 'ptb_extra') ?></label>
            </div>
            <div class="ptb_back_active_module_input">
                <input type="text" id="ptb_<?php echo $id ?>[timeformat]" name="[<?php echo $id ?>][timeformat]" value="<?php echo isset($module['timeformat']) ? $module['timeformat'] : '' ?>"/>
                <ul>
                    <li>H - <?php _e('Hour with no leading 0 (24 hour)','ptb_extra')?></li>
                    <li>HH - <?php _e('Hour with leading 0 (24 hour)','ptb_extra')?></li>
                    <li>h - <?php _e('Hour with no leading 0 (12 hour)','ptb_extra')?></li>
                    <li>hh - <?php _e('Hour with leading 0 (12 hour)','ptb_extra')?></li>
                    <li>m - <?php _e('Minute with no leading 0','ptb_extra')?></li>
                    <li>mm - <?php _e('Minute with leading 0','ptb_extra')?></li>
                    <li>tt - <?php _e('am or pm for AM/PM','ptb_extra')?></li>
                    <li>TT - <?php _e('AM or PM for AM/PM','ptb_extra')?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function ptb_submission_form($post_type, array $args, array $module, $post, $lang, array $languages) {
        $pluginurl = plugin_dir_url(dirname(__FILE__));
        wp_enqueue_style('themify-datetimepicker', $pluginurl . 'admin/css/jquery-ui-timepicker.min.css', array(), self::$version, 'all');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('themify-datetimepicker', $pluginurl . 'admin/js/jquery-ui-timepicker.min.js', array('jquery-ui-datepicker'), self::$version, true);
        wp_enqueue_script(self::$plugin_name . '-submission-date',PTB_Utils::enque_min( $pluginurl . 'public/submission/js/date.js'), array('ptb-submission', 'themify-datetimepicker'), self::$version, true);
        $data = isset($post->ID) ? get_post_meta($post->ID, 'ptb_' . $args['key'], TRUE) : false;
        ?>
        <div class="ptb_back_active_module_input">
            <?php if (!isset($args['showrange'])): ?>
                <div class="ptb-submission-date-wrap">
                    <input <?php if (!empty($module['dateformat'])): ?>data-dateformat="<?php esc_attr_e($module['dateformat'])?>"<?php endif; ?>  <?php if (isset($module['time'])): ?><?php if (!empty($module['timeformat'])): ?>data-timeformat="<?php esc_attr_e($module['timeformat'])?>"<?php endif; ?> data-time="1"<?php endif; ?>  id="ptb_extra_<?php echo $args['key'] ?>" type="text" name="submission[<?php echo $args['key'] ?>]" data-id="<?php echo $args['key'] ?>" value="<?php echo isset($data['start_date']) ? $data['start_date'] : $data; ?>" class="ptb_extra_input_datepicker"/>
                    <i class="fa fa-calendar"></i>
                </div>
            <?php else: ?>
                <?php $data = $data && !is_array($data) ? array('start_date' => $data) : $data; ?>
                <table class="ptb-submission-date-range">
                    <tr>
                        <td class="ptb-submission-date-wrap"><input value="<?php echo isset($data['start_date']) ? $data['start_date'] : '' ?>" <?php if (!empty($module['dateformat'])): ?>data-dateformat="<?php esc_attr_e($module['dateformat'])?>"<?php endif; ?> <?php if (isset($module['time'])): ?><?php if (!empty($module['timeformat'])): ?>data-timeformat="<?php esc_attr_e($module['timeformat'])?>"<?php endif; ?> data-time="1"<?php endif; ?> placeholder="<?php _e('Starts on', 'ptb_extra') ?>" type="text" data-id="<?php echo $args['key'] ?>" name="submission[<?php echo $args['key'] ?>][start_date]" id="ptb_submission_<?php echo $args['key'] ?>" /><i class="fa fa-calendar"></i></td>
                        <td class="ptb-submission-date-arrow"><span class="fa fa-arrow-right"></span></td>
                        <td class="ptb-submission-date-wrap"><input value="<?php echo isset($data['end_date']) ? $data['end_date'] : '' ?>" placeholder="<?php _e('Ends on', 'ptb_extra') ?>" type="text"  name="submission[<?php echo $args['key'] ?>][end_date]" id="ptb_submission_<?php echo $args['key'] ?>_end" /><i class="fa fa-calendar"></i></td>
                    </tr>
                </table>
            <?php endif; ?>
            <?php if (isset($module['show_description'])): ?>
                <div class="ptb-submission-description ptb-submission-<?php echo $args['key'] ?>-description"><?php echo PTB_Utils::get_label($args['description']); ?></div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function ptb_submission_validate(array $post_data, array $args, array $module, $post_type, $post_id, $lang, array $languages) {
        $error = false;
        if (isset($post_data[$module['key']])) {
            $time = isset($module['time']);
            $format = !empty($module['dateformat'])?trim(str_replace(array('yy','MM','mm','m','dd','DD','#'),array('Y','F','#','n','d','L','m'),$module['dateformat'])):'Y-m-d';
            if($time){
                $format.='\@';
                $format.= !empty($module['timeformat'])?
                                            trim(str_replace(array('HH','hh','h','H','mm','m','TT','tt','$','#'),array('$','#','g','G','i','i','A','a','H','h'),$module['timeformat'])):
                                             'h:i a';
            }
            if (isset($args['showrange'])) {
                $values = $post_data[$module['key']];
                $keys = array('start_date', 'end_date');
            } else {
                $keys = array(0);
                $values = array(0 => $post_data[$module['key']]);
            }
            foreach ($keys as $k) {
                if (isset($values[$k]) && trim($values[$k])) {
                    $start_date = sanitize_text_field($values[$k]);
                    $start_date = str_replace(array('AM','PM'),array('am','pm'),$start_date);
                    $convert = DateTime::createFromFormat($format, $start_date);
                    if(!$convert){
                        return PTB_Utils::get_label($args['name']) . __(' has incorrect date format', 'ptb_extra');
                    }
                    
                    $start_date = $convert->format('Y-m-d@h:i a');  
                    $date = explode('@', $start_date);
                    $valid_start_date = $date[0];
                    $valid_start_date = explode('-', $valid_start_date);
                    if (!checkdate($valid_start_date[1], $valid_start_date[2], $valid_start_date[0])) {
                        return PTB_Utils::get_label($args['name']) . __(' has incorrect date format', 'ptb_extra');
                    }
                    if ($time && isset($date[1]) && trim($date[1]) && !preg_match("/(0?\d|1[0-2]):(0\d|[0-5]\d) (AM|PM)/i", $date[1], $matches)) {
                        return PTB_Utils::get_label($args['name']) . __(' has incorrect time format', 'ptb_extra');
                    }
                    if ($k) {
                        $post_data[$module['key']][$k] = $time ? $this->convert_datetime($start_date, 'yes') : $date[0];
                    } else {
                        $post_data[$module['key']] = $time ? $this->convert_datetime($start_date, 'yes') : $date[0];
                    }
                } else {
                    $error = true;
                }
            }
            if ($error && isset($module['required'])) {
                return PTB_Utils::get_label($args['name']) . _e(' is required', 'ptb_extra');
            }
            return $post_data;
        }
    }

    public function ptb_submission_save(array $m, $key, array $post_data, $post_id, $lng) {
        return $m;
    }
    
    
    public function search_date_template($post_type,$id,$args,$module,$value,$label,$lang,$languages){
        $name =  PTB_Utils::get_label($args['name']);
        $name = $name ? sanitize_title($name) : $args['key'];
        if(isset($this->search_date[$name])){
            $value = $this->search_date[$name];
        }
        PTB_Search_Public::show_as('date', $post_type, $id, $name, $value, $args['key'], $label);
    }
    
    public function filter_value(array $data, $post_id,array $options,array $cmb_options, $post_support, $post_taxonomies){
        $found = false;
        
        foreach($options as $k=>$opt){
            if($opt['type']==='event_date'){
                if(!empty($_REQUEST[$k.'-from'])){
                    $data[$opt['key']]['from'] = $_REQUEST[$k.'-from'];
                    $found = 1;
                }
                if(!empty($_REQUEST[$k.'-to'])){
                    $data[$opt['key']]['to'] = $_REQUEST[$k.'-to'];
                    $found = 1;
                }
            }
        }
        if($found){
            $this->search_date = $data;
            remove_filter('ptb_search_filter_by_slug',array($this,'filter_value'),10,6);
        }
        return $data;
    }
    
    public function search_date($post_id,$post_type,$value,$args,$meta_key,$post_taxonomies){
        $meta_key = 'ptb_'.$meta_key;
        $range = !empty($args['showrange']);
        $include =  !empty($post_id) ? implode(',', array_keys($post_id)) : FALSE;
        $query_args = array(
            'fields' => 'ids',
            'post_type' => $post_type,
            'orderby' => 'ID',
            'order' => 'ASC',
            'nopaging' => 1,
            'include'=> $include,
            'meta_query' => array(
                    array(
                        'type'  => 'date',
                        'key' =>$meta_key
                    )
            )
        );
        $from = $to = false;
        $condition = $post_id = array();
        if(!empty($value['from'])){
            $from = esc_sql($value['from']);
            if(!$range){
                $query_args['meta_query'][0]['compare'] = '>=';
                $query_args['meta_query'][0]['value'] = $from;
            }
            else{
                $condition[] = "STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value,'\"',4),'\"',-1),'%Y-%m-%d')>='$from'";
            }
        }
        if(!empty($value['to'])){
            $to = esc_sql($value['to']);
            if(!$range){
                $query_args['meta_query'][0]['compare'] = $from?'BETWEEN':'<=';
                $query_args['meta_query'][0]['value'] = $from?array($from,$to):$to;
            }
            else{
                $condition[] ="STR_TO_DATE(SUBSTRING_INDEX(SUBSTRING_INDEX(meta_value,'\"',-2),'\"',1),'%Y-%m-%d')<='$to'";
            }
        }
        if($range && !empty($condition)){
            global $wpdb;
            if($include){
                $condition[] = 'post_id IN('.$include.')'; 
            }
            $condition = implode(' AND ',$condition);
            $posts = $wpdb->get_results("SELECT `post_id` FROM `{$wpdb->postmeta}` WHERE `meta_key` = '$meta_key' AND $condition");
           
            if(!empty($posts)){
                $ids = array();
                foreach ($posts as $p) {
                    $ids[] = $p->post_id;
                } 
                unset($query_args['meta_query']);
                $query_args['include'] = $ids;
            }
            else{
                $from = $to = false;
            }
        }
      
        if($from || $to){
            $posts_array = get_posts($query_args);
            if(!empty($posts_array)){
                foreach ($posts_array as $p) {
                    $post_id[$p] = 1;
                }
            }
        }
        return $post_id;
    }

}