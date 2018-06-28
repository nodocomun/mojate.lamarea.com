<?php
/**
 * Taxonomies field template
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

<?php if (!empty($meta_data['taxonomies'])): ?>
		<?php $taxs = array(); 
			  static $taxonomy = array();
		?>
		<?php foreach ($meta_data['taxonomies'] as $tax): ?>
			<?php if (isset($tax->taxonomy) && $data['taxonomies'] === $tax->taxonomy): ?>
				<?php
				$get_tax =!isset($taxonomy[$tax->taxonomy])? $this->options->get_custom_taxonomy($data['taxonomies']):$taxonomy[$tax->taxonomy];
			  
				if($get_tax->ad_publicly_queryable){
					$term_link = get_term_link($tax, $tax->taxonomy);
					$taxs[$tax->term_id] = '<a href="' . $term_link . '">' . $tax->name . '</a>';
				}
				else{
				   $taxs[$tax->term_id] =  $tax->name;
				}
				
				?>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if (!empty($taxs)): ?>
			<?php
			if (!$data['seperator']) {
				$data['seperator'] = ', ';
			}
			?>
			<div class="ptb_module_inline ptb_taxonomies_<?php echo str_replace('-', '_', $data['taxonomies']) ?>">
				<?php echo implode($data['seperator'], $taxs) ?>
			</div>
		<?php endif; ?>
<?php endif; ?>
