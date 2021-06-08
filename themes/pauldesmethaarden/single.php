<?php get_header(); ?>

    <div id="primary" class="content-area">
        <main id="content" class="site-content main_content">

            <div class="container">

                <div class="inner-content">
					<?php the_content(); ?>

					<?php
					$related = get_posts(
						array(
							'post_type'    => 'post',
							'numberposts'  => 9,
							'post_status'  => array( 'publish', 'private' ),
							'post__not_in' => array( get_the_ID() )
						)
					);

					if ( $related ) {
						?>
                        <div class="related-products product-list post-list">
                            <h2><?php echo __( 'Onze overige blog items', 'paul' ); ?></h2>
                            <div class="related-slider custom-slick-arrow">
                                <div class="related-slider-inner">
									<?php
									foreach ( $related as $post ) {
										setup_postdata( $post );
										get_template_part( 'partials/content', 'post' );
									}
									wp_reset_postdata();
									?>
                                </div>
                            </div>
                        </div>
						<?php
					}
					?>
                </div>

            </div>

        </main>
    </div>

<?php get_footer(); ?>