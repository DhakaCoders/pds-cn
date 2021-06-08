<?php
define( 'VC_ICON', THEME_URL . '/assets/images/vc-icon.png' );
define( 'VC_CATEGORY', __( 'PAULDESMET Elements', 'paul' ) );

/*
 * TWC Partner Slider
 */

vc_map( array(
	"name"     => __( "Partner Slider", "paul" ),
	"base"     => "twc_partner_slider",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			'type'        => 'attach_images',
			'heading'     => __( 'Images', 'paul' ),
			'param_name'  => 'images',
			'value'       => '',
			'description' => __( 'Select images from media library.', 'js_composer' ),
			'dependency'  => array(
				'element' => 'source',
				'value'   => 'media_library',
			),
		)
	)
) );

add_shortcode( "twc_partner_slider", "twc_partner_slider_shortcode" );
function twc_partner_slider_shortcode( $attr ) {
	extract( shortcode_atts( array(
		'images' => ''
	), $attr ) );

	$html = '';

	$image_ids = explode( ',', $images );

	if ( count( $image_ids ) > 0 ) {
		$html .= '<div class="twc-partner-slider"><div class="container">';
		$html .= '<div class="twc-partner-slider-inner" data-slick=\'{
					"slidesToShow": 6, 
					"slidesToScroll": 1, 
					"arrows": true, 
					"infinite": true,
                    "autoplay": "true",
                    "autoplaySpeed": 2000,
                    "responsive": [
                             {
                             "breakpoint": 780,
                             "settings": {"slidesToShow": 4}
                             },
                             {
                             "breakpoint": 480,
                             "settings": {"slidesToShow": 2, "slidesToScroll" : 2}
                     }]}\'>';
		foreach ( $image_ids as $image ) {
			$img_src = wp_get_attachment_image_src( $image, 'full' )[0];
			if ( $img_src ) {
				$html .= '<div class="twc-partner-item"><span style="background-image: url(' . $img_src . ')"><img src="' . $img_src . '" title="' . get_the_title( $image ) . '" /></span></div>';
			}
		}
		$html .= '</div>';
		$html .= '</div></div>';
	}

	return $html;
}

/*
 * TWC Product Categories
 */

vc_map( array(
	"name"     => __( "Product Categorieën", "paul" ),
	"base"     => "twc_product_categories",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			'type'       => 'param_group',
			'value'      => '',
			"heading"    => __( "Extra items", "paul" ),
			'param_name' => 'category_items',
			'params'     => array(
				array(
					"type"        => "autocomplete",
					"heading"     => __( "Product Categorieën", "paul" ),
					"param_name"  => 'ids',
					"settings"    => array(
						'multiple'      => false,
						'sortable'      => true,
						'unique_values' => true,
						'auto_focus'    => true,
						'min_length'    => 1,
						'groups'        => true,
						'delay'         => 500
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Button', 'paul' ),
					'param_name' => 'button'
				)
			)
		),
		array(
			'type'       => 'param_group',
			'value'      => '',
			"heading"    => __( "Extra items", "paul" ),
			'param_name' => 'extra_items',
			'params'     => array(
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Heading', 'paul' ),
					'param_name'  => 'heading',
					'admin_label' => true
				),
				array(
					'type'       => 'vc_link',
					'heading'    => __( 'URL', 'paul' ),
					'param_name' => 'url'
				),
				array(
					'type'       => 'textarea',
					'heading'    => __( 'Beschrijving', 'paul' ),
					'param_name' => 'description'
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Afbeelding', 'paul' ),
					'param_name' => 'main_image'
				),
				array(
					'type'       => 'attach_image',
					'heading'    => __( 'Sub afbeelding', 'paul' ),
					'param_name' => 'sub_image'
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Button', 'paul' ),
					'param_name' => 'button'
				)
			),
			'callbacks'  => array(
				'after_add' => 'callBackFunction',
			),
		)
	)
) );

add_filter( 'vc_autocomplete_twc_product_categories_category_items_ids_callback', "twcProductCategoriesIdsAutocompleteSuggester", 10, 1 ); // Get suggestion(find). Must return an array
add_filter( 'vc_autocomplete_twc_product_categories_category_items_ids_render', "listIdsAutocompleteRender", 10, 1 ); // Render exact item. Must return an array (label,value)

add_shortcode( "twc_product_categories", "twc_product_categories_shortcode" );

