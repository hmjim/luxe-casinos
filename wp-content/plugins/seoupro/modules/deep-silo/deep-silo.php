<?php
if(!get_option('su_silo_active')){
	update_option('su_silo_active', '0');
}
$su_silo_active = get_option( 'su_silo_active' );
if($su_silo_active == "10") {
	define( 'WPUS_THEME_NAME', 'Silo Builder' );
	define( 'WPUS_PLUGIN_OPTIONS', '_wpu_silo_builder' );
	define( 'WPUS_PLUGIN_STYLES', '_wpu_silo_styles' );
	define( 'WPUS_TXT_DOMAIN', 'wpus_silo' );
	define( 'WPUS_THEME_SUBMITTED_FIELD', 'wpu_silo_submitted' );
	define( 'WPUS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

	require_once( plugin_dir_path( __FILE__ ) . 'includes/wpu_category_order.php' );
	require_once( plugin_dir_path( __FILE__ ) . 'includes/deep-silo.php' );
}

?>