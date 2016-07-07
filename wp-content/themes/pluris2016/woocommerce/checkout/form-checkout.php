<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	
	<?php
		global $wc_cpdf;

		$posts_query = new WP_Query();
		$posts_query->query( array(
			'post_type' => 'bs_posts_articles',
			'posts_per_page' => -1
		));
	?>

	<?php if ($posts_query->have_posts()) { ?>
		<?php 
			$product_cart = WC()->cart->get_cart();

			foreach ($product_cart as $key => $value) {
				$qty_articles = $wc_cpdf->get_value($value['product_id'], 'qty_articles');
			}
		?>
		<div id="checkout_articles">
			<h2><?php echo __('Articles', 'pluris2016') ?></h2>

			<p class="form-row form-row form-row-wide">
				<label>
					<?php echo __('Did you sent an article ?', 'pluris2016'); ?>
				</label>

				<div id="checkout_articles_radio">
					<input type="radio" name="article_sent" class="article_sent" value="1" id="article_sent_yes"/> <label for="article_sent_yes"><?php echo __('Yes'); ?></label>
					<input type="radio" name="article_sent" class="article_sent" value="0" id="article_sent_no"/> <label for="article_sent_no"><?php echo __('No'); ?></label>
				</div>
			</p>

			<p  id="articles_select" class="form-row form-row form-row-wide">
				<label for="article_id">
					<?php if($qty_articles > 1) { ?>
						<?php echo sprintf(__('Select %s articles','pluris2016'), $qty_articles); ?>
					<?php } else { ?>
						<?php echo sprintf(__('Select %s article','pluris2016'), $qty_articles); ?>
					<?php } ?>

				</label>

				<select id="articles_id" name="articles_id[]" <?php echo $qty_articles > 1 ? 'multiple' : ''; ?> name="article_id" id="article_id" autocomplete="article" class="country_to_state country_select">
					<option><?php echo __('Search your article in this list', 'pluris2016'); ?></option>
					<?php while($posts_query->have_posts()){ ?>
						<?php $posts_query->the_post() ?>
						<option value="<?php echo get_the_ID();?>"><?php echo get_the_title();?></option>
					<?php }?>
					<?php wp_reset_postdata(); ?>
				</select>
			</p>
		</div>
	<?php } ?>	

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<h3 id="order_review_heading"><?php _e( 'Payment', 'pluris2016' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
