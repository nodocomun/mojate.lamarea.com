<?php
/**
 * Template for event post type display.
 * @package themify
 * @since 1.0.0
 */
?>
<?php if(!is_singular( 'event' )){ global $more; $more = 0; } //enable more link ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify, $themify_event;
$is_singular  =  is_singular( 'event' );
?>

<?php themify_post_before(); // hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class('post clearfix event-post'); ?>>
	<?php themify_post_start(); // hook ?>

	<div class="post-content">

		<?php if ( !$is_singular || ( isset( $themify->is_event_widget ) && $themify->is_event_widget ) ) : ?>
			<?php get_template_part( 'includes/post-media',	'event' ); ?>

			<?php if($themify->hide_title != 'yes'): ?>
				<?php themify_post_title(); ?>
			<?php endif; //post title ?>

			<div class="<?php echo $is_singular ? 'event-single-wrap clearfix' : 'event-info'; ?>">

				<?php if ( themify_check( 'location' ) && ( isset( $themify->hide_event_location ) && $themify->hide_event_location != 'yes' ) ) : ?>
					<span class="location"><?php echo themify_get( 'location' ); ?></span>
				<?php endif; ?>

				<?php
				$has_start_date = themify_check( 'start_date' );
				$has_end_date = themify_check( 'end_date' );
				if ( ( $has_start_date || $has_end_date ) && ( isset( $themify->hide_event_date ) && $themify->hide_event_date != 'yes' ) ) : ?>
					<span class="day">
                    <?php $repeat = false;?>
					<?php if ( $has_start_date ) : ?>
						<span class="event-start-date">
						<?php
                        $repeat = themify_get('repeat');
                        if($repeat){
                            $repeat_x = intval(themify_get('repeat_x'));
                            if(!$repeat_x || $repeat_x<0){
                                $repeat = false;
                            }
                        }
						$start_date = themify_get( 'start_date' );
						$start_date_parts = explode( ' ', $start_date );
						$time =  explode( '@', $start_date );
						if(isset($time[1])){
							$start_date_parts[1] = $time[1];
						}
						echo $repeat?
                                                        themify_theme_get_repeat_date($repeat, $repeat_x,$start_date_parts[0], $start_date_parts[1]):
                                                        date_i18n( get_option( 'date_format' ), strtotime( $start_date_parts[0] ) ) . _x( ' @ ', 'Connector between date and time (with spaces around itself) in event date and time.', 'themify' ) . date_i18n( get_option( 'time_format' ), strtotime( $start_date_parts[1] ) );
						?>
						</span>
					<?php endif; // has start date ?>

                    <?php if (!$repeat && $has_end_date  && ($is_singular || (!$is_singular && !themify_check( 'event_end_date_hide' )))) :?>
						<span class="event-end-date">
						<?php
						$end_date = themify_get( 'end_date' );
						$end_date_parts = explode( ' ', $end_date );
						$time =  explode( '@', $end_date );
						if(isset($time[1])){
							$end_date_parts[1] = $time[1];
						}
                                                echo !isset($start_date_parts) || $start_date_parts[0]!=$end_date_parts[0]?_x( ' &#8211; ', 'Character to provide a hint that this is the event end date and time.', 'themify' ) . date_i18n( get_option( 'date_format' ), strtotime( $end_date_parts[0] ) ):' &#8211; ';
                                                echo  _x( ' @ ', 'Connector between date and time (with spaces around itself) in event date and time.', 'themify' ) . date_i18n( get_option( 'time_format' ), strtotime( $end_date_parts[1] ) );
						?>
						</span>
					<?php endif; ?>
					</span>
				<?php endif; ?>

			</div><!-- /.event-info -->

		<?php endif; // is not single ?>

		<div class="entry-content">

			<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

				<?php the_excerpt(); ?>

				<?php if( themify_check('setting-excerpt_more') ) : ?>
					<p><a href="<?php the_permalink(); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
				<?php endif; ?>

			<?php elseif($themify->display_content == 'none'): ?>

			<?php else: ?>

				<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

			<?php endif; //display content ?>

		</div><!-- /.entry-content -->

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

	</div>
	<!-- /.post-content -->
	<?php themify_post_end(); // hook ?>

</article>
<!-- /.post -->
<?php themify_post_after(); // hook ?>
