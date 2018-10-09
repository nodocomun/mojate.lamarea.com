<?php
/**
 * Text field template
 *
 * To override this template copy it to <your_theme>/plugins/themify-ptb/templates/field-text.php
 *
 * @var string $type
 * @var array $args
 * @var array $data
 * @var array $meta_data
 * @var array $lang
 * @var boolean $is_single single page
 *
 * @package Themify PTB
 */
?>

<?php
$meta_data = $meta_data[$args['key']];
if (!isset($data['display'])) {
	$data['display'] = 'one_line';
}
if (empty($data['seperator'])) {
	$data['seperator'] = ', ';
}
$data['seperator'] = trim($data['seperator']) . ' ';
if ($args['repeatable']) {
	if (!is_array($meta_data)) {
		$meta_data = array($meta_data);
	}
	$seperator = $data['display'] === 'one_line' ? $data['seperator'] : FALSE;
	$this->get_repeateable_text($data['display'], $meta_data, $seperator);
} else {
	if (is_array($meta_data)) {
		if ($data['display'] === 'one_line') {
			$text = implode($data['seperator'], $meta_data);
		}
	} else {
		$text = $meta_data;
	}
	?>  
	<?php if (!empty($data['tag'])): ?>
		<?php if(!empty($text) || $text==='0'):?>
			<<?php echo $data['tag'] ?>><?php echo $text; ?></<?php echo $data['tag'] ?>>
		<?php endif; ?>
	<?php else: ?>
		<?php $this->get_text($text); ?>
	<?php endif;
}