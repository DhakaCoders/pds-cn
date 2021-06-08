<?php get_header(); ?>

    <div id="primary" class="content-area">
        <main id="content" class="site-content main_content">

            <div class="container">

                <div class="inner-content">

                    <div class="product-main-info">
						<?php
						$featured_img = twc_get_image( get_the_ID() );
						$gallery      = get_field( 'product_gallery', get_the_ID() );
						?>
                        <div class="product-gallery">
							<?php
							if ( $featured_img || $gallery ) {
								?>
                                <div class="product-gallery-slider">
                                    <div class="product-gallery-inner">
										<?php
										if ( $gallery ) {
											foreach ( $gallery as $gallery_item ) {
												?>
                                                <div class="product-gallery-item">
                                                    <div class="product-gallery-image"
                                                         style="background-image: url('<?php echo $gallery_item['url']; ?>')">
                                                        <img src="<?php echo $gallery_item['url']; ?>"
                                                             alt="<?php echo $gallery_item['title']; ?>"
                                                             title="<?php echo $gallery_item['title']; ?>"/>
                                                    </div>
                                                </div>
												<?php
											}
										} else {
											?>
                                            <div class="product-gallery-item">
                                                <div class="product-gallery-image"
                                                     style="background-image: url('<?php echo $featured_img; ?>')">
                                                    <img src="<?php echo $featured_img; ?>"
                                                         alt="<?php echo get_the_title(); ?>"
                                                         title="<?php echo get_the_title(); ?>"/>
                                                </div>
                                            </div>
											<?php
										}
										?>
                                    </div>
                                </div>
                                <div class="product-thumbs-slider">
                                    <div class="product-thumbs-inner">
										<?php
										if ( $gallery ) {
											foreach ( $gallery as $gallery_item ) {
												?>
                                                <div class="product-thumbs-item">
                                                    <div class="product-thumbs-image"
                                                         style="background-image: url('<?php echo $gallery_item['url']; ?>')">
                                                        <img src="<?php echo $gallery_item['url']; ?>"
                                                             alt="<?php echo $gallery_item['title']; ?>"
                                                             title="<?php echo $gallery_item['title']; ?>"/>
                                                    </div>
                                                </div>
												<?php
											}
										} else {
											?>
                                            <div class="product-thumbs-item">
                                                <div class="product-thumbs-image"
                                                     style="background-image: url('<?php echo $featured_img; ?>')">
                                                    <img src="<?php echo $featured_img; ?>"
                                                         alt="<?php echo get_the_title(); ?>"
                                                         title="<?php echo get_the_title(); ?>"/>
                                                </div>
                                            </div>
											<?php
										}
										?>
                                    </div>
                                </div>
								<?php
							}
							?>
                        </div>
                        <div class="product-info">
							<?php
							echo twc_page_title( "h2", get_the_title() );
							?>
                            <div class="product-excerpt">
								<?php the_excerpt(); ?>
                            </div>
							<?php
							$price          = 'product_price';
							$price_discount = 'product_discount_price';
							?>
                            <div class="product-price">
								<?php if ( $price_discount ): ?>
									<span class="discount">
                                        <?php echo twc_get_price( $price_discount, get_the_ID() ); ?>
                                    </span>
									<span class="line-through">
                                        <?php echo twc_get_price( $price, get_the_ID() ); ?>
                                    </span>
								<?php else : ?>
									<?php echo twc_get_price( $price, get_the_ID() ); ?>
								<?php endif; ?>
                            </div>
                            <div class="product-content">
								<?php the_content(); ?>
                            </div>
                            <div class="product-contact-btn">
                                <a href="#" class="btn_right_arrow product-popup">VRAAG UW GRATIS OFFERTE AAN</a>
                            </div>
                        </div>
                    </div>
					<?php
					$related = get_posts(
						array(
							'post_type'    => PRODUCT_TYPE,
							'category__in' => wp_get_post_categories( get_the_ID() ),
							'numberposts'  => 9,
							'post__not_in' => array( get_the_ID() )
						)
					);

					if ( $related ) {
						?>
                        <div class="related-products product-list">
                            <h2><?php echo __( 'Bekijk ook onze andere artikelen', 'paul' ); ?></h2>
                            <div class="related-slider custom-slick-arrow">
                                <div class="related-slider-inner">
									<?php
									foreach ( $related as $post ) {
										setup_postdata( $post );
										get_template_part( 'partials/content', 'product' );
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