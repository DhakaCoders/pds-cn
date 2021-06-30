<?php
define( 'SITE_URL', get_site_url() );
define( 'THEME_URL', get_template_directory_uri() );
define( 'THEME_PATH', get_template_directory() );
define( 'THEME_NAME', get_bloginfo( 'name' ) );

define( 'PRODUCT_TYPE', 'product' );
define( 'PRODUCT_CATEGORY', 'product-categorie' );

include_once( 'inc/ajax-load-more.php' );
include_once( 'inc/Mobile_Detect.php' );

// EXCERPT LENGTH
function custom_excerpt_length( $length ) {
	return 23;
}

add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/*
 * Fix conflict VC Grid and Fastest Cache
 */
add_filter( 'vc_grid_get_grid_data_access', '__return_true' );

/**
 * Theme setup
 */

function twc_theme_setup() {
	add_theme_support( 'title-tag' );

	register_nav_menu( 'main-menu', __( 'Main Menu', 'paul' ) );
	register_nav_menu( 'extra-menu', __( 'Extra Menu', 'paul' ) );

	add_editor_style();
}

add_action( 'after_setup_theme', 'twc_theme_setup' );

/**
 * Disable automatic WordPress plugin updates
 * Disable automatic WordPress theme updates
 * Disable automatic WordPress core updates
 * Disable admin bar on front-end
 */

add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );
add_filter( 'automatic_updater_disabled', '__return_true' );
add_filter( 'show_admin_bar', '__return_false' );

/**
 * Advanced Custom Field options page
 */

if ( function_exists( 'acf_add_options_page' ) ) {
	// Add options page
	acf_add_options_page( array(
		'page_title' => __( 'Algemene opties', 'paul' ),
		'menu_title' => __( 'Algemene opties', 'paul' ),
		'menu_slug'  => 'theme-general-settings',
		'icon_url'   => 'dashicons-sos'
	) );

	// Add sub pages
	acf_add_options_sub_page( array(
		'page_title'  => __( 'Algemeen', 'paul' ),
		'menu_title'  => __( 'Algemeen', 'paul' ),
		'parent_slug' => 'theme-general-settings',
	) );
	acf_add_options_sub_page( array(
		'page_title'  => __( '404 pagina', 'paul' ),
		'menu_title'  => __( '404 pagina', 'paul' ),
		'parent_slug' => 'theme-general-settings',
	) );
	acf_add_options_sub_page( array(
		'page_title'  => __( 'Footer', 'paul' ),
		'menu_title'  => __( 'Footer', 'paul' ),
		'parent_slug' => 'theme-general-settings',
	) );
}

/**
 * Theme custom scripts and stylesheets
 */
function twc_theme_scripts() {
	// Stylesheet
	wp_enqueue_style( 'style-editor', THEME_URL . '/editor-style.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-skeleton', THEME_URL . '/assets/css/skeleton.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-slick', THEME_URL . '/assets/css/slick.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-slick-theme', THEME_URL . '/assets/css/slick-theme.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-nice-select', THEME_URL . '/assets/css/nice-select.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-layout', THEME_URL . '/assets/css/style.css', false, '1.0.0', 'all' );
	wp_enqueue_style( 'style-responsive', THEME_URL . '/assets/css/responsive.css', false, '1.0.0', 'all' );

	// Javascript
	wp_enqueue_script( 'script-slick', THEME_URL . '/assets/js/slick.min.js', array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'script-nice-select', THEME_URL . '/assets/js/jquery.nice-select.min.js', array( 'jquery' ), '1.0.0', false );
	wp_enqueue_script( 'script-theme', THEME_URL . '/assets/js/theme.js', array( 'jquery' ), '1.0.0', false );
}

add_action( 'wp_enqueue_scripts', 'twc_theme_scripts' );

/**
 * Add Visual Composer Elements
 */
function twc_vc_before_init() {
	include_once( 'inc/vc-maps.php' );
}

add_action( 'vc_before_init', 'twc_vc_before_init' );

/**
 * Fix W3C validate for Yoast SEO breadcrumbs
 */
