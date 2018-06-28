<?php
/**
 * Permalink field template
 *
 * @var string $type
 * @var array $args
 * @var array $data
 * @var array $meta_data
 * @var array $lang
 * @var boolean $is_single single page
 * @var string $index index in themplate
 *
 * @package Themify PTB
 */
?>

<?php
$class = $style = array();
$none = true;
if (!empty($data['styles'])) {
	if (!is_array($data['styles'])) {
		$data['styles'] = array($data['styles']);
	}
	$none = array_search('none', $data['styles']) === FALSE;
	$class[] = $none ? implode(' ', $data['styles']) : 'none';
}
if ($none) {
	if (!empty($data['custom_color'])) {
		$style[] = 'background-color:' . $data['custom_color'] . ' !important;';
	} elseif (isset($data['color'])) {
		$class[] = $data['color'];
	}
}
if (!empty($data['size'])) {
	$class[] = $data['size'];
}
if (!empty($data['icon'])) {
	$class[] = 'fa';
	$class[] = $data['icon'];
}
if (isset($data['link_link']) && $data['link_link'] === 'lightbox') {
	$class[] = 'ptb_open_lightbox';
	$meta_data['post_url'] = admin_url('admin-ajax.php?id=' . get_the_ID() . '&action=ptb_single_lightbox');
}

if (!empty($data['text_color'])) {
	$style[] = 'color:' . $data['text_color'] . ' !important;';
}
?>
<div class="ptb_permalink">
	<a <?php if (!empty($style)): ?>style="<?php esc_attr_e(implode(' ', $style)) ?>"<?php endif; ?> class="ptb_link_button <?php if (!empty($class)): ?><?php if ($none): ?>shortcode <?php endif; ?><?php esc_attr_e(implode(' ', $class)) ?><?php endif; ?>" <?php if (isset($data['link_link']) && $data['link_link'] === 'new_window'): ?>target="_blank"<?php endif; ?>
									 href="<?php echo $meta_data['post_url'] ?>"><?php echo isset($data['text'][$lang]) ? $data['text'][$lang] : '' ?></a>    
</div>
