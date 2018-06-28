<?php
/**
 * Post Tag field template
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
$key = $type == 'post_tag' ? 'tags_input' : 'post_category';

if (!empty($meta_data[$key])): ?>
	<span class="ptb_post_category ptb_post_meta">
		<?php
		if (!$data['seperator']) {
			$data['seperator'] = ',';
		}
		?>
		<?php if ($key === 'tags_input'): ?>
			<?php the_tags('', $data['seperator'], ''); ?>
		<?php else: ?>
			<?php the_category($data['seperator']); ?>
		<?php endif; ?>
	</span>   
<?php endif; ?>
