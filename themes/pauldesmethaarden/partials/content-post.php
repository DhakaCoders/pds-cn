<?php
$title    = get_the_title();
$link     = get_the_permalink();
$image_id = get_field( 'thumbnail_image' );
if ( empty( $image_id ) ) {
	$image_id = get_post_thumbnail_id();
}
if ( empty( $image_id ) ) {
	$image_id = get_field( 'default_thumbnail', 'option' );
}
$imgURL = wp_get_attachment_image_src( $image_id, 'full' )[0];
$imgTag = wp_get_attachment_image( $image_id, 'full' );

$excerpt = ! empty( get_the_excerpt() ) ? get_the_excerpt() : wp_trim_excerpt();
?>
<div class="item blog-item">
    <a class="inner" href="<?= $link ?>">

        <div class="item-image">
            <div class="item-image-inner" style="background-image: url('<?= $imgURL ?>')">
				<?= $imgTag ?>
            </div>
        </div>
        <h3><?= $title ?></h3>
        <div class="item-excerpt">
	        <?php the_excerpt(); ?>
        </div>
    </a>
</div>