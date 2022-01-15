<?php
/*
Plugin Name: SEO Ultimate Pro
Plugin URI: https://www.seoultimatepro.com/
Description: This SEO Ultimate Pro plugin gives you control over title tags, noindex/nofollow, meta tags, opengraph+, slugs, canonical tags, autolinks, 404 errors, rich snippets, and more.
Version: 1.0.7
Author: SEO Design Solutions
Author URI: https://www.seoultimatepro.com/
Text Domain: seo-ultimate-pro
*/
/**
 * The main SEO Ultimate Pro plugin file.
 * @package SeoUltimatePro
 * @version 1.0.7
 * @link https://www.seoultimatepro.com SEO Ultimate Pro Homepage
 */
/*
Copyright (c) 2018 SEO Design Solutions
*/

remove_action('shutdown', 'wp_ob_end_flush_all', 1);
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
			if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
				$link = "https://";
			} else {
				$link = "http://";
			}
define( 'SU_STORE_URL', 'https://www.seoultimatepro.com' );
define( 'EDD_SL_ITEM_ID', 2328 );
define( 'SU_PLUGIN_LICENSE_PAGE', 'seoupro-license' );
define( 'SU_ITEM_NAME', 'SEO Ultimate Pro Core' );
define('SU_FILENAME', "URI_NOW.conf");
define('PLUGIN_INSTALL_URL',plugins_url());
define( 'SU_PLUGIN_ROOT', plugins_url('', __FILE__ ) );
define( 'SU_PLUGIN_DIR', dirname(__FILE__));
if( !class_exists( 'SU_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/SU_Update.php' );
}
function su_sl_plugin_updater() {
	$license_key = trim( get_option( 'su_license_key' ) );
	$su_updater = new SU_SL_Plugin_Updater( SU_STORE_URL, __FILE__,
		array(
			'version' => '1.0.7',
			'license' => $license_key,
			'item_id' => EDD_SL_ITEM_ID,
			'author'  => 'SEO Design Solutions',
			'beta'    => false,
		)
	);
}
add_action( 'admin_init', 'su_sl_plugin_updater', 0 );
/************************************
* the code below is just a standard
* options page. Substitute with
* your own.
*************************************/
function su_license_menu() {
	add_plugins_page( 'SEO Ultimate Pro License', 'SEO Ultimate Pro License', 'manage_options', SU_PLUGIN_LICENSE_PAGE, 'su_license_page' );
}

add_action('admin_menu', 'su_license_menu');