function twc_product_categories_item_layout( $is_even, $cat_name, $description, $link, $main_image, $sub_image, $button ) {
	$html = '';
	if ( ! $button ) {
		$button = __( 'LEES MEER', 'paul' );
	}
	$html .= '<div class="category-item"><div class="inner container">';
	if ( $is_even ) {
		//Layout for even
		$html .= '<div class="category-item-info">';
		$html .= '<h3>' . $cat_name . '</h3>';
		$html .= '<div class="category-description">' . $description . '</div>';
		$html .= '<div class="category-btn"><a href="' . $link . '" class="btn_right_arrow" title="' . $button . '">' . $button . '</a></div>';
		$html .= '</div>';

		$html .= '<div class="category-item-images">';
		if ( $main_image ) {
			$html .= '<div class="category-main-image">';
			$html .= '<div class="category-main-img-inner" style="background-image: url(' . $main_image . ')">';
			$html .= '<img src="' . $main_image . '" alt="' . $cat_name . '" />';
			$html .= '</div>';
			$html .= '</div>';
		}
		if ( $sub_image ) {
			$html .= '<div class="category-sub-image">';
			$html .= '<div class="category-sub-img-inner" style="background-image: url(' . $sub_image . ')">';
			$html .= '<img src="' . $sub_image . '" alt="' . $cat_name . '" />';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
	} else {
		//Layout for odd
		$html .= '<div class="category-item-images">';
		if ( $main_image ) {
			$html .= '<div class="category-main-image">';
			$html .= '<div class="category-main-img-inner" style="background-image: url(' . $main_image . ')">';
			$html .= '<img src="' . $main_image . '" alt="' . $cat_name . '" />';
			$html .= '</div>';
			$html .= '</div>';
		}
		if ( $sub_image ) {
			$html .= '<div class="category-sub-image">';
			$html .= '<div class="category-sub-img-inner" style="background-image: url(' . $sub_image . ')">';
			$html .= '<img src="' . $sub_image . '" alt="' . $cat_name . '" />';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';

		$html .= '<div class="category-item-info">';
		$html .= '<h3>' . $cat_name . '</h3>';
		$html .= '<div class="category-description">' . $description . '</div>';
		$html .= '<div class="category-btn"><a href="' . $link . '" class="btn_right_arrow" title="' . $button . '">' . $button . '</a></div>';
		$html .= '</div>';
	}
	$html .= '</div></div>';

	return $html;
}

function twc_product_categories_shortcode( $attrs ) {
	$category_items = vc_param_group_parse_atts( $attrs['category_items'] );
	$extra_items    = vc_param_group_parse_atts( $attrs['extra_items'] );
	$cat_html       = '';
	$extra_html     = '';
	$i              = 1;

	$html = '';
	$html .= '<div class="twc-product-categories categories-items">';


	// from cat multi select
	if ( $category_items ) {
		foreach ( $category_items as $category_item ) {
			$category    = get_term( $category_item['ids'], PRODUCT_CATEGORY );
			$main_image  = get_field( 'category_main_image', $category );
			$sub_image   = get_field( 'category_sub_image', $category );
			$description = term_description( $category->term_id );
			$link        = get_term_link( $category, PRODUCT_CATEGORY );
			if ( $category ) {
				$cat_html .= twc_product_categories_item_layout(
					$i % 2 == 0,
					$category->name,
					$description,
					$link,
					$main_image,
					$sub_image,
					$category_item['button']
				);
			}

			$i ++;
		}
	}

	// from extra items
	if ( $extra_items ) {
		foreach ( $extra_items as $item ) {
			$href        = vc_build_link( $item['url'] );
			$name        = $item['heading'];
			$link        = $href['url'];
			$main_image  = wp_get_attachment_image_src( $item['main_image'], 'large' )[0];
			$sub_image   = wp_get_attachment_image_src( $item['sub_image'], 'large' )[0];
			$description = $item['description'];
			$extra_html  .= twc_product_categories_item_layout(
				$i % 2 == 0,
				$name,
				$description,
				$link,
				$main_image,
				$sub_image,
				$item['button']
			);

			$i ++;
		}
	}

	$html .= $cat_html . $extra_html;

	$html .= '</div>';

	return $html;
}

function twcProductCategoriesIdsAutocompleteSuggester( $query ) {
	$result = get_terms( array(
		'taxonomy'   => PRODUCT_CATEGORY,
		'hide_empty' => false,
		'name__like' => $query
	) );
	foreach ( $result as $item ) {
		$temp          = array();
		$temp["value"] = $item->term_id;
		$temp["label"] = $item->name;
		$array_list[]  = $temp;
	}

	return $array_list;
}

function listIdsAutocompleteRender( $query ) {
	$value = trim( $query['value'] );
	if ( ! empty( $value ) ) {
		$p             = get_term( $value );
		$data          = array();
		$data['value'] = (int) $value;
		$data['label'] = $p->name;

		return ! empty( $data ) ? $data : false;
	}

	return false;
}

/*
 * TWC Home Banner
 */

vc_map( array(
	"name"     => __( "Home Banner", "paul" ),
	"base"     => "twc_the_home_banner",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			'type'        => 'dropdown',
			'heading'     => __( 'Banner type', 'paul' ),
			'param_name'  => 'type',
			'value'       => array(
				"Custom"            => "custom",
				"Revolution slider" => "revslider",
			),
			'admin_label' => true,
		),
		array(
			'type'       => 'textarea_html',
			'heading'    => __( 'Inhoud', 'paul' ),
			'param_name' => 'content',
			'holder'     => 'div',
			'dependency' => array(
				'element' => 'type',
				'value'   => 'custom'
			),
		),
		array(
			'type'        => 'attach_image',
			'heading'     => __( 'Afbeeldingen', 'paul' ),
			'param_name'  => 'image',
			'value'       => '',
			'description' => __( 'Selecteer afbeeldingen uit de mediabibliotheek.', 'js_composer' ),
			'dependency'  => array(
				'element' => 'type',
				'value'   => 'custom'
			),
		),
		array(
			'type'       => 'checkbox',
			'heading'    => __( 'Gebruik effect', 'paul' ),
			'param_name' => 'use_effect',
			'value'      => false,
			'dependency' => array(
				'element' => 'type',
				'value'   => 'custom'
			),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Revolution slider alias', 'paul' ),
			'param_name'  => 'rev_alias',
			'description' => 'E.g. home-banner',
			'admin_label' => true,
			'dependency'  => array(
				'element' => 'type',
				'value'   => "revslider"
			),
		),
	)
) );

