<?php
// Check for schema markup and add it to head
function su_json_head() {
	global $wpdb;
	$post    = get_post();
	if(!empty($post)) {
		@$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = {$post->ID} AND meta_key LIKE 'su_post_schema%'", OBJECT );
		foreach ( $results as $result ) {
			echo stripslashes_deep( $result->meta_value );
		}
	}
}

// add json schema if any to head
add_action( 'wp_head', 'su_json_head' );
?>