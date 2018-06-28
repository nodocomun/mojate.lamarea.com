<?php
/**
 * Email field template
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

if (!empty($meta_data[$args['key']])) {
	$email = antispambot($meta_data[$args['key']]);
	?>  
	<a href="mailto:<?php echo $email ?>">
		<?php if(isset($data['gravatar']) && $data['gravatar']):?>
			<?php echo get_avatar( $meta_data[$args['key']],$data['gravatar_size'],'',false,array('class'=>'ptb_gravatar')); ?> 
		<?php endif;?>
		<span><?php echo $email ?></span>
	</a>
<?php
}