add_shortcode( "twc_the_home_banner", "twc_the_home_banner_shortcode" );
function twc_the_home_banner_shortcode( $attr, $content ) {
	$image_url = empty( $image )
		? get_field( "default_banner", "option" )
		: wp_get_attachment_image_src( $image, 'full' )[0];
	$content   = empty( $content ) ? '' : $content;
	$type      = ! empty( $attr['type'] ) ? $attr['type'] : 'custom';
	$html      = '';
	if ( $type == "custom" ) {
		$html .= '<div class="home-banner">';

		$html .= '<div class="banner-image" style="background-image: url(' . $image_url . ')">';
		$html .= '<img src="' . $image_url . '" alt="banner-image" class="js-canvas-image">';

		//$use_effect = empty( $use_effect ) ? false : true;
		if ( $attr['use_effect'] ) {
			$html .= '<div id="js-canvas-wrapper" class="canvas-wrapper"></div>';
		}

		$html .= '</div>';

		$html .= '<div class="banner-content">' . $content . '</div>';

		$html .= '<span class="scroll-down">scroll</span>';

		$html .= '</div>';
	} else {
		$alias = $attr['rev_alias'];
		if ( ! empty( $alias ) ) {
			$shortCode = '[rev_slider alias="' . $alias . '"]';
			$html      .= '<div class="home-banner revslider">';
			$html      .= do_shortcode( $shortCode );
			$html      .= '<span class="scroll-down">scroll</span>';
			$html      .= '</div>';
		}
	}

	return $html;
}

/*
 * TWC Instagram Feed
 */

vc_map( array(
	"name"     => __( "Instagram Feed", "paul" ),
	"base"     => "twc_insta_feed",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			'type'       => 'textarea_html',
			'heading'    => __( 'Titel', 'paul' ),
			'param_name' => 'content',
			'holder'     => 'div'
		),
		array(
			'type'       => 'textfield',
			'heading'    => __( 'Instagram shortcode', 'paul' ),
			'param_name' => 'ig_shortcode',
			'holder'     => 'i'
		)
	)
) );

add_shortcode( "twc_insta_feed", "twc_insta_feed_shortcode" );
function twc_insta_feed_shortcode( $attr, $content ) {
	$html = '<div class="insta-feed custom-slick-arrow">';

	$html .= '<div class="insta-title"><div class="container">' . $content . '</div></div>';
	$html .= '<div class="insta-content">' . do_shortcode( '[' . $attr['ig_shortcode'] . ']' ) . '</div>';

	$html .= '</div>';

	return $html;
}

/*
 * TWC Read More Begin
 */

vc_map( array(
	"name"     => __( "Lees Meer Begin", "paul" ),
	"base"     => "twc_read_more_begin",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array()
) );

add_shortcode( "twc_read_more_begin", "twc_read_more_begin_shortcode" );
function twc_read_more_begin_shortcode( $attr ) {
	$html = '<div class="read-more-begin"></div>';

	return $html;
}

/*
 * TWC Read More End
 */

vc_map( array(
	"name"     => __( "Lees Meer End", "paul" ),
	"base"     => "twc_read_more_end",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array()
) );

