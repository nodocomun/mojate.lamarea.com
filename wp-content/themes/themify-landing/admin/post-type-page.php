<?php
/**
 * Appearance Tab for Themify Custom Panel
 *
 * @since 1.0.0
 *
 * @return array
 */
function themify_theme_page_appearance_meta_box() {
	return array(
		// Header Design
		array(
			'name'        => 'header_design',
			'title'       => __( 'Header Design', 'themify' ),
			'description' => '',
			'type'        => 'layout',
			'show_title'  => true,
			'meta'        => array(
				array(
					'value' => 'default',
					'img'   => 'images/layout-icons/default.png',
					'title' => __( 'Header Horizontal', 'themify' ),
					'selected' => true,
				),
				array(
					'value' => 'header-horizontal',
					'img'   => 'images/layout-icons/header-horizontal.png',
					'title' => __( 'Header Horizontal', 'themify' ),
				),
				array(
					'value' => 'header-block',
					'img'   => 'images/layout-icons/header-block.png',
					'title' => __( 'Header Block', 'themify' ),
				),
				array(
					'value' => 'none',
					'img'   => 'images/layout-icons/none.png',
					'title' => __( 'No Header ', 'themify' ),
				)
			),
			'hide'		  => 'none',
			'default' => 'default'
		),
		// Sticky Header
		array(
			'name'        => 'fixed_header',
			'title'       => __( 'Sticky Header', 'themify' ),
			'description' => '',
			'type'        => 'dropdown',
			'meta'        => array(
				array( 'value' => 'default', 'name' => '', 'selected' => true ),
				array( 'value' => 'yes', 'name' => __( 'Yes', 'themify' ) ),
				array( 'value' => 'no', 'name' => __( 'No', 'themify' ) )
			),
			'class'     => 'hide-if none',
			'default' => 'default'
		),
		// Header Elements
		array(
			'name' 	=> '_multi_header_elements',
			'title' => __('Header Elements', 'themify'),
			'description' => '',
			'type' 	=> 'multi',
			'class' => 'hide-if none',
			'meta'	=> array(
				'fields' => array(
					// Show Site Logo
					array(
						'name' 	=> 'exclude_site_logo',
						'description' => '',
						'title' => __( 'Show Site Logo', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
					// Show Site Tagline
					array(
						'name' 	=> 'exclude_site_tagline',
						'description' => '',
						'title' => __( 'Show Site Tagline', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
					// Show Search Form
					array(
						'name' 	=> 'exclude_search_form',
						'description' => '',
						'title' => __( 'Show Search Form', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
					// Show RSS Link
					array(
						'name' 	=> 'exclude_rss',
						'description' => '',
						'title' => __( 'Show RSS Link', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
					// Show Social Widget
					array(
						'name' 	=> 'exclude_social_widget',
						'description' => '',
						'title' => __( 'Show Social Widget', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
					// Show Menu Navigation
					array(
						'name' 	=> 'exclude_menu_navigation',
						'description' => '',
						'title' => __( 'Show Menu Navigation', 'themify' ),
						'type' 	=> 'dropdownbutton',
						'states' => themify_ternary_states( array(
							'icon_no' => THEMIFY_URI . '/img/ddbtn-check.png',
							'icon_yes' => THEMIFY_URI . '/img/ddbtn-cross.png',
						) ),
						'class' => 'hide-if none',
						'after' => '<div class="clear"></div>',
					),
				),
				'description' => '',
				'before' => '',
				'after' => '',
				'separator' => ''
			)
		),
		// Header Wrap
		array(
			'name'          => 'header_wrap',
			'title'         => __( 'Header Background', 'themify' ),
			'description'   => '',
			'type'          => 'radio',
			'show_title'    => true,
			'meta'          => array(
				array(
					'value'    => 'solid',
					'name'     => __( 'Solid Background', 'themify' ),
					'selected' => true
				),
				array(
					'value' => 'transparent',
					'name'  => __( 'Transparent Background', 'themify' )
				),
			),
			'enable_toggle' => true,
			'class'     => 'hide-if none',
			'default' => 'solid'
		),
		// Background Color
		array(
			'name'        => 'background_color',
			'title'       => '',
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
		),
		// Background image
		array(
			'name'        => 'background_image',
			'title'       => '',
			'type'        => 'image',
			'description' => '',
			'meta'        => array(),
			'before'      => '',
			'after'       => '',
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
		),
		// Background repeat
		array(
			'name'        => 'background_repeat',
			'title'       => '',
			'description' => __( 'Background Repeat', 'themify' ),
			'type'        => 'dropdown',
			'meta'        => array(
				array(
					'value' => 'fullcover',
					'name'  => __( 'Fullcover', 'themify' )
				),
				array(
					'value' => 'repeat',
					'name'  => __( 'Repeat', 'themify' )
				),
				array(
					'value' => 'repeat-x',
					'name'  => __( 'Repeat horizontally', 'themify' )
				),
				array(
					'value' => 'repeat-y',
					'name'  => __( 'Repeat vertically', 'themify' )
				),
			),
			'toggle'      => 'solid-toggle',
			'class'     => 'hide-if none',
			'default' => 'fullcover'
		),
		// Header wrap text color
		array(
			'name'        => 'headerwrap_text_color',
			'title'       => __( 'Header Text Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'class'     => 'hide-if none',
		),
		// Header wrap link color
		array(
			'name'        => 'headerwrap_link_color',
			'title'       => __( 'Header Link Color', 'themify' ),
			'description' => '',
			'type'        => 'color',
			'meta'        => array( 'default' => null ),
			'class'     => 'hide-if none',
		),
		// Exclude Footer Widgets
		array(
			'name' 		=> 'exclude_footer_widgets',
			'title'		=> __('Exclude Footer Widgets', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Exclude Footer
		array(
			'name' 		=> 'exclude_footer',
			'title'		=> __('Exclude Footer', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
	);
}
/**
 * Page Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.0
 */
function themify_theme_page_meta_box( $args = array() ){
	extract( $args );
	return array(
		// Page Layout
		array(
			'name' 		=> 'page_layout',
			'title'		=> __('Sidebar Option', 'themify'),
			'description'	=> '',
			'type'		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array('value' => 'default', 'img' => 'images/layout-icons/default.png', 'selected' => true, 'title' => __('Default', 'themify')),
				array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
				array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
				array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
			),
			'default' => 'default'
		),
		// Content Width
		array(
			'name'=> 'content_width',
			'title' => __('Content Width', 'themify'),
			'description' => 'Select "Fullwidth" if the page is to be built with the Builder without the sidebar (it will make the Builder content fullwidth).',
			'type' => 'layout',
			'show_title' => true,
			'meta' => array(
				array(
					'value' => 'default_width',
					'img' => 'themify/img/default.png',
					'selected' => true,
					'title' => __( 'Default', 'themify' )
				),
				array(
					'value' => 'full_width',
					'img' => 'themify/img/fullwidth.png',
					'title' => __( 'Fullwidth', 'themify' )
				)
			),
			'default' => 'default_width'
		),
		// Hide page title
		array(
			'name' 		=> 'hide_page_title',
			'title'		=> __('Hide Page Title', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Custom menu
		array(
			'name'        => 'custom_menu',
			'title'       => __( 'Custom Menu', 'themify' ),
			'description' => '',
			'type'        => 'dropdown',
			// extracted from $args
			'meta'        => $args['nav_menus'],
		),
	);
}

/**
 * Query Posts Options
 * @param array $args
 * @return array
 * @since 1.0.0
 */
function themify_theme_query_post_meta_box($args = array()) {
	extract( $args );
	return array(
		// Notice
		array(
			'name' => '_query_posts_notice',
			'title' => '',
			'description' => '',
			'type' => 'separator',
			'meta' => array(
				'html' => '<div class="themify-info-link">' . sprintf( __( '<a href="%s">Query Posts</a> allows you to query WordPress posts from any category on the page. To use it, select a Query Category.', 'themify' ), 'https://themify.me/docs/query-posts' ) . '</div>'
			),
		),
		// Query Category
		array(
			'name' 		=> 'query_category',
			'title'		=> __('Query Category', 'themify'),
			'description'	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
			'type'		=> 'query_category',
			'meta'		=> array()
		),
		// Query All Post Types
		array(
			'name' => 'query_all_post_types',
			'type' => 'dropdown',
			'title' => __( 'Query All Post Types', 'themify'),
			'meta' =>array(
				array(
				'value' => '',
				'name' => '',
				),
				array(
				'value' => 'yes',
				'name' => 'Yes',
				),
				array(
				'value' => 'no',
				'name' => 'No',
				),
			)
		),
		// Descending or Ascending Order for Posts
		array(
			'name' 		=> 'order',
			'title'		=> __('Order', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
				array('name' => __('Ascending', 'themify'), 'value' => 'asc')
			),
			'default' => 'desc'
		),
		// Criteria to Order By
		array(
			'name' 		=> 'orderby',
			'title'		=> __('Order By', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
				array('name' => __('Random', 'themify'), 'value' => 'rand'),
				array('name' => __('Author', 'themify'), 'value' => 'author'),
				array('name' => __('Post Title', 'themify'), 'value' => 'title'),
				array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
				array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
				array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
				array('name' => __('Post ID', 'themify'), 'value' => 'ID'),
				array('name' => __( 'Custom Field String', 'themify' ), 'value' => 'meta_value'),
				array('name' => __( 'Custom Field Numeric', 'themify' ), 'value' => 'meta_value_num')
			),
			'default' => 'date',
			'hide' => 'date|rand|author|title|comment_count|modified|name|ID field-meta-key'
		),
		array(
			'name'			=> 'meta_key',
			'title'			=> __( 'Custom Field Key', 'themify' ),
			'description'	=> '',
			'type'			=> 'textbox',
			'meta'			=> array('size' => 'medium'),
			'class'			=> 'field-meta-key'
		),
		// Post Layout
		array(
			'name' 		=> 'layout',
			'title'		=> __('Query Post Layout', 'themify'),
			'description'	=> '',
			'type'		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array(
					'value' => 'list-post',
					'img' => 'images/layout-icons/list-post.png',
					'selected' => true
				),
				array(
					'value' => 'grid4',
					'img'   => 'images/layout-icons/grid4.png',
					'title' => __( 'Grid 4', 'themify' )
				),
				array(
					'value' => 'grid3',
					'img'   => 'images/layout-icons/grid3.png',
					'title' => __( 'Grid 3', 'themify' )
				),
				array(
					'value' => 'grid2',
					'img'   => 'images/layout-icons/grid2.png',
					'title' => __( 'Grid 2', 'themify' )
				),
				array(
					'value' => 'list-large-image',
					'img'   => 'images/layout-icons/list-large-image.png',
					'title' => __( 'List Large Image', 'themify' )
				),
				array(
					'value' => 'list-thumb-image',
					'img'   => 'images/layout-icons/list-thumb-image.png',
					'title' => __( 'List Thumb Image', 'themify' )
				),
				array(
					'value' => 'grid2-thumb',
					'img'   => 'images/layout-icons/grid2-thumb.png',
					'title' => __( 'Grid 2 Thumb', 'themify' )
				)
			),
			'default' => 'list-post'
		),
                array(
			'name' 		=> 'post_disable_masonry',
			'title'		=> __( 'Post Masonry', 'themify' ),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array(
					'value' => '',
					'name' => '',
					'selected' => true,
				),
				array(
					'value' => 'yes',
					'name' => __( 'Enable', 'themify' ),
				),
				array(
					'value' => 'no',
					'name' => __( 'Disable', 'themify' ),
				)
			),
			'default' => ''
		),
		// Posts Per Page
		array(
			'name' 		=> 'posts_per_page',
			'title'		=> __('Posts Per Page', 'themify'),
			'description'	=> '',
			'type'		=> 'textbox',
			'meta'		=> array('size' => 'small')
		),
		// Display Content
		array(
			'name' 		=> 'display_content',
			'title'		=> __('Display Content', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Full Content', 'themify'),'value'=>'content'),
				array('name' => __('Excerpt', 'themify'),'value'=>'excerpt','selected'=>true),
				array('name' => __('None', 'themify'),'value'=>'none')
			),
			'default' => 'excerpt'
		),
		// Featured Image Size
		array(
			'name'	=>	'feature_size_page',
			'title'	=>	__('Image Size', 'themify'),
			'description' => sprintf(__('Image sizes can be set at <a href="%s">Media Settings</a> and <a href="%s" target="_blank">Regenerated</a>', 'themify'), 'options-media.php', 'https://wordpress.org/plugins/regenerate-thumbnails/'),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		),
		// Multi field: Image Dimension
		themify_image_dimensions_field(),
		// Hide Title
		array(
			'name' 		=> 'hide_title',
			'title'		=> __('Hide Post Title', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Title
		array(
			'name' 		=> 'unlink_title',
			'title' 		=> __('Unlink Post Title', 'themify'),
			'description' => __('Unlink post title (it will display the post title without link)', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Date
		array(
			'name' 		=> 'hide_date',
			'title'		=> __('Hide Post Date', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Meta
		themify_multi_meta_field(),
		// Hide Post Image
		array(
			'name' 		=> 'hide_image',
			'title' 		=> __('Hide Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Image
		array(
			'name' 		=> 'unlink_image',
			'title' 		=> __('Unlink Featured Image', 'themify'),
			'description' => __('Display the Featured Image without link', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Page Navigation Visibility
		array(
			'name' 		=> 'hide_navigation',
			'title'		=> __('Hide Page Navigation', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
	);
}

/**
 * Event Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.7
 */
function themify_theme_query_event_meta_box($args = array()){
	extract( $args );
	return array(
		// Query Category
		array(
			'name' 		=> 'event_query_category',
			'title'		=> __('Event Category', 'themify'),
			'description'	=> __('Select a event category or enter multiple event category IDs (eg. 2,5,6). Enter 0 to display all event categories.', 'themify'),
			'type'		=> 'query_category',
			'meta'		=> array('taxonomy' => 'event-category')
		),
		// Descending or Ascending Order for Events
		array(
			'name' 		=> 'event_order',
			'title'		=> __('Order', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
				array('name' => __('Ascending', 'themify'), 'value' => 'asc')
			),
			'default' => 'desc'
		),
		// Criteria to Order By
		array(
			'name' 		=> 'event_orderby',
			'title'		=> __('Order By', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Event Date', 'themify'), 'value' => 'meta_value', 'selected' => true),
				array('name' => __('Date', 'themify'), 'value' => 'content'),
				array('name' => __('Random', 'themify'), 'value' => 'rand'),
				array('name' => __('Author', 'themify'), 'value' => 'author'),
				array('name' => __('Post Title', 'themify'), 'value' => 'title'),
				array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
				array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
				array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
				array('name' => __('Post ID', 'themify'), 'value' => 'ID'),
				array('name' => __( 'Custom Field String', 'themify' ), 'value' => 'meta_value'),
				array('name' => __( 'Custom Field Numeric', 'themify' ), 'value' => 'meta_value_num')
			),
			'default' => 'meta_value',
			'hide' => 'date|rand|author|title|comment_count|modified|name|ID field-event-meta-key'
		),
		array(
			'name'			=> 'event_meta_key',
			'title'			=> __( 'Custom Field Key', 'themify' ),
			'description'	=> '',
			'type'			=> 'textbox',
			'meta'			=> array('size' => 'medium'),
			'class'			=> 'field-event-meta-key'
		),
		// Post Layout
		array(
			'name' 		=> 'event_layout',
			'title'		=> __('Event Layout', 'themify'),
			'description'	=> '',
			'type'		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array(
					'value' => 'list-post',
					'img' => 'images/layout-icons/list-post.png',
					'selected' => true
				),
				array(
					'value' => 'grid4',
					'img'   => 'images/layout-icons/grid4.png',
					'title' => __( 'Grid 4', 'themify' )
				),
				array(
					'value' => 'grid3',
					'img'   => 'images/layout-icons/grid3.png',
					'title' => __( 'Grid 3', 'themify' )
				),
				array(
					'value' => 'grid2',
					'img'   => 'images/layout-icons/grid2.png',
					'title' => __( 'Grid 2', 'themify' )
				),
				array(
					'value' => 'list-large-image',
					'img'   => 'images/layout-icons/list-large-image.png',
					'title' => __( 'List Large Image', 'themify' )
				),
				array(
					'value' => 'list-thumb-image',
					'img'   => 'images/layout-icons/list-thumb-image.png',
					'title' => __( 'List Thumb Image', 'themify' )
				),
				array(
					'value' => 'grid2-thumb',
					'img'   => 'images/layout-icons/grid2-thumb.png',
					'title' => __( 'Grid 2 Thumb', 'themify' )
				)
			),
			'default' => 'list-post'
		),
		// Display Content
		array(
			'name' 		=> 'event_hide_past_events',
			'title'		=> __('Display Events', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('All Events', 'themify'),'value'=>'both','selected'=>true),
				array('name' => __('Past Events', 'themify'),'value'=>'no'),
				array('name' => __('Upcoming Events', 'themify'),'value'=>'yes')
			),
			'default' => 'both'
		),
		// Posts Per Page
		array(
			  'name' 		=> 'event_posts_per_page',
			  'title'		=> __('Events Per Page', 'themify'),
			  'description'	=> '',
			  'type'		=> 'textbox',
			  'meta'		=> array('size' => 'small')
			),

		// Display Content
		array(
			'name' 		=> 'event_display_content',
			'title'		=> __('Display Content', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Full Content', 'themify'),'value'=>'content'),
				array('name' => __('Excerpt', 'themify'),'value'=>'excerpt','selected'=>true),
				array('name' => __('None', 'themify'),'value'=>'none')
			),
			'default' => 'excerpt'
		),
		// Featured Image Size
		array(
			'name'	=>	'event_feature_size_page',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		),

		// Multi field: Image Dimension
		array(
			'type' => 'multi',
			'name' => '_event_image_dimensions',
			'title' => __('Image Dimensions', 'themify'),
			'meta' => array(
				'fields' => array(
					// Image Width
					array(
					  'name' 		=> 'event_image_width',
					  'label' => __('width', 'themify'),
					  'description' => '',
					  'type' 		=> 'textbox',
					  'meta'		=> array('size'=>'small')
					),
					// Image Height
					array(
					  'name' 		=> 'event_image_height',
					  'label' => __('height', 'themify'),
					  'description' => '',
					  'type' 		=> 'textbox',
					  'meta'		=> array('size'=>'small')
					),
				),
				'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
				'before' => '',
				'after' => '',
				'separator' => ''
			)
		),
		// Hide Title
		array(
			'name' 		=> 'event_hide_title',
			'title'		=> __('Hide Event Title', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Title
		array(
			'name' 		=> 'event_unlink_title',
			'title' 		=> __('Unlink Event Title', 'themify'),
			'description' => __('Unlink event title (it will display the post title without link)', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Date
		array(
			'name' 		=> 'event_hide_date',
			'title'		=> __('Hide Event Publish Date', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Meta
		array(
			'name' 		=> 'event_hide_meta_all',
			'title' 	=> __('Hide Event Meta', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Image
		array(
			  'name' 		=> 'event_hide_image',
			  'title' 		=> __('Hide Featured Image', 'themify'),
			  'description' => '',
			  'type' 		=> 'dropdown',
			  'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Image
		array(
			'name' 		=> 'event_unlink_image',
			'title' 		=> __('Unlink Featured Image', 'themify'),
			'description' => __('Display the Featured Image Without Link', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Page Navigation Visibility
		array(
			'name' 		=> 'event_hide_navigation',
			'title'		=> __('Hide Page Navigation', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Event Location
		array(
			'name' 		=> 'event_hide_event_location',
			'title'		=> __('Hide Event Location', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Event Date
		array(
			'name' 		=> 'event_hide_event_date',
			'title'		=> __('Hide Event Date', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
	);
}

/**
 * Portfolio Meta Box Options
 * @param array $args
 * @return array
 * @since 1.0.7
 */
function themify_theme_query_portfolio_meta_box($args = array()){
	extract( $args );
	return array(
		// Query Category
		array(
			'name' 		=> 'portfolio_query_category',
			'title'		=> __('Portfolio Category', 'themify'),
			'description'	=> __('Select a portfolio category or enter multiple portfolio category IDs (eg. 2,5,6). Enter 0 to display all portfolio categories.', 'themify'),
			'type'		=> 'query_category',
			'meta'		=> array('taxonomy' => 'portfolio-category')
		),
		// Descending or Ascending Order for Portfolios
		array(
			'name' 		=> 'portfolio_order',
			'title'		=> __('Order', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
				array('name' => __('Ascending', 'themify'), 'value' => 'asc')
			),
			'default' => 'desc'
		),
		// Criteria to Order By
		array(
			'name' 		=> 'portfolio_orderby',
			'title'		=> __('Order By', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
				array('name' => __('Random', 'themify'), 'value' => 'rand'),
				array('name' => __('Author', 'themify'), 'value' => 'author'),
				array('name' => __('Post Title', 'themify'), 'value' => 'title'),
				array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
				array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
				array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
				array('name' => __('Post ID', 'themify'), 'value' => 'ID'),
				array('name' => __( 'Custom Field String', 'themify' ), 'value' => 'meta_value'),
				array('name' => __( 'Custom Field Numeric', 'themify' ), 'value' => 'meta_value_num')
			),
			'default' => 'date',
			'hide' => 'date|rand|author|title|comment_count|modified|name|ID field-portfolio-meta-key'
		),
		array(
			'name'			=> 'portfolio_meta_key',
			'title'			=> __( 'Custom Field Key', 'themify' ),
			'description'	=> '',
			'type'			=> 'textbox',
			'meta'			=> array('size' => 'medium'),
			'class'			=> 'field-portfolio-meta-key'
		),
		// Post Layout
		array(
			'name' 		=> 'portfolio_layout',
			'title'		=> __('Portfolio Layout', 'themify'),
			'description'	=> '',
			'type'		=> 'layout',
			'show_title' => true,
			'meta'		=> array(
				array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'selected' => true),
				array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
				array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
				array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify'))
			),
			'default' => 'list-post'
        ),
        // Post Masonry
		array(
			'name' 		=> 'portfolio_disable_masonry',
			'title'		=> __( 'Post Masonry', 'themify' ),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array(
					'value' => '',
					'name' => '',
					'selected' => true,
				),
				array(
					'value' => 'yes',
					'name' => __( 'Enable', 'themify' ),
				),
				array(
					'value' => 'no',
					'name' => __( 'Disable', 'themify' ),
				)
			),
			'default' => ''
		),
        // Post Gutter
		array(
			'name' 		=> 'portfolio_post_gutter',
			'title'		=> __( 'Post Gutter', 'themify' ),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array(
					'value' => '',
					'name' => '',
					'selected' => true,
				),
				array(
					'value' => 'no-gutter',
					'name' => __( 'No gutter', 'themify' ),
				)
			),
			'default' => ''
		),
		// Posts Per Page
		array(
			'name' 		=> 'portfolio_posts_per_page',
			'title'		=> __('Portfolios Per Page', 'themify'),
			'description'	=> '',
			'type'		=> 'textbox',
			'meta'		=> array('size' => 'small')
		),
		// Display Content
		array(
			'name' 		=> 'portfolio_display_content',
			'title'		=> __('Display Content', 'themify'),
			'description'	=> '',
			'type'		=> 'dropdown',
			'meta'		=> array(
				array('name' => __('Full Content', 'themify'),'value'=>'content'),
				array('name' => __('Excerpt', 'themify'),'value'=>'excerpt','selected'=>true),
				array('name' => __('None', 'themify'),'value'=>'none')
			),
			'default' => 'excerpt'
		),
		// Featured Image Size
		array(
			'name'	=>	'portfolio_feature_size_page',
			'title'	=>	__('Image Size', 'themify'),
			'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
			'type'		 =>	'featimgdropdown',
			'display_callback' => 'themify_is_image_script_disabled'
		),
		// Multi field: Image Dimension
		array(
			'type' => 'multi',
			'name' => '_portfolio_image_dimensions',
			'title' => __('Image Dimensions', 'themify'),
			'meta' => array(
				'fields' => array(
					// Image Width
					array(
						'name' 		=> 'portfolio_image_width',
						'label' => __('width', 'themify'),
						'description' => '',
						'type' 		=> 'textbox',
						'meta'		=> array('size'=>'small')
					),
					// Image Height
					array(
						'name' 		=> 'portfolio_image_height',
						'label' => __('height', 'themify'),
						'description' => '',
						'type' 		=> 'textbox',
						'meta'		=> array('size'=>'small')
					),
				),
				'description' => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
				'before' => '',
				'after' => '',
				'separator' => ''
			)
		),
		// Hide Title
		array(
			'name' 		=> 'portfolio_hide_title',
			'title'		=> __('Hide Portfolio Title', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Title
		array(
			'name' 		=> 'portfolio_unlink_title',
			'title' 	=> __('Unlink Portfolio Title', 'themify'),
			'description' => __('Unlink portfolio title (it will display the post title without link)', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Date
		array(
			'name' 		=> 'portfolio_hide_date',
			'title'		=> __('Hide Portfolio Date', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Meta
		array(
			'name' 		=> 'portfolio_hide_meta_all',
			'title' 	=> __('Hide Portfolio Meta', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Hide Post Image
		array(
			'name' 		=> 'portfolio_hide_image',
			'title' 	=> __('Hide Featured Image', 'themify'),
			'description' => '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Unlink Post Image
		array(
			'name' 		=> 'portfolio_unlink_image',
			'title' 		=> __('Unlink Featured Image', 'themify'),
			'description' => __('Display the Featured Image Without Link', 'themify'),
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		),
		// Page Navigation Visibility
		array(
			'name' 		=> 'portfolio_hide_navigation',
			'title'		=> __('Hide Page Navigation', 'themify'),
			'description'	=> '',
			'type' 		=> 'dropdown',
			'meta'		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			),
			'default' => 'default'
		)
	);
}

/**
 * Default Page Layout Module
 * @param array $data Theme settings data
 * @return string Markup for module.
 * @since 1.0.0
 */
function themify_default_page_layout($data = array()){
	$data = themify_get_data();

	/**
	 * Theme Settings Option Key Prefix
	 * @var string
	 */
	$prefix = 'setting-default_page_';

	/**
	 * Sidebar placement options
	 * @var array
	 */
	$sidebar_location_options = array(
		array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
		array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
		array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'))
	);

	/**
	 * Tertiary options <blank>|yes|no
	 * @var array
	 */
	$default_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Yes', 'themify'), 'value' => 'yes'),
		array('name' => __('No', 'themify'), 'value' => 'no')
	);

	/**
	 * Module markup
	 * @var string
	 */
	$output = '';

	/**
	 * Page sidebar placement
	 */
	$output .= '<p>
					<span class="label">' . __('Page Sidebar Option', 'themify') . '</span>';
	$val = isset( $data[$prefix.'layout'] ) ? $data[$prefix.'layout'] : '';
	foreach ( $sidebar_location_options as $option ) {
		if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) {
			$val = $option['value'];
		}
		if ( $val == $option['value'] ) {
			$class = "selected";
		} else {
			$class = "";
		}
		$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';
	}
	$output .= '<input type="hidden" name="'.$prefix.'layout" class="val" value="'.$val.'" /></p>';

	/**
	 * Hide Title in All Pages
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Title in All Pages', 'themify') . '</span>
					<select name="setting-hide_page_title">'.
						themify_options_module($default_options, 'setting-hide_page_title') . '
					</select>
				</p>';

    /**
     * Hide Feauted images in All Pages
     */
    $output .= '<p>
                    <span class="label">' . __('Hide Featured Image', 'themify') . '</span>
                    <select name="setting-hide_page_image">' .
                        themify_options_module($default_options, 'setting-hide_page_image') . '
                    </select>
                </p>';
	/**
	 * Featured Image dimensions
	 */
	$output .= '<p>
				<span class="label">' . __('Image Size', 'themify') . '</span>
				<input type="text" class="width2" name="setting-page_featured_image_width" value="' . themify_get( 'setting-page_featured_image_width' ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>
				<input type="text" class="width2 show_if_enabled_img_php" name="setting-page_featured_image_height" value="' . themify_get( 'setting-page_featured_image_height' ) . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
				<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
			</p>';

	/**
	 * Page Comments
	 */
	$pre = 'setting-comments_pages';
	$output .= '<p><span class="label">' . __('Page Comments', 'themify') . '</span><label for="'.$pre.'"><input type="checkbox" id="'.$pre.'" name="'.$pre.'" ' . checked( themify_get( $pre ), 'on', false ) . ' /> ' . __('Disable comments in all Pages', 'themify') . '</label></p>';

	return $output;
}