<div class="post-modern">
    <div class="post-inner">
        <?php carafity_post_thumbnail('carafity-post-grid', false); ?>
        <div class="post-content">
            <div class="entry-header">
                <div class="entry-meta">
                    <?php carafity_post_meta(['show_cat' => true, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
                </div>
                <?php the_title('<h3 class="sigma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
            </div>
        </div>
    </div>
</div>
