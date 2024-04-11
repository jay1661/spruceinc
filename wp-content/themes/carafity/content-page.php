<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to carafity_page action
	 *
	 * @see carafity_page_header          - 10
	 * @see carafity_page_content         - 20
	 *
	 */
	do_action( 'carafity_page' );
	?>
</article><!-- #post-## -->
