<?php
/**
 * Template name: Promoties
 */
?>

<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="content" class="site-content main_content">
        <div class="container">
            <div class="inner-content">
                <div class="the-content"><?php the_content();?></div>
				<?php
				$promotions = get_posts( array(
					'post_type'  => 'product',
					'posts_per_page' => -1,
					'meta_query' => array(
						array(
							'key'     => 'product_discount_price',
							'value'   => '',
							'compare' => '!='
						)
					)
				) );
				if ( ! empty( $promotions ) ) {
					?>
                    <div class="list-items product-list" data-load-more-container>
						<?php
						foreach ( $promotions as $post ) {
							setup_postdata( $post );
							get_template_part( 'partials/content', PRODUCT_TYPE );
						}
						?>
                    </div>
					<?php
					wp_reset_postdata();
				} else {
					?>
                    <p class="not-found"><?php _e( "Niet gevonden", "paul" ); ?></p>
					<?php
				}
				?>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
