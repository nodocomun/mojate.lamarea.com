<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php
$categories = wp_get_object_terms(get_the_id(), 'portfolio-category');
$class = '';
foreach($categories as $cat){
	$class .= ' cat-'.$cat->term_id;
}
?>

<?php themify_post_before(); //hook ?>
<article id="portfolio-<?php the_id(); ?>" class="<?php echo implode(' ', get_post_class('post clearfix portfolio-post' . $class)); ?>">
	<?php themify_post_start(); //hook ?>

	<?php if ( ! is_singular( 'portfolio' ) ) : ?>
		<?php if( $themify->hide_image != 'yes' ) : ?>
			<?php get_template_part( 'includes/post-media', get_post_type() ); ?>
		<?php endif //hide image ?>
	<?php endif; ?>

	<div class="post-content">

		<?php if ( ! is_singular( 'portfolio' ) ) : ?>

			<?php if($themify->hide_title != 'yes'): ?>
				<?php themify_post_title(); ?>
			<?php endif; //post title ?>

			<?php if ( $themify->hide_date != 'yes' ): ?>
				<time class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
			<?php endif; //post date ?>

			<?php if ( $themify->hide_meta != 'yes' ): ?>
				<p class="post-meta entry-meta">
					<?php the_terms( get_the_id(), get_post_type() . '-category', '<span class="post-category">', ' <span class="separator">/</span> ', ' </span>' ) ?>
				</p>
			<?php endif; //post meta ?>
		<?php endif; ?>

		<div class="entry-content">
			<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>
				<?php the_excerpt(); ?>
			<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>
			<?php else: ?>
				<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
			<?php endif; //display content ?>
		</div><!-- /.entry-content -->

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

	</div>
	<!-- /.post-content -->

	<?php themify_post_end(); //hook ?>
</article>
<!-- /.post -->
<?php themify_post_after(); //hook ?>
