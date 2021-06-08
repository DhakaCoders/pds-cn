<?php
$discount_price = get_field( 'product_discount_price' );
?>
<div class="item <?= $discount_price ? 'has-discount' : '' ?>">
    <div class="inner" data-link="<?php the_permalink() ?>">
		<?php
		if ( $discount_price ):;
			?>
            <label class="promo">PROMO</label>
		<?php endif; ?>
        <div class="item-image">
            <div class="item-image-inner" style="background-image: url('<?php echo twc_get_image( get_the_ID() ) ?>')">
                <img src="<?php echo twc_get_image( get_the_ID() ); ?>" alt="<?php echo get_the_title() ?>"/>
            </div>
        </div>
        <h3><?php the_title(); ?></h3>
        <div class="item-excerpt">
			<?php the_excerpt(); ?>
        </div>

		<?php
		if ( $price = get_field( 'product_price' ) ) {
			?>
            <div class="item-price">
				<?php
				if ( $discount_price ) {
					?>
                    <span class="discount">
			            <?php echo twc_get_price( 'product_discount_price', get_the_ID() ); ?>
                    </span>
					<?php
				}
				?>
                <span class="<?= $discount_price ? 'line-through' : '' ?>">
				<?php echo twc_get_price( 'product_price', get_the_ID() ); ?>
                    </span>
            </div>
			<?php
		}
		?>
    </div>
</div>