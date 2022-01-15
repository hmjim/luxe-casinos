<?php
if(is_admin()) {
// Add action hook only if action=download_csv
	if ( isset( $_GET['action'] ) && $_GET['action'] == 'download_csv' ) {
		// Handle CSV Export
		add_action( 'admin_init', 'su_csv_export' );
	}
	function su_csv_export() {
		// Check for current user privileges
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		// Check if we are in WP-Admin
		if ( ! is_admin() ) {
			return false;
		}
		// Nonce Check
		$nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
		if ( ! wp_verify_nonce( $nonce, 'download_csv' ) ) {
			die( 'Security check error' );
		}

		ob_start();
		$domain = $_SERVER['SERVER_NAME'];


		$data_rows = array();
		global $wpdb;
		if ( $_GET['type'] == "ttr" ) {
			$sql        = 'SELECT meta_id,' . $wpdb->prefix . 'postmeta.post_id,meta_key,meta_value,' . $wpdb->prefix . 'posts.post_title AS curtitle FROM ' . $wpdb->prefix . 'postmeta INNER JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id WHERE meta_key = \'_su_title\' AND post_status = \'publish\'';
			$filename   = 'titles-' . $domain . '-' . time() . '.csv';
			$header_row = array(
				'ID',
				'POST ID',
				'Title',
				'SUP Meta Key',
				'SUP Value'
			);
			$dataout    = $wpdb->get_results( $sql, 'ARRAY_A' );
			foreach ( $dataout as $dataout1 ) {
				$row         = array(
					$dataout1['meta_id'],
					$dataout1['post_id'],
					$dataout1['curtitle'],
					@$dataout1['meta_key'],
					$dataout1['meta_value']
				);
				$data_rows[] = $row;
			}
		} elseif ( $_GET['type'] == "metadescr" ) {
			$sql        = 'SELECT meta_id,' . $wpdb->prefix . 'postmeta.post_id,meta_key,meta_value,' . $wpdb->prefix . 'posts.post_title AS curtitle FROM ' . $wpdb->prefix . 'postmeta INNER JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id WHERE meta_key = \'_su_description\' AND post_status = \'publish\'';
			$filename   = 'meta-' . $domain . '-' . time() . '.csv';
			$header_row = array(
				'ID',
				'POST ID',
				'Title',
				'SUP Meta Key',
				'SUP Value'
			);
			$dataout    = $wpdb->get_results( $sql, 'ARRAY_A' );
			foreach ( $dataout as $dataout1 ) {
				$row         = array(
					$dataout1['meta_id'],
					$dataout1['post_id'],
					$dataout1['curtitle'],
					@$dataout1['meta_key'],
					$dataout1['meta_value']
				);
				$data_rows[] = $row;
			}
		} elseif ( $_GET['type'] == "og" ) {
			$sql        = 'SELECT meta_id,' . $wpdb->prefix . 'postmeta.post_id,meta_key,meta_value,' . $wpdb->prefix . 'posts.post_title AS curtitle FROM ' . $wpdb->prefix . 'postmeta INNER JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id WHERE meta_key Like \'%_su_og%\' AND post_status = \'publish\'';
			$filename   = 'opengraph-' . $domain . '-' . time() . '.csv';
			$header_row = array(
				'ID',
				'POST ID',
				'Title',
				'SUP Meta Key',
				'SUP Value'
			);
			$dataout    = $wpdb->get_results( $sql, 'ARRAY_A' );
			foreach ( $dataout as $dataout1 ) {
				$row         = array(
					$dataout1['meta_id'],
					$dataout1['post_id'],
					$dataout1['curtitle'],
					@$dataout1['meta_key'],
					$dataout1['meta_value']
				);
				$data_rows[] = $row;
			}
		} elseif ( $_GET['type'] == "mrte" ) {
			$sql        = 'SELECT meta_id,' . $wpdb->prefix . 'postmeta.post_id,meta_key,meta_value,' . $wpdb->prefix . 'posts.post_title AS curtitle FROM ' . $wpdb->prefix . 'postmeta INNER JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id WHERE meta_key LIKE \'%_su_meta_robots%\' AND post_status = \'publish\'';
			$filename   = 'metarobots-' . $domain . '-' . time() . '.csv';
			$header_row = array(
				'ID',
				'POST ID',
				'Title',
				'WP Meta Key',
				'SUP Value'
			);
			$dataout    = $wpdb->get_results( $sql, 'ARRAY_A' );
			foreach ( $dataout as $dataout1 ) {
				$row         = array(
					$dataout1['meta_id'],
					$dataout1['post_id'],
					$dataout1['curtitle'],
					@$dataout1['meta_key'],
					$dataout1['meta_value']
				);
				$data_rows[] = $row;
			}
		} elseif ( $_GET['type'] == "altattrib" ) {
			$sql        = 'SELECT meta_id,' . $wpdb->prefix . 'postmeta.post_id,meta_key,meta_value,' . $wpdb->prefix . 'posts.post_title AS curtitle FROM ' . $wpdb->prefix . 'postmeta INNER JOIN ' . $wpdb->prefix . 'posts ON ' . $wpdb->prefix . 'posts.ID = ' . $wpdb->prefix . 'postmeta.post_id WHERE meta_key = \'_wp_attachment_image_alt\'';
			$filename   = 'altattrib-' . $domain . '-' . time() . '.csv';
			$header_row = array(
				'ID',
				'POST ID',
				'Title',
				'SUP Meta Key',
				'SUP Value'
			);
			$dataout    = $wpdb->get_results( $sql, 'ARRAY_A' );
			foreach ( $dataout as $dataout1 ) {
				$row         = array(
					$dataout1['meta_id'],
					$dataout1['post_id'],
					$dataout1['curtitle'],
					@$dataout1['meta_key'],
					$dataout1['meta_value']
				);
				$data_rows[] = $row;
			}
		}


		$fh = @fopen( 'php://output', 'w' );
		fprintf( $fh, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );
		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: text/csv' );
		header( "Content-Disposition: attachment; filename={$filename}" );
		header( 'Expires: 0' );
		header( 'Pragma: public' );
		fputcsv( $fh, $header_row );
		foreach ( $data_rows as $data_row ) {
			fputcsv( $fh, $data_row );
		}
		fclose( $fh );

		ob_end_flush();

		die();
	}
}

?>