add_shortcode( "twc_read_more_end", "twc_read_more_end_shortcode" );
function twc_read_more_end_shortcode( $attr ) {
	$html = '<div class="read-more-end">';
	$html .= '<a class="btn_right_arrow_bg_gold read-more-button" href="#">Lees Meer</a>';
	$html .= '</div>';

	return $html;
}

/*
 * TWC Product Slider
 */

vc_map( array(
	"name"     => __( "Product Slider", "paul" ),
	"base"     => "twc_product_slider",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			"type"        => "autocomplete",
			"heading"     => __( "Selecteer Producten", "paul" ),
			"param_name"  => 'ids',
			"settings"    => array(
				'multiple'      => true,
				'sortable'      => true,
				'unique_values' => true,
				'auto_focus'    => true,
				'min_length'    => 1,
				'groups'        => true,
				'delay'         => 500
			),
			'save_always' => true,
			'admin_label' => true
		)
	)
) );

add_filter( 'vc_autocomplete_twc_product_slider_ids_callback', "twcProductSliderIdsAutocompleteSuggester", 10, 1 );
add_filter( 'vc_autocomplete_twc_product_slider_ids_render', "listProductIdsAutocompleteRender", 10, 1 );

add_shortcode( "twc_product_slider", "twc_product_slider_shortcode" );

function twc_product_slider_shortcode( $attrs ) {
	$post_ids = explode( ",", $attrs['ids'] );

	global $post;

	echo '<div class="product-slider product-list">';
	foreach ( $post_ids as $id ) {
		$post = get_post( $id, OBJECT );
		get_template_part( 'partials/content', 'product' );
	}
	wp_reset_postdata();

	echo '</div>';

	return '';
}

function twcProductSliderIdsAutocompleteSuggester( $query ) {
	$result = get_posts( array(
		'post_type'   => 'product',
		'post_status' => 'publish',
		'name__like'  => $query
	) );
	foreach ( $result as $item ) {
		$temp          = array();
		$temp["value"] = $item->ID;
		$temp["label"] = $item->post_title;
		$array_list[]  = $temp;
	}

	return $array_list;
}

function listProductIdsAutocompleteRender( $query ) {
	$value = trim( $query['value'] );
	if ( ! empty( $value ) ) {
		$p             = get_post( $value );
		$data          = array();
		$data['value'] = (int) $value;
		$data['label'] = $p->post_title;

		return ! empty( $data ) ? $data : false;
	}

	return false;
}


/*
 * TWC Blogs
 */

vc_map( array(
	"name"     => __( "Blogs", "paul" ),
	"base"     => "twc_blogs",
	"category" => VC_CATEGORY,
	"icon"     => VC_ICON,
	"params"   => array(
		array(
			"type"        => "autocomplete",
			"heading"     => __( "Selecteer Producten", "paul" ),
			"param_name"  => 'ids',
			"settings"    => array(
				'multiple'      => true,
				'sortable'      => true,
				'unique_values' => true,
				'auto_focus'    => true,
				'min_length'    => 1,
				'groups'        => true,
				'delay'         => 500
			),
			'save_always' => true,
			'admin_label' => true
		)
	)
) );

add_filter( 'vc_autocomplete_twc_blogs_ids_callback', "twcBlogsSliderIdsAutocompleteSuggester", 10, 1 );
add_filter( 'vc_autocomplete_twc_blogs_ids_render', "listBlogsIdsAutocompleteRender", 10, 1 );

add_shortcode( "twc_blogs", "twc_blogs_shortcode" );

function twc_blogs_shortcode( $attrs ) {
	$post_ids = explode( ",", $attrs['ids'] );

	global $post;

	$html = '<div class="product-slider product-list">';
	foreach ( $post_ids as $id ) {
		$post = get_post( $id );
		setup_postdata( $post );
		ob_start();
		get_template_part( 'partials/content', 'post' );
		$html .= ob_get_clean();
	}
	wp_reset_postdata();

	$html .= '</div>';

	return $html;
}

function twcBlogsSliderIdsAutocompleteSuggester( $query ) {
	$result = get_posts( array(
		'post_type'      => 'post',
		'posts_per_page' => - 1,
		'post_status'    => array( 'publish', 'private' ),
		's'              => $query,
	) );
	foreach ( $result as $item ) {
		$temp          = array();
		$temp["value"] = $item->ID;
		$temp["label"] = $item->post_title;
		$array_list[]  = $temp;
	}

	return $array_list;
}

function listBlogsIdsAutocompleteRender( $query ) {
	$value = trim( $query['value'] );
	if ( ! empty( $value ) ) {
		$p             = get_post( $value );
		$data          = array();
		$data['value'] = (int) $value;
		$data['label'] = $p->post_title;

		return ! empty( $data ) ? $data : false;
	}

	return false;
}