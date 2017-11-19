<?php
// Register Custom Post Type Bike
// Post Type Key: bike
function create_bike_cpt() {

	$labels = array(
		'name' => __( 'Bikes', 'Post Type General Name', 'bikes-domain' ),
		'singular_name' => __( 'Bike', 'Post Type Singular Name', 'bikes-domain' ),
		'menu_name' => __( 'Bikes', 'bikes-domain' ),
		'name_admin_bar' => __( 'Bike', 'bikes-domain' ),
		'archives' => __( 'Bike Archives', 'bikes-domain' ),
		'attributes' => __( 'Bike Attributes', 'bikes-domain' ),
		'parent_item_colon' => __( 'Parent Bike:', 'bikes-domain' ),
		'all_items' => __( 'All Bikes', 'bikes-domain' ),
		'add_new_item' => __( 'Add New Bike', 'bikes-domain' ),
		'add_new' => __( 'Add New', 'bikes-domain' ),
		'new_item' => __( 'New Bike', 'bikes-domain' ),
		'edit_item' => __( 'Edit Bike', 'bikes-domain' ),
		'update_item' => __( 'Update Bike', 'bikes-domain' ),
		'view_item' => __( 'View Bike', 'bikes-domain' ),
		'view_items' => __( 'View Bikes', 'bikes-domain' ),
		'search_items' => __( 'Search Bike', 'bikes-domain' ),
		'not_found' => __( 'Not found', 'bikes-domain' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'bikes-domain' ),
		'featured_image' => __( 'Featured Image', 'bikes-domain' ),
		'set_featured_image' => __( 'Set featured image', 'bikes-domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'bikes-domain' ),
		'use_featured_image' => __( 'Use as featured image', 'bikes-domain' ),
		'insert_into_item' => __( 'Insert into Bike', 'bikes-domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Bike', 'bikes-domain' ),
		'items_list' => __( 'Bikes list', 'bikes-domain' ),
		'items_list_navigation' => __( 'Bikes list navigation', 'bikes-domain' ),
		'filter_items_list' => __( 'Filter Bikes list', 'bikes-domain' ),
	);
	$args = array(
		'label' => __( 'Bike', 'bikes-domain' ),
		'description' => __( 'Bikes', 'bikes-domain' ),
		'labels' => $labels,
		'menu_icon' => '',
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments', 'page-attributes', ),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'bike', $args );

}
add_action( 'init', 'create_bike_cpt', 0 );

/* this filter removes the wyswyg editor and leaves the text editor */
add_filter(‘user_can_richedit’, ‘disable_wyswyg_for_pageblocks’);
function disable_wyswyg_for_pageblocks( $default ){
  if( get_post_type() === ‘bike’) return false;
  return $default;
}

class bikespecsMetabox {
	private $screen = array(
		'bike',
	);
	private $meta_fields = array(
		array(
			'label' => 'Bike Specs',
			'id' => 'bikespecs_31070',
			'type' => 'textarea',
		),
	);
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}
	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'bikespecs',
				__( 'Bike Specs', 'bikes-domain' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'advanced',
				'default'
			);
		}
	}
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'bikespecs_data', 'bikespecs_nonce' );
		$this->field_generator( $post );
	}
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				$meta_value = $meta_field['default']; }
			switch ( $meta_field['type'] ) {
				case 'textarea':
					$input = sprintf(
						'<textarea style="width: 100%%" id="%s" name="%s" rows="5">%s</textarea>',
						$meta_field['id'],
						$meta_field['id'],
						$meta_value
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}
	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}
	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['bikespecs_nonce'] ) )
			return $post_id;
		$nonce = $_POST['bikespecs_nonce'];
		if ( !wp_verify_nonce( $nonce, 'bikespecs_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], $_POST[ $meta_field['id'] ] );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}
if (class_exists('bikespecsMetabox')) {
	new bikespecsMetabox;
};
// Register Taxonomy bike type
// Taxonomy Key: biketype
function create_biketype_tax() {

	$labels = array(
		'name'              => _x( 'bike types', 'taxonomy general name', 'bikes-domain' ),
		'singular_name'     => _x( 'bike type', 'taxonomy singular name', 'bikes-domain' ),
		'search_items'      => __( 'Search bike types', 'bikes-domain' ),
		'all_items'         => __( 'All bike types', 'bikes-domain' ),
		'parent_item'       => __( 'Parent bike type', 'bikes-domain' ),
		'parent_item_colon' => __( 'Parent bike type:', 'bikes-domain' ),
		'edit_item'         => __( 'Edit bike type', 'bikes-domain' ),
		'update_item'       => __( 'Update bike type', 'bikes-domain' ),
		'add_new_item'      => __( 'Add New bike type', 'bikes-domain' ),
		'new_item_name'     => __( 'New bike type Name', 'bikes-domain' ),
		'menu_name'         => __( 'bike type', 'bikes-domain' ),
	);
	$args = array(
		'labels' => $labels,
		'description' => __( 'Bike Type', 'bikes-domain' ),
		'hierarchical' => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_rest' => false,
		'show_tagcloud' => true,
		'show_in_quick_edit' => true,
		'show_admin_column' => false,
	);
	register_taxonomy( 'biketype', array('bike', ), $args );

}
add_action( 'init', 'create_biketype_tax' );

/**
 * Display a custom taxonomy dropdown in admin
 * @author Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_action('restrict_manage_posts', 'tsm_filter_post_type_by_taxonomy');
function tsm_filter_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'bike'; // change to your post type
	$taxonomy  = 'biketype'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => __("Show All {$info_taxonomy->label}"),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => true,
		));
	};
}
/**
 * Filter posts by taxonomy in admin
 * @author  Mike Hemberger
 * @link http://thestizmedia.com/custom-post-type-filter-admin-custom-taxonomy/
 */
add_filter('parse_query', 'tsm_convert_id_to_term_in_query');
function tsm_convert_id_to_term_in_query($query) {
	global $pagenow;
	$post_type = 'bike'; // change to your post type
	$taxonomy  = 'biketype'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}