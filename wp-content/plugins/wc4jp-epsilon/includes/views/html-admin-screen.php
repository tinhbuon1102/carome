<div class="wrap woocommerce">
    <h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
        <a href="<?php echo admin_url('admin.php?page=wc4jp-epsilon-output') ?>" class="nav-tab <?php echo ($tab == 'setting') ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Setting', 'wc4jp-epsilon' )?></a><a href="<?php echo admin_url('admin.php?page=wc4jp-epsilon-output&tab=info') ?>" class="nav-tab <?php echo ($tab == 'info') ? 'nav-tab-active' : ''; ?>"><?php echo __( 'Infomations', 'wc4jp-epsilon' )?></a>
    </h2>
	<?php
		switch ($tab) {
			case "setting" :
				$this->admin_epsilon_pro_setting_page();
			break;
			default :
				$this->admin_epsilon_pro_info_page();
			break;
		}
	?>
</div>