if (!file_exists(SU_FILENAME)) {
    file_put_contents(SU_FILENAME, $link . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}
function su_license_page() {
	$license = get_option( 'su_license_key' );
	$status  = get_option( 'su_license_status' );
	?>
	<div class="wrap">
		<h2><?php _e('SEO Ultimate Pro License'); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('su_license'); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key'); ?>
						</th>
						<td>
							<input id="su_license_key" name="su_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<hr><i style="color: red;" class="fa fa-exclamation-triangle"></i> <?php _e('If this is your first time activating SEO Ultimate Pro, or the license key field is empty, you must save the license key first. After you have saved the license key, you may then use the Activate License button.'); ?>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License'); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e('License is Active'); ?> <a class="btn btn-primary" href="admin.php?page=seo">Click To Access SEO Ultimate Pro</a></span><br><br>
									<?php wp_nonce_field( 'su_nonce', 'su_nonce' ); ?>
									<input type="submit" class="button-secondary" name="su_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
								<?php } else {
									wp_nonce_field( 'su_nonce', 'su_nonce' ); ?>
									<input type="submit" class="button-secondary" name="su_license_activate" value="<?php _e('Activate License'); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
	<?php
}
function su_register_option() {
	// creates our settings in the options table
	register_setting('su_license', 'su_license_key', 'su_sanitize_license' );
}
add_action('admin_init', 'su_register_option');
function su_sanitize_license( $new ) {
	$old = get_option( 'su_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'su_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}
/************************************
* this illustrates how to activate
* a license key
*************************************/
function su_activate_license() {
	// listen for our activate button to be clicked
	if( isset( $_POST['su_license_activate'] ) ) {
		// run a quick security check
	 	if( ! check_admin_referer( 'su_nonce', 'su_nonce' ) )
			{return;} // get out if we didn't click the Activate button
		// retrieve the license from the database
		$license = trim( get_option( 'su_license_key' ) );
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( SU_ITEM_NAME ),
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( SU_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( false === $license_data->success ) {
				switch( $license_data->error ) {
					case 'expired' :
						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;
					case 'disabled' :
					case 'revoked' :
						$message = __( 'Your license key has been disabled.' );
						break;
					case 'missing' :
						$message = __( 'Invalid license.' );
						break;
					case 'invalid' :
					case 'site_inactive' :
						$message = __( 'Your license is not active for this URL.' );
						break;
					case 'item_name_mismatch' :
						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), SU_ITEM_NAME );
						break;
					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.' );
						break;
					default :
						$message = __( 'An error occurred, please try again.' );
						break;
				}
			}
		}
		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'plugins.php?page=' . SU_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
			wp_redirect( $redirect );
		}
		// $license_data->license will be either "valid" or "invalid"
		update_option( 'su_license_status', $license_data->license );
		wp_redirect( admin_url( 'plugins.php?page=' . SU_PLUGIN_LICENSE_PAGE ) );
		exit();
	}
}
add_action('admin_init', 'su_activate_license');
/***********************************************
* Illustrates how to deactivate a license key.
* This will decrease the site count
***********************************************/
function su_deactivate_license() {
	// listen for our activate button to be clicked
	if( isset( $_POST['su_license_deactivate'] ) ) {
		// run a quick security check
	 	if( ! check_admin_referer( 'su_nonce', 'su_nonce' ) )
			{return;} // get out if we didn't click the Activate button
		// retrieve the license from the database
		$license = trim( get_option( 'su_license_key' ) );
		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( SU_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);
		// Call the custom API.
		$response = wp_remote_post( SU_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}
			$base_url = admin_url( 'plugins.php?page=' . SU_PLUGIN_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );
			wp_redirect( $redirect );
			exit();
		}
		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( 'su_license_status' );
			delete_transient("seouproval");
		}
		wp_redirect( admin_url( 'plugins.php?page=' . SU_PLUGIN_LICENSE_PAGE ) );
		exit();
	}
}
add_action('admin_init', 'su_deactivate_license');
function su_check_license() {
	global $wp_version;
        $su_results= 1;

	$su_results = get_transient( 'seouproval' );
	if($su_results != 1) {
	$license = trim( get_option( 'su_license_key' ) );
	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( SU_ITEM_NAME ),
		'url'       => home_url()
	);
	// Call the custom API.
	$response = wp_remote_post( SU_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
	if ( is_wp_error( $response ) )
		{return false;}
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	if( $license_data->license != 'valid' ) {
	    set_transient( "seouproval", "1", 60*60*12 );
	    // set_Includes();
	} else {
        delete_transient("seouproval");
    				        if(is_admin()) {
    				            if( isset($_GET['page']) && $_GET['page'] != "seoupro-license") {
		$class = 'notice notice-error';
	$message = __( 'SEO Ultimate Pro License is not active or invalid. Please visit the license page, by clicking <a href="' . admin_url( 'plugins.php?page=' . SU_PLUGIN_LICENSE_PAGE ) . '">here</a>','seo-ultimate-pro' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}
	}
	}
	}
}
/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function su_admin_notices() {
	if ( isset( $_GET['sl_activation'] ) && ! empty( $_GET['message'] ) ) {
		switch( $_GET['sl_activation'] ) {
			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;
			case 'true':
			default:
				echo '<div class="error"><p>';
	printf( __( 'SEO Ultimate Pro License Has Been Activated Successfully.'));
	echo "</p></div>\n";
				break;
		}
	}
}
add_action( 'admin_notices', 'su_admin_notices' );
$su_results = "";
$su_results = get_transient( 'seouproval' );
	if($su_results == 1) {
    //The bare minimum version of WordPress required to run without generating a fatal error.
//SEO Ultimate will refuse to run if activated on a lower version of WP.
define( 'SU_MINIMUM_WP_VER', '5.0' );
//Reading plugin info from constants is faster than trying to parse it from the header above.
define( 'SU_PLUGIN_NAME', 'SEO Ultimate Pro' );
define( 'SU_PLUGIN_URI', 'https://www.seoultimatepro.com' );
define( 'SU_VERSION', '1.0.7' );
define( 'SU_AUTHOR', 'SEO Design Solutions' );
define( 'SU_AUTHOR_URI', 'https://www.seoultimatepro.com' );
define( 'SU_USER_AGENT', 'SeoUltimatePro/1.0.7' );
    global $wp_version;
/********** INCLUDES **********/
//Libraries
include( dirname( __FILE__ ) . '/includes/jlfunctions/jlfunctions.php');
include( dirname( __FILE__ ) . '/includes/jlwp/jlwp.php');
//Plugin files
include( dirname( __FILE__ ) . '/includes/su-constants.php');
include( dirname( __FILE__ ) . '/includes/su-functions.php');
include( dirname( __FILE__ ) . '/includes/class.seo-ultimate-plus.php');
//Module files
include( dirname( __FILE__ ) . '/modules/class.su-module.php');
include( dirname( __FILE__ ) . '/modules/class.su-importmodule.php');
/********** VERSION CHECK & INITIALIZATION **********/

if ( version_compare( $wp_version, SU_MINIMUM_WP_VER, '>=' ) ) {
	global $seo_ultimate_plus;
	$seo_ultimate_plus = new SEO_Ultimate_Plus( __FILE__ );
} else {
		echo '<div class="error"><p>';
	printf( __( 'SEO Ultimate Pro requires WordPress %s or above. Please upgrade to the latest version of WordPress to enable SEO Ultimate on your blog, or deactivate SEO Ultimate to remove this notice.', 'seo-ultimate-pro' ), SU_MINIMUM_WP_VER );
	echo "</p></div>\n";
}
} else {
	    add_action( 'init', 'su_check_license' );
}

function su_admin_scripts() {
			wp_enqueue_script( 'jtools', plugin_dir_url( __FILE__ ) . 'assets/js/jtools.js', array(), '1.0.9', true );
			wp_enqueue_script( 'schemasort', plugin_dir_url( __FILE__ ) . 'assets/js/schema.js', array(), '1.0.9', true );
}
add_action( 'admin_enqueue_scripts', 'su_admin_scripts' );