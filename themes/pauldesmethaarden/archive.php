<?php get_header(); ?>

    <div id="primary" class="content-area">
        <main id="content" class="site-content main_content">
            <div class="container">
                <div class="inner-content">
					<?php
					get_template_part( "partials/product", "categories" );
					if ( have_posts() ) {
						?>
                        <div class="list-items product-list" data-load-more-container>
							<?php
							while ( have_posts() ) {
								the_post();
								get_template_part( 'partials/content', PRODUCT_TYPE );
							}
							?>
                        </div>
						<?php
						global $wp_query;
						$queried_object = get_queried_object();
						$term_id        = $queried_object->term_id;
						if ( $wp_query->max_num_pages > 1 ):
							?>
                            <div class="list-load-more">
								<?php
								if ( ! $term_id ) {
									$term_id = 0;
								}
								?>
                                <a class="load-more-btn btn_right_arrow" href="#"
                                   data-category="<?php echo $term_id; ?>" data-post-type="<?php echo PRODUCT_TYPE; ?>"
                                   data-current="2"
                                   data-post-per-page="9"><span><?php echo __( 'MEER LADEN', 'paul' ); ?></span></a>
                            </div>
						<?php
						endif;
					} else {
						?>
                        <p class="not-found"><?php _e( "Niet gevonden", "paul" ); ?></p>
						<?php
					}
					?>
                </div>
            </div>
			<?php
			if ( $content = get_field( 'content_section', $queried_object ) ):;
				$bg_color = get_field( 'content_bg_color', $queried_object );
				if ( ! empty( $bg_color ) ) {
					$bg_color = 'style="background-color:' . $bg_color . '"';
				}
				?>
                <div class="content-section" <?= $bg_color ?>>
                    <div class="container"><?= $content ?></div>
                </div>
			<?php endif; ?>
        </main>
    </div>

<?php get_footer(); ?>