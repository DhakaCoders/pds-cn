<?php get_header(); ?>

    <div id="primary" class="content-area">
        <main id="content" class="site-content main_content">

            <div class="container">

                <div class="inner-content">

					<?php
					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

					$args     = array(
						'posts_per_page'   => get_option( 'posts_per_page' ),
						'paged'            => $paged,
						'suppress_filters' => false
					);
					$wp_query = new WP_Query( $args );
					if ( $wp_query->have_posts() ) :
						?>
                        <div class="list-items product-list blog-list">
							<?php
							while ( $wp_query->have_posts() ) : $wp_query->the_post();
								get_template_part( "partials/content", "post" );
							endwhile;
							?>
                        </div>
						<?php
						get_template_part( "partials/pagination", "" );
						?>
					<?php else: ?>
                        <p class="not-found">
							<?php _e( "Geen berichten gevonden", "paul" ); ?>
                        </p>
					<?php endif; ?>
					<?php wp_reset_postdata(); ?>
                </div>

            </div>

        </main>
    </div>

<?php get_footer(); ?>