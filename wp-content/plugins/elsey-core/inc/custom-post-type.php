<?php
/**
 * Initialize Custom Post Type - Elsey Theme
 */

function elsey_custom_post_type() {

	$portfolio_slug  = 'lookbook';
	$portfolio_label = 'Lookbook';

	// Register custom post type - Lookbook
	register_post_type('lookbook',
		array(
			'labels'                 => array(
				'name'               => $portfolio_label,
				'singular_name'      => sprintf(esc_html__('%s Post', 'elsey-core' ), $portfolio_label),
				'all_items'          => sprintf(esc_html__('All %s', 'elsey-core' ), $portfolio_label),
				'add_new'            => esc_html__('Add New', 'elsey-core') ,
				'add_new_item'       => sprintf(esc_html__('Add New %s', 'elsey-core' ), $portfolio_label),
				'edit'               => esc_html__('Edit', 'elsey-core') ,
				'edit_item'          => sprintf(esc_html__('Edit %s', 'elsey-core' ), $portfolio_label),
				'new_item'           => sprintf(esc_html__('New %s', 'elsey-core' ), $portfolio_label),
				'view_item'          => sprintf(esc_html__('View %s', 'elsey-core' ), $portfolio_label),
				'search_items'       => sprintf(esc_html__('Search %s', 'elsey-core' ), $portfolio_label),
				'not_found'          => esc_html__('Nothing found in the Database.', 'elsey-core') ,
				'not_found_in_trash' => esc_html__('Nothing found in Trash', 'elsey-core') ,
				'parent_item_colon'  => ''
			),
			'public'                 => true,
			'publicly_queryable'     => true,
			'exclude_from_search'    => false,
			'show_ui'                => true,
			'query_var'              => true,
			'menu_position'          => 10,
			'menu_icon'              => 'dashicons-camera',
			'rewrite'                => array(
				'slug'               => $portfolio_slug,
				'with_front'         => false
			),
			'has_archive'            => true,
			'capability_type'        => 'post',
			'hierarchical'           => true,
			'supports'               => array(
				'title',
				'author',
				'revisions',
				'page-attributes'
			)
		)
	);
	// Registered

	//Faq starts
    $faq_slug = 'faq';
    $faq = __('FAQ', 'elsey-core');

    // Register custom post type - faq
    register_post_type('faq',
        array(
            'labels' => array(
                'name' => $faq,
                'singular_name' => sprintf(esc_html__('%s Post', 'elsey-core' ), $faq),
                'all_items' => sprintf(esc_html__('%s', 'elsey-core' ), $faq),
                'add_new' => esc_html__('Add New', 'elsey-core') ,
                'add_new_item' => sprintf(esc_html__('Add New %s', 'elsey-core' ), $faq),
                'edit' => esc_html__('Edit', 'elsey-core') ,
                'edit_item' => sprintf(esc_html__('Edit %s', 'elsey-core' ), $faq),
                'new_item' => sprintf(esc_html__('New %s', 'elsey-core' ), $faq),
                'view_item' => sprintf(esc_html__('View %s', 'elsey-core' ), $faq),
                'search_items' => sprintf(esc_html__('Search %s', 'elsey-core' ), $faq),
                'not_found' => esc_html__('Nothing found in the Database.', 'elsey-core') ,
                'not_found_in_trash' => esc_html__('Nothing found in Trash', 'elsey-core') ,
                'parent_item_colon' => ''
            ) ,
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            //'menu_position' => 10,
            'show_in_menu' => 'elsey_post_type',
            'menu_icon' => 'dashicons-portfolio',
            'rewrite' => array(
                'slug' => $faq_slug,
                'with_front' => false
            ),
            'has_archive' => true,
            'capability_type' => 'post',
            'hierarchical' => true,
            'supports' => array(
                'title',
                'editor',
                'author',
                'thumbnail',
                'excerpt',
                'trackbacks',
                'custom-fields',
                'comments',
                'revisions',
                'sticky',
                'page-attributes'
            )
        )
    );
    // Registered

	// Testimonials - Start
	$testimonial_slug = 'testimonial';
	$testimonials     = __('Testimonials', 'elsey-core');

	// Register custom post type - Testimonials
	register_post_type('testimonial',
		array(
			'labels' => array(
				'name'               => $testimonials,
				'singular_name'      => sprintf(esc_html__('%s Post', 'elsey-core' ), $testimonials),
				'all_items'          => sprintf(esc_html__('%s', 'elsey-core' ), $testimonials),
				'add_new'            => esc_html__('Add New', 'elsey-core') ,
				'add_new_item'       => sprintf(esc_html__('Add New %s', 'elsey-core' ), $testimonials),
				'edit'               => esc_html__('Edit', 'elsey-core') ,
				'edit_item'          => sprintf(esc_html__('Edit %s', 'elsey-core' ), $testimonials),
				'new_item'           => sprintf(esc_html__('New %s', 'elsey-core' ), $testimonials),
				'view_item'          => sprintf(esc_html__('View %s', 'elsey-core' ), $testimonials),
				'search_items'       => sprintf(esc_html__('Search %s', 'elsey-core' ), $testimonials),
				'not_found'          => esc_html__('Nothing found in the Database.', 'elsey-core') ,
				'not_found_in_trash' => esc_html__('Nothing found in Trash', 'elsey-core') ,
				'parent_item_colon'  => ''
			) ,
			'public'                 => true,
			'publicly_queryable'     => true,
			'exclude_from_search'    => false,
			'show_ui'                => true,
			'query_var'              => true,
			// 'menu_position' => 10,
			'show_in_menu'           => 'elsey_post_type',
			'menu_icon'              => 'dashicons-groups',
			'rewrite'                => array(
				'slug'               => $testimonial_slug,
				'with_front'         => false
			),
			'has_archive'            => true,
			'capability_type'        => 'post',
			'hierarchical'           => true,
			'supports'               => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
				'sticky',
			)
		)
	);
	// Testimonials - End

	// Team - Start
	$team_slug = 'team';
	$teams = __('Teams', 'elsey-core');

	// Register custom post type - Team
	register_post_type('team',
		array(
			'labels'                 => array(
				'name'               => $teams,
				'singular_name'      => sprintf(esc_html__('%s Post', 'elsey-core' ), $teams),
				'all_items'          => sprintf(esc_html__('%s', 'elsey-core' ), $teams),
				'add_new'            => esc_html__('Add New', 'elsey-core') ,
				'add_new_item'       => sprintf(esc_html__('Add New %s', 'elsey-core' ), $teams),
				'edit'               => esc_html__('Edit', 'elsey-core') ,
				'edit_item'          => sprintf(esc_html__('Edit %s', 'elsey-core' ), $teams),
				'new_item'           => sprintf(esc_html__('New %s', 'elsey-core' ), $teams),
				'view_item'          => sprintf(esc_html__('View %s', 'elsey-core' ), $teams),
				'search_items'       => sprintf(esc_html__('Search %s', 'elsey-core' ), $teams),
				'not_found'          => esc_html__('Nothing found in the Database.', 'elsey-core') ,
				'not_found_in_trash' => esc_html__('Nothing found in Trash', 'elsey-core') ,
				'parent_item_colon'  => ''
			) ,
			'public'                 => true,
			'publicly_queryable'     => true,
			'exclude_from_search'    => false,
			'show_ui'                => true,
			'query_var'              => true,
			// 'menu_position'       => 10,
			'show_in_menu'           => 'elsey_post_type',
			'menu_icon'              => 'dashicons-businessman',
			'rewrite'                => array(
				'slug'               => $team_slug,
				'with_front'         => false
			),
			'has_archive'            => true,
			'capability_type'        => 'post',
			'hierarchical'           => true,
			'supports'               => array(
				'title',
				'editor',
				'thumbnail',
				'excerpt',
				'revisions',
				'sticky',
			)
		)
	); // Team - End
}

