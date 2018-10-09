<?php

class Builder_Timeline_Text_Source {

	public function get_id() {
		return 'text';
	}

	public function get_name() {
		return __( 'Text', 'builder-timeline' );
	}

	public function get_items( $args ) {
		$items = array();
                if(!empty($args['text_source_timeline'])){
                    foreach( $args['text_source_timeline'] as $key => $item ) {
                            $item = wp_parse_args( $item, array( 
                                'image_timeline' => '', 
                                'title_timeline' => '', 
                                'icon_timeline' => '', 
                                'iconcolor_timeline' => '', 
                                'date_timeline' => '', 
                                'content_timeline' => '',
                                'link_timeline' => '' 
                            ) );
                            $items[] = array(
                                    'id' => $key,
                                    'title' => $item['title_timeline'],
                                    'icon' => $item['icon_timeline'],
                                    'icon_color' => $item['iconcolor_timeline'],
                                    'link' => '' !== $item['link_timeline'] ? $item['link_timeline'] : null,
                                    'date' => $item['date_timeline'],
                                    'date_formatted' => $item['date_timeline'],
                                    'hide_featured_image' => $item['image_timeline'] === '',
                                    'image' => '<img src="' . $item['image_timeline'] . '" alt="' . $item['title_timeline'] . '" />',
                                    'hide_content' =>$item['content_timeline'] === '',
                                    'content' => apply_filters( 'themify_builder_module_content', $item['content_timeline'] ),
                            );
                    }
                }
		return apply_filters( 'builder_timeline_source_text_items', $items );
	}

	public function get_options() {
		return array(
			array(
				'id' => 'text_source_timeline',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'title_timeline',
						'type' => 'text',
						'label' => __('Title', 'builder-timeline'),
						'class' => 'large',
						'render_callback' => array(
                                                    'repeater' => 'text_source_timeline',
                                                    'live-selector'=>'.module-timeline-title'
						)
					),
					array(
						'id' => 'link_timeline',
						'type' => 'text',
						'label' => __('Link', 'builder-timeline'),
						'class' => 'large',
						'render_callback' => array(
							'repeater' => 'text_source_timeline'
						)
					),
					array(
						'id' => 'icon_timeline',
						'type' => 'text',
						'iconpicker' => true,
						'label' => __('Icon', 'builder-timeline'),
						'class' => 'medium',
						'render_callback' => array(
							'repeater' => 'text_source_timeline'
						)
					),
					array(
						'id' => 'iconcolor_timeline',
						'type' => 'text',
						'colorpicker' => true,
						'label' => __('Icon Color', 'builder-timeline'),
						'class' => 'small',
						'render_callback' => array(
							'repeater' => 'text_source_timeline'
						)
					),
					array(
						'id' => 'date_timeline',
						'type' => 'text',
						'label' => __('Date', 'builder-timeline'),
						'class' => 'medium',
						'after' => __( '(eg. Sep 2014)', 'builder-timeline' ),
						'render_callback' => array(
                                                    'repeater' => 'text_source_timeline',
                                                    'live-selector'=>'.module-timeline-date'
						)
					),
					array(
						'id' => 'image_timeline',
						'type' => 'image',
						'label' => __('Image', 'builder-timeline'),
						'class' => 'xlarge',
						'render_callback' => array(
							'repeater' => 'text_source_timeline'
						)
					),
					array(
						'id' => 'content_timeline',
						'type' => 'wp_editor',
						'label' => false,
						'class' => 'fullwidth',
						'rows' => 6,
						'render_callback' => array(
                                                    'repeater' => 'text_source_timeline',
                                                    'live-selector'=>'.entry-content'
						)
					)
				),
				'wrap_with_class' => 'tb_group_element tb_group_element_text'
			)
		);
	}

}