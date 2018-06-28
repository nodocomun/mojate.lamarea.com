<?php
/**
 * Comments field template
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

<div class="ptb_comments">
	<?php
	//Gather comments for a specific page/post
	$comments = get_comments(array(
		'post_id' => get_the_ID(),
		'status' => 'approve' //Change this to the type of comments to be displayed
	));
	?>
	<ul class="commentlist">    
		<?php
		//Display the list of comments
		wp_list_comments(array(
			'per_page' => 10, //Allow comment pagination
			'reverse_top_level' => false //Show the latest comments at the top of the list
				), $comments);
		?>
	</ul>
	<?php comment_form(); ?>
</div>
