<?php
$vals = $meta_data[$args['key']];
if (!$vals || empty($vals)) {
    return;
}
?>
<a href="tel:<?php echo esc_attr($vals); ?>" class="ptb_extra_telephone ptb_extra_<?php echo $args['key'] ?>"><?php echo empty($data['placement'])?$vals:trim($data['placement']);?></a>

