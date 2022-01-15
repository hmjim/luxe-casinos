<?php
if ( class_exists( 'SU_Module' ) ) {

	class SU_ImportExport extends SU_Module {

		static function get_module_title() {
			return __( 'Import/Export', 'seo-ultimate-pro' );
		}

		static function get_menu_title() {
			return __( 'Import/Export', 'seo-ultimate-pro' );
		}


		function admin_page_contents() {
			wp_enqueue_script( 'file-browse-js', plugin_dir_url( __FILE__ ) . 'js/filebrowse.js' );
			$su_max_filezise = file_upload_max_size();
			echo '
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
</style>
<div class="features-boxed">
    <div class="container">
        <div class="intro">
            <h2 class="text-center">IMPORT / EXPORT</h2>
            <p class="text-center">Import and Export Data For Modules in <strong>' . SU_PLUGIN_NAME . '</strong></p>
        </div>
        <div class="row features">
            <div class="col-md-4 col-sm-6 item">
                <div class="box"><i class="fa fa-pencil-alt fa-4x faicon"></i>
                    <h3 class="name"><strong>Title Tag Rewriter</strong></h3>
                    <p class="description">Import / Export Options For This Module</p>
                    <a style="color: #FFF;" class="btn btn-enable" href="' . admin_url( 'admin.php?page=su-importexport' ) . '&action=download_csv&type=ttr&_wpnonce=' . wp_create_nonce( 'download_csv' ) . '">Export</a><br>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 item">
                <div class="box"><i class="fa fa-cog fa-4x faicon"></i>
                    <h3 class="name"><strong>Meta Description Editor</strong></h3>
                    <p class="description">Import / Export Options For This Module<br /></p>
                    <a style="color: #FFF;" class="btn btn-enable" href="' . admin_url( 'admin.php?page=su-importexport' ) . '&action=download_csv&type=metadescr&_wpnonce=' . wp_create_nonce( 'download_csv' ) . '">Export</a><br>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 item">
                <div class="box"><i class="fa fa-list fa-4x faicon"></i>
                    <h3 class="name"><strong>OpenGraph</strong></h3>
                    <p class="description">Import / Export Options For This Module<br /></p>
                    <a style="color: #FFF;" class="btn btn-enable" href="' . admin_url( 'admin.php?page=su-importexport' ) . '&action=download_csv&type=og&_wpnonce=' . wp_create_nonce( 'download_csv' ) . '">Export</a><br>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 item">
                <div class="box"><i class="fa fa-globe fa-4x faicon"></i>
                    <h3 class="name"><strong>Meta Robots Tags Editor</strong></h3>
                    <p class="description">Import / Export Options For This Module<br /></p>
                    <a style="color: #FFF;" class="btn btn-enable" href="' . admin_url( 'admin.php?page=su-importexport' ) . '&action=download_csv&type=mrte&_wpnonce=' . wp_create_nonce( 'download_csv' ) . '">Export</a><br>
                </div>
            </div>

            </div>
            <div class="row">
            <div class="col-md-12 col-sm-12 item" style="background-color: #ffffff;">
            <form id="fileform" class="form-horizontal" method="post" enctype="multipart/form-data">
<fieldset>

<!-- Form Name -->
<legend>CSV File Import</legend>

<!-- File Button --> 
            <div class="col-md-4 col-sm-6  form-group input-group" style="margin-left: auto; margin-right: auto;">
                <label class="input-group-btn">
                    <span class="btn btn-primary">
                        Browse&hellip; <input type="file" name="file" style="display: none;" multiple>
                    </span>
                </label>
                <input type="text" class="form-control"  readonly>
            </div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="importcsv"></label>
  <div class="col-md-4">
    <input class="btn btn-disable" type="submit" name="upload" value="Import CSV File">
  </div>
  <div>
  <strong>MAX UPLOAD FILE SIZE:</strong> ' . formatBytes( $su_max_filezise ) . '
  </div>
</div>

</fieldset>
</form>
</div>
            </div>
        </div>
    </div>
<hr>';
// echo ABSPATH;


		}

	}

	function file_upload_max_size() {
		static $max_size = - 1;

		if ( $max_size < 0 ) {
			// Start with post_max_size.
			$post_max_size = parse_size( ini_get( 'post_max_size' ) );
			if ( $post_max_size > 0 ) {
				$max_size = $post_max_size;
			}

			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = parse_size( ini_get( 'upload_max_filesize' ) );
			if ( $upload_max > 0 && $upload_max < $max_size ) {
				$max_size = $upload_max;
			}
		}

		return $max_size;
	}

	function parse_size( $size ) {
		$unit = preg_replace( '/[^bkmgtpezy]/i', '', $size ); // Remove the non-unit characters from the size.
		$size = preg_replace( '/[^0-9\.]/', '', $size ); // Remove the non-numeric characters from the size.
		if ( $unit ) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round( $size * pow( 1024, stripos( 'bkmgtpezy', $unit[0] ) ) );
		} else {
			return round( $size );
		}
	}

	function formatBytes( $size, $precision = 2 ) {
		$base     = log( $size, 1024 );
		$suffixes = array( '', 'Kb', 'MB', 'GB', 'TB' );

		return round( pow( 1024, $base - floor( $base ) ), $precision ) . ' ' . $suffixes[ floor( $base ) ];
	}


function su_upload_import() {

	if ( isset( $_POST['upload'] ) ) {

		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		include_once( ABSPATH . 'wp-includes/pluggable.php' );

		if ( $_FILES['file']['name'] != '' ) {
			$uploadedfile     = $_FILES['file'];
			$upload_overrides = array( 'test_form' => false );

			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			$imageurl = "";
			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$imageurl         = $movefile['file'];

				global $wpdb;
				remove_filter( 'upload_dir', 'su_upload_dir' );

				$filename = $imageurl;

				$file = fopen( $filename, "r" );
				fgets( $file );
				while ( ( $getData = fgetcsv( $file, 10000, "," ) ) !== false ) {

					$sql = "REPLACE into " . $wpdb->prefix . "postmeta (meta_id,post_id,meta_key,meta_value) values ('" . $getData[0] . "','" . $getData[1] . "','" . $getData[3] . "','" . $getData[4] . "');";
					// echo $sql;
					$wpdb->query( $sql );

				}

				fclose( $file );
				// unlink($file);

				// echo "<script>alert('File uploaded and imported.');</script>";



			} else {
				echo $movefile['error'];
			}
		}

	}
}

	add_filter( 'init', 'su_upload_import' );
	function upload_import_success() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'File Import Complete! No errors were returned.', 'sample-text-domain' ); ?></p>
		</div>
		<?php
	}
	if(array_key_exists("upload", $_POST)) {
		if ( null !== @$_POST['upload'] ) {
			add_action( 'admin_notices', 'upload_import_success' );
		}
	}
}

?>