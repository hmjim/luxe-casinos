<?php
function su_404_redirect() {
	if ( is_404() ) {
		$psdata = (array) get_option( 'seo_ultimate_plus_module_404s', array() );
		if ( $psdata['default_404_page'] == "" ) {
			return;
		} else {
			header( 'HTTP/1.1 301 Moved Permanently' );
			header( "Location: " . $psdata['default_404_page'] );
			exit();
		}
	}
}

add_action('wp', 'su_404_redirect');
?>