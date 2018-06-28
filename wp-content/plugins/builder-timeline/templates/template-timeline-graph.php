<?php
/**
 * Timeline TimelineJS template
 *
 * @var $items
 * @var $settings
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$date_format = apply_filters( 'builder_timeline_graph_date_format', 'Y,m,d G:i:s', $settings );

if( ! function_exists( 'builder_timeline_get_graph_locale' ) ) :
/**
 * Get locale as expected by the Timeline script
 * Handles special cases where iso codename and language code do not match
 *
 * @return string
 */
function builder_timeline_get_graph_locale() {
        static $locale = null;
        if($locale===null){
            $locale = get_locale();
            switch ( $locale ) {
                    case 'cs-CZ': $locale = 'cz'; break;
                    case 'pt_BR': $locale = 'pt-br'; break;
                    case 'zh_TW': $locale = 'zh-tw'; break;
                    case 'zh_CN': $locale = 'zh-cn'; break;
                    default: $locale = substr( get_locale(), 0, 2 ); break;
            }
        }
	return $locale;
}
endif;

$data = array(
	'timeline' => array(
		'type' => 'default',
		'date' => array()
	)
);
foreach( $settings['items'] as $item ) {
	$item_data = array(
		'startDate' => date( $date_format, strtotime( $item['date'] ) ),
		'endDate' => date( $date_format, strtotime( $item['date'] ) ),
		'headline' => isset( $item['link'] )
			? '<a href="' . $item['link'] . '">' . $item['title'] . '</a>'
			: $item['title'],
		'text' => $item['content'],
		'asset' => array(
			'media' => '',
			'credit' => '',
			'caption' => ''
		)
	);
	if( $item['hide_featured_image'] !== 'yes' ) {
		preg_match( '/src=[\"\'](.*?)[\'\"]/', $item['image'], $matches );
		$item_data['asset']['media'] = isset( $matches[1] ) ? $matches[1] : '';
	}
	$data['timeline']['date'][] = $item_data;
}
$config = array(
	'type' => 'timeline',
	'width' => '100%',
	'height' => 650,
	'embed_id' => 'timeline-embed-' . $module_ID,
        'id'=>'story-js-'.$module_ID,
	'lang' => builder_timeline_get_graph_locale(),
	'start_at_end' => !empty( $settings['start_at_end'] )? 1 : 0,
);
?>
<div class="timeline-embed" id="timeline-embed-<?php echo $module_ID; ?>" data-id="<?php echo $module_ID;; ?>" data-data="<?php esc_attr_e(base64_encode(json_encode( $data ))); ?>" data-config='<?php echo json_encode( $config ); ?>'></div>