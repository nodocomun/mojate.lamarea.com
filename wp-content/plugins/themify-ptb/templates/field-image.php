<?php
/**
 * Image field template
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

<div class="ptb_image">
<?php
	$url = $img_id = false;
        $alt = $title = '';
	if( ! empty( $meta_data[0] ) ) {
            if(is_numeric( $meta_data[0] )){
                $url = wp_get_attachment_url( $meta_data[0] );
                $img_id = $meta_data[0];
                
            }else{
                $url =  $meta_data[0];
            }
	} elseif( ! empty( $meta_data[1] ) ) {
		$url = $meta_data[1];
	}
        if($img_id===false){
            $img_id = PTB_Utils::get_image_id_by_url($url);
        }
        
	$url = PTB_CMB_Base::ptb_resize( $url, $data['width'], $data['height'] );
	if( $url ) {
            if($img_id){
                $title = get_the_title($img_id);
                $alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
                if(!$alt && $title){
                    $alt = $title;
                }
                elseif(!$title && $alt){
                    $title = $alt;
                }
            }
            $link = ! empty( $meta_data[2] ) ? esc_url( $meta_data[2] ) : false;
            $link = ! $link && ! empty( $data['custom_url'] ) ? esc_url( $data['custom_url'] ) : $link;
            $link = ! $link && isset( $data['permalink'] ) ? $meta_data['post_url'] : $link;
            $image = sprintf( '<figure class="ptb_post_image clearfix"><img src="%s" alt="%s" title="%s"/></figure>', $url,esc_attr($alt),esc_attr($title) );
            echo $link ? sprintf( '<a href="%s">%s</a>', $link, $image ) : $image;
	}
?>
</div>