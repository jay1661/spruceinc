<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <?php carafity_post_thumbnail('post-thumbnail', false); ?>
    <div class="post-content">
        <?php
        /**
         * Functions hooked in to carafity_loop_post action.
         *
         * @see carafity_post_header          - 15
         * @see carafity_post_content         - 30
         */
        do_action('carafity_loop_post');
        ?>
    </div>
</article><!-- #post-## -->