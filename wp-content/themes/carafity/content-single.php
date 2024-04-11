<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to carafity_single_post_top action
         *
         */
        do_action('carafity_single_post_top');

        /**
         * Functions hooked in to carafity_single_post action
         * @see carafity_post_header         - 10
         * @see carafity_post_thumbnail - 20
         * @see carafity_post_content         - 30
         */
        do_action('carafity_single_post');

        /**
         * Functions hooked in to carafity_single_post_bottom action
         *
         * @see carafity_post_taxonomy      - 5
         * @see carafity_single_author      - 10
         * @see carafity_post_nav            - 15
         * @see carafity_display_comments    - 20
         */
        do_action('carafity_single_post_bottom');
        ?>

    </div>

</article><!-- #post-## -->
