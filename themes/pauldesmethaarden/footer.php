<footer>
    <div class="container">
        <div class="footer-content main_content">
			<?php echo apply_filters( "the_content", str_replace( "#year", date( "Y" ), get_field( "footer_content", "option" ) ) ); ?>
        </div>
    </div>
    <span class="scroll-top">TOP <i class="fa fa-angle-double-up" aria-hidden="true"></i></span>
</footer>

<?php
$cta = get_field( 'cta_buttons', 'option' );
if ( $cta ): ?>
    <div class="cta-button">
		<?php foreach ( get_field( 'cta_buttons', 'option' ) as $item ):; ?>
            <a href="<?= $item['cta_link'] ?>" title="<?= $item['cta_mobile_link'] ?>" data-href="<?= $item['cta_link'] ?>"><?= $item['cta_icon'] ?></a>
		<?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
//wp_enqueue_script( 'script-water-distortion', THEME_URL . '/assets/js/water-distortion.js', array( 'jquery' ), '1.0.0', false );
wp_footer(); ?>
</body>
</html>