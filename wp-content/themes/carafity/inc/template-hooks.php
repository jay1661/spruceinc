<?php
/**
 * =================================================
 * Hook carafity_page
 * =================================================
 */
add_action('carafity_page', 'carafity_page_header', 10);
add_action('carafity_page', 'carafity_page_content', 20);

/**
 * =================================================
 * Hook carafity_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_single_post
 * =================================================
 */
add_action('carafity_single_post', 'carafity_post_header', 10);
add_action('carafity_single_post', 'carafity_post_thumbnail', 20);
add_action('carafity_single_post', 'carafity_post_content', 30);

/**
 * =================================================
 * Hook carafity_single_post_bottom
 * =================================================
 */
add_action('carafity_single_post_bottom', 'carafity_post_taxonomy', 5);
add_action('carafity_single_post_bottom', 'carafity_single_author', 10);
add_action('carafity_single_post_bottom', 'carafity_post_nav', 15);
add_action('carafity_single_post_bottom', 'carafity_display_comments', 20);

/**
 * =================================================
 * Hook carafity_loop_post
 * =================================================
 */
add_action('carafity_loop_post', 'carafity_post_header', 15);
add_action('carafity_loop_post', 'carafity_post_content', 30);

/**
 * =================================================
 * Hook carafity_footer
 * =================================================
 */
add_action('carafity_footer', 'carafity_footer_default', 20);

/**
 * =================================================
 * Hook carafity_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'carafity_template_account_dropdown', 1);
add_action('wp_footer', 'carafity_mobile_nav', 1);
add_action('wp_footer', 'render_html_back_to_top', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'carafity_pingback_header', 1);

/**
 * =================================================
 * Hook carafity_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_sidebar
 * =================================================
 */
add_action('carafity_sidebar', 'carafity_get_sidebar', 10);

/**
 * =================================================
 * Hook carafity_loop_after
 * =================================================
 */
add_action('carafity_loop_after', 'carafity_paging_nav', 10);

/**
 * =================================================
 * Hook carafity_page_after
 * =================================================
 */
add_action('carafity_page_after', 'carafity_display_comments', 10);

/**
 * =================================================
 * Hook carafity_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_woocommerce_before_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook carafity_woocommerce_after_shop_loop_item
 * =================================================
 */
