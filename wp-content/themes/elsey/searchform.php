<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>" class="searchform" >
  <div>
	  <input type="search" name="s" id="search" placeholder="<?php esc_html_e( 'Search', 'elsey'); ?>" />
	  <button><i class="fa fa-search"></i></button>
  </div>
</form>