<div class="post-inner">
    <?php carafity_post_thumbnail('carafity-post-grid', false); ?>
    <div class="post-content">
        <div class="entry-header">
            <div class="entry-meta">
                <?php carafity_post_meta(['show_cat' => true, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
            </div>
            <?php the_title('<h3 class="sigma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
        </div>
        <div class="entry-content">
            <div class="entry-excerpt">
                <?php
                the_excerpt(); ?>
            </div>
            <?php
            echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Read more', 'carafity') . '<i class="carafity-icon-long-arrow-right"></i></a></div>';
            ?>
        </div>
    </div>
</div>