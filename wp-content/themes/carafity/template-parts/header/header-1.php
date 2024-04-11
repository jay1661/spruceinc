<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="header-main">
            <div class="header-left">
                <?php
                carafity_site_branding();
                if (carafity_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php carafity_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php carafity_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php carafity_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    carafity_header_account();
                    if (carafity_is_woocommerce_activated()) {
                        carafity_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
