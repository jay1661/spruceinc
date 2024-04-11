<?php
/**
 * Photoswipe markup
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/photoswipe.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="pswp__bg"></div>
	<div class="pswp__scroll-wrap">
		<div class="pswp__container">
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
			<div class="pswp__item"></div>
		</div>
		<div class="pswp__ui pswp__ui--hidden">
			<div class="pswp__top-bar">
				<div class="pswp__counter"></div>
				<button class="pswp__button pswp__button--close" aria-label="<?php esc_attr_e( 'Close (Esc)', 'carafity' ); ?>"></button>
				<button class="pswp__button pswp__button--share" aria-label="<?php esc_attr_e( 'Share', 'carafity' ); ?>"></button>
				<button class="pswp__button pswp__button--fs" aria-label="<?php esc_attr_e( 'Toggle fullscreen', 'carafity' ); ?>"></button>
				<button class="pswp__button pswp__button--zoom" aria-label="<?php esc_attr_e( 'Zoom in/out', 'carafity' ); ?>"></button>
				<div class="pswp__preloader">
					<div class="pswp__preloader__icn">
						<div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						</div>
					</div>
				</div>
			</div>
            <button class="pswp__button pswp__button--prev pswp__button--arrow--left RoundButton" data-animate-left aria-label="<?php esc_attr_e( 'Previous (arrow left)', 'carafity' ); ?>"><svg class="Icon Icon--arrow-left" role="presentation" viewBox="0 0 11 21">
                    <polyline fill="none" stroke="currentColor" points="10.5 0.5 0.5 10.5 10.5 20.5" stroke-width="1.25"></polyline>
                </svg></button>
            <button class="pswp__button pswp__button--close RoundButton RoundButton--large" data-animate-bottom aria-label="<?php esc_attr_e( 'Close (Esc)', 'carafity' ); ?>"><svg class="Icon Icon--close" role="presentation" viewBox="0 0 16 14">
                    <path d="M15 0L1 14m14 0L1 0" stroke="currentColor" fill="none" fill-rule="evenodd"></path>
                </svg></button>
            <button class="pswp__button pswp__button--next pswp__button--arrow--right RoundButton" data-animate-right  aria-label="<?php esc_attr_e( 'Next (arrow right)', 'carafity' ); ?>"><svg class="Icon Icon--arrow-right" role="presentation" viewBox="0 0 11 21">
                    <polyline fill="none" stroke="currentColor" points="0.5 0.5 10.5 10.5 0.5 20.5" stroke-width="1.25"></polyline>
                </svg></button>
			<div class="pswp__caption">
				<div class="pswp__caption__center"></div>
			</div>
		</div>
	</div>
</div>
