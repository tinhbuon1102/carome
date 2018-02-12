<?php
/*
 * The template for displaying comments.
 * Author & Copyright: VictorThemes
 * URL: http://themeforest.net/user/VictorThemes
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */

if ( post_password_required() ) {
  return;
}

$elsey_prev_comment_text   = cs_get_option('previous_comment_text') ? cs_get_option('previous_comment_text') : esc_html__( 'Older Comments', 'elsey' );
$elsey_next_comment_text   = cs_get_option('next_comment_text') ? cs_get_option('next_comment_text') : esc_html__( 'Newer Comments', 'elsey' );
$elsey_sgular_comment_text = cs_get_option('singular_comment_text') ? cs_get_option('singular_comment_text') : esc_html__( 'Comment', 'elsey' );
$elsey_plural_comment_text = cs_get_option('plural_comment_text') ? cs_get_option('plural_comment_text') : esc_html__( 'Comments', 'elsey' );  ?>

<!-- Comments Start -->
<div class="els-commentbox">

  <div class="els-comments-area comments-area" id="comments">

    <?php if ( have_comments() ) : ?>
    	<div class="comments-section">

    	  <h3 class="comments-title">
	    	<?php
	    		printf( // WPCS: XSS OK.
	    			esc_html( _nx( '%1$s '.$elsey_sgular_comment_text, '%1$s '.$elsey_plural_comment_text, get_comments_number(), 'comments title', 'elsey' ) ),
	    			number_format_i18n( get_comments_number() ),
	    			'<span>' . get_the_title() . '</span>'
	    		);
	    	?>
	      </h3>

	      <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
	   	    <nav id="comment-nav-above" class="navigation els-comment-navigation" role="navigation">
		    		<h2 class="els-screen-reader-text"><?php esc_html_e( 'Comment navigation', 'elsey' ); ?></h2>
		    		<div class="els-nav-links">
		    			<div class="els-nav-previous"><?php previous_comments_link( $elsey_prev_comment_text ); ?></div>
		    			<div class="els-nav-next"><?php next_comments_link( $elsey_next_comment_text ); ?></div>
		    		</div>
	    		</nav>
	      <?php endif; // Check for comment navigation. ?>

	      <ol class="comments">
	        <?php wp_list_comments('	type=comments&callback=elsey_comment_modification'); ?>
	      </ol>

	      <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		    	<nav id="els-comment-nav-below" class="navigation els-comment-navigation" role="navigation">
		    		<h2 class="els-screen-reader-text"><?php esc_html_e( 'Comment navigation', 'elsey' ); ?></h2>
		    		<div class="els-nav-links">
		    			<div class="els-nav-previous"><?php previous_comments_link( $elsey_prev_comment_text ); ?></div>
		    			<div class="els-nav-next"><?php next_comments_link( $elsey_next_comment_text ); ?></div>
		    		</div>
		    	</nav>
	      <?php endif; // Check for comment navigation. ?>

			</div>
    <?php endif; // Check for have_comments().

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<div class="comments-section">
			  <p class="els-no-comments"><?php esc_html_e( 'Comments are closed.', 'elsey' ); ?></p>
			</div>
		<?php endif; ?>

	</div>

	<?php
	/* ==============================================
	  Comment Forms
	=============================================== */
  if ( comments_open() ) { ?>
		<div class="els-comment-form">
		  <?php
			$elsey_comment_label_text = (cs_get_option('comment_field_text')) ? cs_get_option('comment_field_text') : esc_html__('Comment ', 'elsey');
			$elsey_name_label_text    = (cs_get_option('name_field_text')) ? cs_get_option('name_field_text') : esc_html__('Name ', 'elsey');
			$elsey_email_label_text   = (cs_get_option('email_field_text')) ? cs_get_option('email_field_text') : esc_html__('Email ', 'elsey');
			$elsey_url_label_text     = (cs_get_option('url_field_text')) ? cs_get_option('url_field_text') : esc_html__('Website', 'elsey');
			$elsey_post_comment_text  = (cs_get_option('post_comment_text')) ? cs_get_option('post_comment_text') : esc_html__('Post Comment', 'elsey');
			$elsey_form_title_text    = (cs_get_option('comment_form_title_text')) ? cs_get_option('comment_form_title_text') : esc_html__('Leave a reply', 'elsey');
			$elsey_form_reply_text    = (cs_get_option('comment_form_reply_to_text')) ? cs_get_option('comment_form_reply_to_text') : esc_html__('Leave a reply to', 'elsey');

			$fields = array(
		    'author' => '<div class="row els-form-inputs"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 els-form-input-box"><p><label>'.esc_attr($elsey_name_label_text).'<span>'.esc_html__('*', 'elsey').'</span></label><input type="text" id="author" name="author" value="'.esc_attr($commenter['comment_author']) . '" tabindex="1"/></p></div>',
		    'email' => '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 els-form-input-box"><p><label>'.esc_attr($elsey_email_label_text).'<span>'.esc_html__('*', 'elsey').'</span></label><input type="text" id="email" name="email" value="'.esc_attr($commenter['comment_author_email']).'" tabindex="2" /></p></div>',
		    'URL' => '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 els-form-input-box"><p><label>'.esc_attr($elsey_url_label_text).'</label><input type="text" id="url" name="url" value="'.esc_attr($commenter['comment_author_url']).'" tabindex="3" /></p></div></div>',
		  );

			$defaults = array(
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'fields'               => apply_filters( 'comment_form_default_fields', $fields),
				'comment_field' 	     => '<div class="els-form-textarea"><p><label>'.esc_attr($elsey_comment_label_text).'<span>'.esc_html__('*', 'elsey').'</span></label><textarea id="comment" name="comment" tabindex="4" rows="3" cols="30"></textarea></p></div>',
				'id_form'              => 'commentform',
				'class_form'           => 'comment-form els-contact',
				'id_submit'            => 'submit',
				'cancel_reply_link'    => '<i class="fa fa-times-circle"></i>'. '',
				'title_reply'          => esc_attr($elsey_form_title_text),
				'title_reply_to'       => esc_attr($elsey_form_reply_text).' %s',
				'label_submit'         => esc_attr($elsey_post_comment_text),
	    );

	    comment_form($defaults); ?>
		</div>
	<?php } ?>

</div>
<!-- Comments End -->