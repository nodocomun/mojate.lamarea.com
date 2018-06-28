<?php
/**
 * Title field template
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

<h<?php echo $data['title_tag'] ?> class="ptb_post_title ptb_entry_title" itemprop="name">
	<?php
	if (!empty($data['title_link'])) {
		echo '<a ' . ($data['title_link'] == 'lightbox' ? 'data-href="' . admin_url('admin-ajax.php?id=' . get_the_ID() . '&action=ptb_single_lightbox') . '" class="ptb_open_lightbox"' : '') . ($data['title_link'] == 'new_window' ? 'target="_blank"' : '') . 'href="' . $meta_data['post_url'] . '">';
	}
	the_title();
	if (!empty($data['title_link'])) {
		echo '</a>';
	}
	?>
</h<?php echo $data['title_tag'] ?>>
