<?php
/**
 * VictorTheme Custom Changes - Field Label Remove and Added Placeholder, Submit Button Text Change
 */

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! comments_open() ) {
	return;
} ?>

<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<h2 class="woocommerce-Reviews-title">
			<?php
				if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
					/* translators: 1: reviews count 2: product name */
					printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'elsey' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				} else {
					esc_html_e( 'Reviews', 'elsey' );
				}
			?>
		</h2>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'elsey' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'comment_notes_before' => '<p class="comment-form-notes">'. esc_html__('Your email address will not be published. Required fields are marked ', 'elsey' ) .'<span>*</span></p>',
						'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'elsey' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'elsey' ), get_the_title() ),
						'title_reply_to'       => __( 'Leave a Reply to %s', 'elsey' ),
						'title_reply_before'   => '<span id="reply-title" class="comment-reply-title">',
						'title_reply_after'    => '</span>',
						'comment_notes_after'  => '',
						'fields'               => array(
							'author' => '<p class="comment-form-author">'.'<input id="author" name="author" type="text" value="'.esc_attr( $commenter['comment_author'] ).'" placeholder="'.esc_html__( 'Name', 'elsey' ).'" size="30" aria-required="true" required /></p>',
							'email'  => '<p class="comment-form-email">'.'<input id="email" name="email" type="email" value="'.esc_attr( $commenter['comment_author_email'] ).'" placeholder="'.esc_html__( 'Email', 'elsey' ).'" size="30" aria-required="true" required /></p>',
						), //Custom Change - Field Label Remove and Added Placeholder
						'label_submit'  => esc_html__( 'Add Your Review', 'elsey' ), //Custom Change - Submit Title Change
						'logged_in_as'  => '',
						'comment_field' => '',
					);

					if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
						$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be <a href="%s">logged in</a> to post a review.', 'elsey' ), esc_url( $account_page_url ) ) . '</p>';
					}

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
						$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating:', 'elsey' ) . '</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'elsey' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'elsey' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'elsey' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'elsey' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'elsey' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'elsey' ) . '</option>
						</select></div>';
					}

					$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="'.esc_html__( 'Your Review', 'elsey' ).'" required></textarea></p>';
					//Custom Change - Field Label Remove and Added Placeholder

					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'elsey' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
