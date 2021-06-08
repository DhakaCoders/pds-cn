<?php
global $wp_query;

$total_pages = $wp_query->max_num_pages;

if ( $total_pages > 1 ) {
	?>
    <div class="pagination">
        <div class="pagination__block">
			<?php
			$current_page = max( 1, get_query_var( 'paged' ) );

			$big = 999999999; // need an unlikely integer

			echo paginate_links( array(
				'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'format'    => '?paged=%#%',
				'current'   => $current_page,
				'total'     => $total_pages,
				'prev_text' => '<span class="fa fa-angle-left" aria-hidden="true"></span>',
				'next_text' => '<span class="fa fa-angle-right" aria-hidden="true"></span>'
			) );
			?>
        </div>
    </div>
	<?php
}
?>