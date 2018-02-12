<?php
/* ==========================================================
  Team
=========================================================== */
if ( !function_exists('elsey_team_function') ) {

  function elsey_team_function( $atts, $content = NULL ) {

    extract(shortcode_atts(array(
      'team_columns'        => '',
      'team_limit'          => '',
      'team_title'          => '',
      'team_sub_title'      => '',
      'team_member_details' => '',
      'team_member_job'     => '',
      'team_order'          => '',
      'team_orderby'        => '',
      'class'               => '',
    ), $atts));  
    
    // Column Style
    if($team_columns === 'els-team-col-2') {
      $grid_number = 2;
      $team_column_class = 'col-lg-6 col-md-6 col-sm-6 col-xs-6 elsey-team-wide';
    } else if($team_columns === 'els-team-col-3') {
      $grid_number = 3;
      $team_column_class = 'col-lg-4 col-md-4 col-sm-4 col-xs-6 elsey-team-wide';
    } else {
      $grid_number = 1;
      $team_column_class = '';
    }

    // Turn output buffer on
    ob_start();
    
    $args = array(
      // other query params here,
      'post_type'      => 'team',
      'posts_per_page' => (int)$team_limit,
      'orderby'        => $team_orderby,
      'order'          => $team_order
    );

    $vcts_post = new WP_Query( $args );
?>
    <!-- Team Start -->       
    <div class="els-team <?php echo esc_attr($team_columns . ' ' . $class); ?>">       
    
			<?php if(isset($team_title)) { ?>  
        <h5 class="els-team-title">   
          <?php echo esc_attr($team_title); ?>
        </h5>
      <?php } ?>

      <?php if(isset($team_sub_title)) { ?>  
        <h2 class="els-team-sub-title">   
          <?php echo esc_attr($team_sub_title); ?>
        </h2>
      <?php } 
        echo '<div class="row">';
	    if ($vcts_post->have_posts()) : 
	      $count_all_post = $vcts_post->post_count;
	      $count = 0;
	           
	      while ($vcts_post->have_posts()) : $vcts_post->the_post();

	        $count++;  

	        if ( $grid_number != 1) {
	          // if( $count === 1 ) {
	          //   // echo '<div class="row">';
	          // } else if(( $count % $grid_number ) === 1 ) {
	          //   // echo '<div class="row">';
	          // }
	          echo '<div class="'. esc_attr($team_column_class) .'">';
	        } ?>

	        <div class="els-team-box"> 
        
          <?php        
          $large_image  = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
          $large_image  = $large_image[0];
          $team_options = get_post_meta( get_the_ID(), 'team_options', true );
          $member_link  = ($team_options['team_member_custom_link']) ? $team_options['team_member_custom_link'] : get_the_permalink();
          $member_job   = ($team_options['team_member_job_position']) ? $team_options['team_member_job_position'] : '';
          $member_fcb   = ($team_options['team_member_facebook_link']) ? $team_options['team_member_facebook_link'] : '';
          $member_twr   = ($team_options['team_member_twitter_link']) ? $team_options['team_member_twitter_link'] : '';
          $member_ins   = ($team_options['team_member_instagram_link']) ? $team_options['team_member_instagram_link'] : '';

          if ($large_image) {   

            if($team_columns === 'els-team-col-3') {
              if(class_exists('Aq_Resize')) {
                $team_img = aq_resize( $large_image, '385', '420', true );
                $team_img = ($team_img) ? $team_img : $large_image;
              } else {
                $team_img = $large_image;
              }
            } else if($team_columns === 'els-team-col-2') {
              if(class_exists('Aq_Resize')) {
                $team_img = aq_resize( $large_image, '555', '590', true );
                $team_img = ($team_img) ? $team_img : $large_image;
              } else {
                $team_img = $large_image;
              }
            } else {
              $team_img = $large_image; 
            } ?>

            <div class="els-team-img">
              <img src="<?php echo esc_url($team_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
            </div>
       	  
          <?php 
          } else {

            if($team_columns === 'els-team-col-3') {
              $team_img = ELSEY_PLUGIN_ASTS . '/images/385x420.jpg';
            } else if($team_columns === 'els-team-col-2') {
              $team_img = ELSEY_PLUGIN_ASTS . '/images/555x590.jpg';
            } else {
              $team_img = ELSEY_PLUGIN_ASTS . '/images/1170x705.jpg';
            } ?>

            <div class="els-team-img">
              <img src="<?php echo esc_url($team_img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"/>
            </div>

          <?php } // Featured Image ?>
          
          <div class="els-team-info">
            <div class="els-team-lift-up">

              <div class="els-team-member-name">
                <a href="<?php echo esc_url($member_link) ?>">
                  <?php the_title(); ?>
                </a>
              </div>

              <?php if($team_member_job) { ?>  
                <div class="els-team-member-job">   
                  <?php echo esc_attr($member_job); ?>
                </div>
              <?php }

              if($team_member_details) { 
                if($member_fcb || $member_twr || $member_ins) { ?>  
                  <div class="els-team-member-details">   
                    <?php 
                    if(isset($member_fcb)) {
                      echo '<a href="'.esc_url($member_fcb).'" target="_blank">'.esc_html__('Facebook').'</a>';
                    } 
                    if(isset($member_twr)) {
                      echo ($member_fcb) ? esc_html__('/') : '';
                      echo '<a href="'.esc_url($member_twr).'" target="_blank">'.esc_html__('Twitter').'</a>';
                    } 
                    if(isset($member_ins)) {
                      echo ($member_fcb || $member_twr) ? esc_html__('/') : '';
                      echo '<a href="'.esc_url($member_ins).'" target="_blank">'.esc_html__('Instagram').'</a>';
                    } 
                    ?>
                  </div>
                <?php 
                } 
              } ?> 

            </div>
          </div>
          
        </div>  

        <?php    
        if ( $grid_number != 1) {
          echo '</div>';
          // if((($count % $grid_number) === 0) || ($count === ($count_all_post))) {
          //   // echo '</div>';
          // }
        }   

      endwhile;
     
      endif;
       echo "</div>";  ?>

    </div>
    <!-- Team End -->

<?php
    wp_reset_postdata();  
    // avoid errors further down the page
    
    // Return outbut buffer
    return ob_get_clean();

  }
}

add_shortcode( 'elsey_team', 'elsey_team_function' );
