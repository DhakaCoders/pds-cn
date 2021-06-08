<?php
function twc_load_more_scripts() {
    global $wp_query;

    wp_register_script( 'twc-load-more', THEME_URL . '/assets/js/load-more.js', array('jquery') );

    wp_localize_script( 'twc-load-more', 'load_more_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php',
        'posts' => json_encode( $wp_query->query_vars ),
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ) );

    wp_enqueue_script( 'twc-load-more' );
}

add_action( 'wp_enqueue_scripts', 'twc_load_more_scripts' );

add_action('wp_ajax_nopriv_load_more_action', 'twc_load_more');
add_action('wp_ajax_load_more_action', 'twc_load_more');

function twc_load_more()
{
    $post_type = (isset($_POST["data"]["post_type"])) ? $_POST["data"]["post_type"] : 'post';
    $posts_per_page = (isset($_POST["data"]["posts_per_page"])) ? $_POST["data"]["posts_per_page"] : 2;
    $paged = (isset($_POST["data"]["current_page"])) ? $_POST["data"]["current_page"] : 1;
    $category = (isset($_POST["data"]["category"])) ? $_POST["data"]["category"] : 1;
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => 'menu_order',
        'order' => 'asc',
        'post_status' => 'publish'
    );

    if($_POST["data"]["category"] > 0){
        $args['tax_query'] = array(
            array(
                'taxonomy' => PRODUCT_CATEGORY,
                'field'    => 'term_id',
                'terms'    => array(intval($category)),
                'operator' => 'IN',
            ),
        );
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            get_template_part('partials/content', PRODUCT_TYPE);
        }
    }

    wp_reset_postdata();

    $args['paged'] = (isset($_POST["data"]["current_page"])) ? $_POST["data"]["current_page"] + 1 : 1;

    $query = new WP_Query($args);
    $list = $query->get_posts();
    if (empty($list)) {
        echo '<div class="next-no-post"></div>';
    }

    wp_die();
}