<?php
/**
 * Template for single post view
 * @package themify
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify;
?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<!-- layout-container -->
	<div id="layout" class="pagewidth clearfix">

		<?php themify_content_before(); // hook ?>


		<!-- content -->
		<div id="content" class="list-post">
			<?php themify_content_start(); // hook ?>

			<?php get_template_part( 'includes/loop', get_post_type()); ?>

			<?php wp_link_pages( array( 'before' => '<p class="post-pagination"><strong>' . __( 'Pages:', 'themify' ) . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

			<?php get_template_part( 'includes/author-box', 'single' ); ?>

			<?php get_template_part( 'includes/post-nav' ); ?>

			<?php if ( is_single() && 'none' != themify_get( 'setting-relationship_taxonomy' ) ) : ?>
				<?php get_template_part( 'includes/related-posts', 'loop' ); ?>
			<?php endif; ?>

			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>


			<?php themify_content_end(); // hook ?>
					<?php if (get_post(get_post_thumbnail_id())->post_excerpt) { // search for if the image has caption added on it ?>
    <span class="featured-image-caption">
        <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); // displays the image caption ?>
    </span>
<?php } ?>



		</div>
		<!-- /content -->
		<?php themify_content_after(); // hook ?>

		<?php
		/////////////////////////////////////////////
		// Sidebar
		/////////////////////////////////////////////
		if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

	</div>
	<!-- /layout-container -->

<?php endwhile; ?>

<?php get_footer(); ?>