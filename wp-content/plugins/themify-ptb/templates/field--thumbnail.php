<?php
/**
 * Thumbnail field template
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

<?php if (has_post_thumbnail()): ?>

	<?php
	$thumb_id = get_post_thumbnail_id();
	$thumb = get_post(get_post_thumbnail_id());
	$url = wp_get_attachment_url($thumb_id);
	$url = PTB_CMB_Base::ptb_resize($url, $data['width'], $data['height'], false, true, $thumb_id);
	$title = ! empty( $thumb ) && $thumb->post_title ? $thumb->post_title : (isset($meta_data['post_title']) ? $meta_data['post_title'] : '');
	$alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
	if (!$alt) {
		$alt = $title;
	}
	?>
	<figure class="ptb_post_image clearfix">
		<?php
		if (!empty($data['thumbnail_link'])): echo '<a ' . ($data['thumbnail_link'] == 'lightbox' ? 'data-href="' . admin_url('admin-ajax.php?id=' . get_the_ID() . '&action=ptb_single_lightbox') . '" class="ptb_open_lightbox"' : '') . ($data['thumbnail_link'] == 'new_window' ? 'target="_blank"' : '') . ' href="' . $meta_data['post_url'] . '">';
		endif;
		?>
		<img src="<?php echo $url ?>" alt="<?php echo $alt ?>"
			 title="<?php echo $title ?>"/>
			 <?php
			 if (!empty($data['thumbnail_link'])): echo '</a>';
			 endif;
			 ?>
	</figure>
<?php endif; ?>
