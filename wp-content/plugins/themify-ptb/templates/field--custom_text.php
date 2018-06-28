<?php
/**
 * Custom Text field template
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

<?php if (!empty($data['text'][$lang])): ?>
	<?php echo PTB_CMB_Base::format_text($data['text'][$lang],$is_single); ?>
<?php endif; ?>