/* ---------------------------------------------------------------------------
 * Custom Columns - Testimonial
 * --------------------------------------------------------------------------- */
add_filter("manage_edit-testimonial_columns", "elsey_testimonial_edit_columns");
function elsey_testimonial_edit_columns($columns) {
  $new_columns['cb']        = '<input type="checkbox" />';
  $new_columns['title']     = __('Title', 'elsey-core' );
  $new_columns['thumbnail'] = __('Image', 'elsey-core' );
  $new_columns['name']      = __('Client Name', 'elsey-core' );
  $new_columns['date']      = __('Date', 'elsey-core' );
  return $new_columns;
}

add_action('manage_testimonial_posts_custom_column', 'elsey_manage_testimonial_columns', 10, 2);
function elsey_manage_testimonial_columns( $column_name ) {
  global $post;
  switch ($column_name) {
    /* If displaying the 'Image' column. */
    case 'thumbnail':
      echo get_the_post_thumbnail( $post->ID, array( 100, 100) );
    break;

    case "name":
    	$testimonial_options = get_post_meta( get_the_ID(), 'testimonial_options', true );
      echo $testimonial_options['testi_name'];
    break;

    /* Just break out of the switch statement for everything else. */
    default :
      break;
    break;
  }
}

/* ---------------------------------------------------------------------------
 * Custom Columns - Team
 * --------------------------------------------------------------------------- */
add_filter("manage_edit-team_columns", "elsey_team_edit_columns");
function elsey_team_edit_columns($columns) {
  $new_columns['cb']        = '<input type="checkbox" />';
  $new_columns['title']     = __('Title', 'elsey-core' );
  $new_columns['thumbnail'] = __('Image', 'elsey-core' );
  $new_columns['name']      = __('Job Position', 'elsey-core' );
  $new_columns['date']      = __('Date', 'elsey-core' );
  return $new_columns;
}

add_action('manage_team_posts_custom_column', 'elsey_manage_team_columns', 10, 2);
function elsey_manage_team_columns( $column_name ) {
  global $post;
  switch ($column_name) {
    /* If displaying the 'Image' column. */
    case 'thumbnail':
      echo get_the_post_thumbnail( $post->ID, array( 100, 100) );
    break;

    case "name":
    	$team_options = get_post_meta( get_the_ID(), 'team_options', true );
      echo $team_options['team_member_job_position'];
    break;

    /* Just break out of the switch statement for everything else. */
    default :
      break;
    break;
  }
}

// Post Type - Menu
if( ! function_exists( 'elsey_post_type_menu' ) ) {
  function elsey_post_type_menu(){
    if ( current_user_can( 'edit_theme_options' ) ) {
			add_menu_page( __('Company', 'elsey-core'), __('Company', 'elsey-core'), 'edit_theme_options', 'elsey_post_type', 'elsey_welcome_page', 'dashicons-groups', 10 );
    }
  }
}
add_action( 'admin_menu', 'elsey_post_type_menu' );

// After Theme Setup
function elsey_custom_flush_rules() {
	// Enter post type function, so rewrite work within this function
	elsey_custom_post_type();
	// Flush it
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'elsey_custom_flush_rules');
add_action('init', 'elsey_custom_post_type');
