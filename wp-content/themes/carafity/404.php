<?php
get_header(); ?>

    <div id="primary" class="content">
        <main id="main" class="site-main">
            <div class="error-404 not-found">
                <div class="page-content">
                    <div class="error-content">
                        <div class="page-title">
                            <h1 class="title"><?php esc_html_e('404', 'carafity'); ?></h1>
                        </div>
                        <div class="error-text">
                            <h2 class="text"><?php esc_html_e('We can’t find the page your are looking for', 'carafity') ?></h2>
                        </div>
                        <div class="button-error">
                            <a href="javascript: history.go(-1)" class="go-back"><?php esc_html_e('back to homepage', 'carafity'); ?><i class="carafity-icon-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php

get_footer();
