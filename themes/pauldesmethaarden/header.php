<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#be9439">
	<?php wp_head(); ?>
	<?php echo get_field( "script_head", "option" ); ?>

    <div class="twc-loading-layer">
        <img src="<?= THEME_URL ?>/assets/images/three-dots.svg" alt="loading-icon">
    </div>
</head>
<body <?php body_class(); ?>>
<?php echo get_field( "script_body", "option" ); ?>
<header class="main_header">
    <div class="main_header_wrapper">
        <div class="main_header_inner container">
            <div class="logo-block">
				<?php if ( is_front_page() ): ?>
                    <h1 class="hidden"><?php echo get_bloginfo( "name" ); ?></h1>
				<?php endif; ?>
                <a href="<?php echo SITE_URL; ?>" title="<?php echo get_bloginfo( "name" ); ?>" class="logo-img">
                    <img src='<?php echo get_field( "logo", "option" ); ?>'
                         alt="<?php echo get_bloginfo( "name" ) . " - " . get_bloginfo( "description" ) ?>"/>
                </a>
            </div>
            <div class="menu-block">

				<?php wp_nav_menu( array(
					'theme_location'  => 'main-menu',
					'container_class' => 'main-menu-container'
				) ); ?>

				<?php wp_nav_menu( array(
					'theme_location'  => 'extra-menu',
					'container_class' => 'extra-menu-container'
				) ); ?>

            </div>
            <span class="btn_menu"><span></span></span>
        </div>
    </div>
	<?php
	if ( ! is_front_page() ) {
		?>
        <div class="banner-title-breadcrumbs">
			<?php
			$use_effect = false;
			if ( is_home() ) {
				$banner_src = wp_get_attachment_image_url( get_post_thumbnail_id( get_option( 'page_for_posts', true ) ), "full" );
			} else if ( is_single() && get_post_type() == PRODUCT_TYPE ) {
				$category = twc_get_category( get_the_ID() );
				if ( $category ) {
					$banner_src = get_field( 'category_main_image', $category );
					$use_effect = get_field( 'use_effect', $category );
				}
			} else if ( is_tax() ) {
				$queried_object = get_queried_object();
				$banner_src     = get_field( 'banner_image', $queried_object );
				$use_effect     = get_field( 'use_effect', $queried_object );
			} else {
				$banner_src = wp_get_attachment_image_url( get_post_thumbnail_id(), "full" );
				$use_effect = get_field( 'use_effect', get_the_ID() );
			}
			if ( ! $banner_src ) {
				$banner_src = get_field( "default_banner", "option" );
				$use_effect = get_field( 'use_effect', 'option' );
			}
			?>
			<?php
			if ( ! empty( $banner_src ) ) {
				?>
                <div class="banner" style="background-image: url('<?php echo $banner_src ?>')">
                    <img src="<?php echo $banner_src ?>" alt="<?php the_title() ?>" class="js-canvas-image">
					<?php
					if ( $use_effect ):;
						?>
                        <div id="js-canvas-wrapper" class="canvas-wrapper"></div>
					<?php endif; ?>
                    <div class="page-title-breadcrumbs">
						<?php
						twc_show_page_title();
						if ( function_exists( 'yoast_breadcrumb' ) ) {
							?>
							<?php yoast_breadcrumb( '<div id="breadcrumbs">', '</div>' ); ?>
							<?php
						}
						?>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
	}
	?>
</header>