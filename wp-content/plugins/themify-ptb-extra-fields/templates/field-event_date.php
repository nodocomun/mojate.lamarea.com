<?php
/* Start of old plugin data support */
if (isset($data['dateformat'])) {
    if (!isset($data['start']['dateformat'])) {
        $data['start']['dateformat'] = $data['dateformat'];
    }
    if (!isset($data['end']['dateformat'])) {
        $data['end']['dateformat'] = $data['dateformat'];
    }
}
if (isset($data['timeformat'])) {
    if (!isset($data['start']['timeformat'])) {
        $data['start']['timeformat'] = $data['timeformat'];
    }
    if (!isset($data['end']['timeformat'])) {
        $data['end']['timeformat'] = $data['timeformat'];
    }
}
if (isset($data['hide_time'])) {
    if (!isset($data['start']['hide_time'])) {
        $data['start']['hide_time'] = $data['hide_time'];
    }
    if (!isset($data['end']['hide_time'])) {
        $data['end']['hide_time'] = $data['hide_time'];
    }
}
/* End of old plugin data support */

$startDateFormat = trim($data['start']['dateformat']) ? $data['start']['dateformat'] : get_option('date_format');
$startTimeFormat = trim($data['start']['timeformat']) ? $data['start']['timeformat'] : get_option('time_format');
$hide = array(array('date' => isset($data['start']['hide_date']), 'time' => isset($data['start']['hide_time'])));
$timeseperator = isset($data['start']['separator']) ? $data['start']['separator'] : '@';
$format = array(array('date' => $startDateFormat, 'time' => $startTimeFormat, 'separator' => $timeseperator));
$key = $args['key'];

if (isset($args['showrange']) && isset($meta_data[$key]['end_date']) && is_array($meta_data[$key])) {
    $endDateFormat = trim($data['end']['dateformat']) ? $data['end']['dateformat'] : get_option('date_format');
    $endTimeFormat = trim($data['end']['timeformat']) ? $data['end']['timeformat'] : get_option('time_format');
    $timeseperator = isset($data['end']['separator']) ? $data['end']['separator'] : '@';
    $dates = array('start_date', 'end_date');
    $format[] =  array('date' => $endDateFormat, 'time' => $endTimeFormat, 'separator' => $timeseperator);
    $hide[] = array('date' => isset($data['end']['hide_date']), 'time' => isset($data['end']['hide_time']));
} else {
    $dates = array(0);
    $meta_data[$key] = array('0' => is_array($meta_data[$key]) && isset($meta_data[$key]['start_date']) ? $meta_data[$key]['start_date'] : $meta_data[$key]);
}
$_text = isset($data['show_text'][$lang]) && $data['show_text'][$lang] ? trim($data['show_text'][$lang]) : false;
if ($_text) {
    $now = time();
}
 foreach ($dates as $k => $date): 
    if (isset($meta_data[$key][$date]) && $meta_data[$key][$date]): 
        if ($hide[$k]) {
            $tmp = explode('@', $meta_data[$key][$date]);
            $post_date = '';
            if (!$hide[$k]['date']) {
                $post_date .= $tmp[0];
            }

            if (!$hide[$k]['time']) {
                $post_date .= $tmp[1];
            }
        } else {
            $post_date = str_replace('@', '', $meta_data[$key][$date]);
        }
        $strtotime = strtotime($post_date);
        $post_date = '';
        if (!$hide[$k]['date']) {
            $post_date .= date_i18n($format[$k]['date'], $strtotime);
            if (!$hide[$k]['time']) {
                $post_date .= ' ' . ($format[$k]['separator']) . ' ';
            }
        }
        if (!$hide[$k]['time']) {
            $post_date .= date_i18n($format[$k]['time'], $strtotime);
        }
        if ($_text && $strtotime <= $now) {
            $c = count($dates);
            if ($c === 1 || ($c === 2 && $k != 0)) {
                $post_date = $_text;
            }
        }
        ?>
        <?php if ($k != 0 && !empty($data['rangeseperator'])): ?>
            <span class="ptb_extra_range_seperator"><?php echo $data['rangeseperator'] ?></span>
        <?php endif; ?>
        <time class="ptb_extra_post_date ptb_extra_post_meta" datetime="<?php echo date('Y-m-d', $strtotime) ?>" itemprop="datePublished">
            <?php echo $post_date ?>
        </time>
    <?php endif; ?>
<?php endforeach ?>