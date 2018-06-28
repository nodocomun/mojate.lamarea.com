<?php
/**
 * Radio Button field template
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

if ($meta_data && !empty($args['options'])) {
	foreach ($args['options'] as $opt) {
		if (isset($meta_data[$args['key']]) && $opt['id'] === $meta_data[$args['key']]) {
			$this->get_text($opt[$lang]);
			break;
		}
	}
}