add_filter( 'wpseo_breadcrumb_output', 'twc_wpseo_breadcrumb_output' );
function twc_wpseo_breadcrumb_output( $link_output ) {
	$link_output = preg_replace( array(
		'#<span xmlns:v="http://rdf.data-vocabulary.org/\#">#',
		'#<span typeof="v:Breadcrumb"><a href="(.*?)" .*?' . '>(.*?)</a></span>#',
		'#<span typeof="v:Breadcrumb">(.*?)</span>#',
		'# property=".*?"#',
		'#</span>$#'
	), array(
		'',
		'<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="$1" itemprop="url"><span itemprop="title">$2</span></a></span>',
		'<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><span itemprop="title">$1</span></span>',
		'',
		''
	), $link_output );

	return $link_output;
}

/**
 * Update Gravity form submit button
 */
function twc_gform_submit_button( $button, $form ) {
	return "<button class='btn-submit btn_right_arrow_bg_gold' id='gform_submit_button_{$form['id']}'>{$form['button']['text']}</button>";
}

add_filter( 'gform_submit_button', 'twc_gform_submit_button', 10, 2 );

/**
 * Update Gravity form validation message
 */
function twc_gform_validation_message( $message, $form ) {
	return "<div class='validation_error'>" . get_field( "form_error_message", "option" ) . '</div>';
}

add_filter( 'gform_validation_message', 'twc_gform_validation_message', 10, 2 );

/**
 * Gravity form: add an option to hide label
 * Ref: https://gravitywiz.com/how-to-hide-gravity-form-field-labels-when-using-placeholders/
 */
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

/**
 * Custom page title function
 */
function twc_page_title( $title_tag, $title ) {
	return "<{$title_tag} class='page-title'>{$title}</$title_tag>";
}

/**
 * Show page title function
 */

function twc_show_page_title() {
	$title = '';
	if ( is_404() ) {
		$title = '404';
	} else if ( is_home() || is_category() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );
	} else if ( is_single() && get_post_type() == PRODUCT_TYPE ) {
		$category = twc_get_category( get_the_ID() );
		if ( $category ) {
			$title = $category->name;
		}
	} else if ( is_tax() ) {
		$queried_object = get_queried_object();
		$title          = $queried_object->name;
	} else if ( ! is_front_page() ) {
		$title = get_the_title();
	}
	if ( $title ) {
		$page_title = twc_page_title( "h1", $title );
		echo $page_title;
	}
}

/*
 * Get Item Image
 */

function twc_get_image( $post_id ) {
	$img = wp_get_attachment_image_url( get_post_thumbnail_id( $post_id ), "full" );
	if ( empty( $img ) ) {
		$default = get_field( "default_thumbnail", "option" );
		$img     = $default["url"];
	}

	return $img;
}

/*
 * Get Main Category
 */

function twc_get_category( $post_id ) {
	$terms = wp_get_post_terms( $post_id, PRODUCT_CATEGORY );
	if ( count( $terms ) > 0 ) {
		foreach ( $terms as $term ) {
			if ( $term->parent == 0 ) {
				return $term;
			}
		}
	}

	return '';
}

/*
 * Get Product Price
 */

function twc_get_price( $field_name, $post_id ) {
	$field = get_field_object( $field_name, $post_id );

	return $field['value'] . ' ' . $field['append'];
}

/*
 * Gravity Form Product Population
 */

add_filter( 'gform_field_value_product', 'twc_gform_field_value_product' );
function twc_gform_field_value_product( $value ) {
	return get_the_title();
}

add_filter( 'gform_field_value_product_price', 'twc_gform_field_value_product_price' );
function twc_gform_field_value_product_price( $value ) {
	return get_field( 'product_price' );
}

/**
 * Add body class
 */
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = 'twc-page-loading';
	}

	return $classes;
}

add_filter( 'body_class', 'add_slug_body_class' );

/*
 * Disable push active taxonomy to top
 */
add_filter( 'wp_terms_checklist_args', 'twc_stop_reordering_taxonomies' );
function twc_stop_reordering_taxonomies( $args ) {
	$args['checked_ontop'] = false;

	return $args;
}


/*$user_id = 2;
$username = 'admin';
$pass = 'admin123';
 
$user_data = wp_update_user( array( 'ID' => $user_id, 'user_login' => $username, 'user_pass'=> $pass ) );
 
if ( is_wp_error( $user_data ) ) {
    // There was an error; possibly this user doesn't exist.
    echo 'Error.';
} else {
    // Success!
    echo 'User profile updated.';
}*/