<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/wordpress-platform
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

class JchOptimizeUpdater
{

	private $downloadid = '';

	private $updateinfo = '';

	/**
	 * Class constructor
	 *
	 *
	 * $param string $downloadid Download ID
	 *
	 * @return null
	 */
	public function __construct( $downloadid )
	{
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'filterTransient' ) );

		$this->downloadid = $downloadid;
	}

	/**
	 * Update transient with information for automatic pro update
	 *
	 *
	 * @param   object  $transient
	 *
	 * @return object
	 */
	public function filterTransient( $transient )
	{
		if ( empty( $this->downloadid ) || ! $this->queryUpdateSite() )
		{
			return $transient;
		}

		$updateversion = (string)$this->updateinfo->version;
		$downloadurl   = (string)$this->updateinfo->downloads->downloadurl;

		$downloadurl = add_query_arg( array( 'dlid' => $this->downloadid ), $downloadurl );

		//Check if there's a newer version to the current version installed
		$doupdate = version_compare( $updateversion, str_replace( 'pro-', '', JCH_VERSION ), '>' );

		if ( $doupdate )
		{//Insert the transient for the new version
			$obj = new stdClass();

			$obj->slug        = 'jch-optimize';
			$obj->plugin      = 'jch-optimize/jch-optimize.php';
			$obj->new_version = $updateversion;
			$obj->url         = (string)$this->updateinfo->infourl;
			$obj->package     = $downloadurl;

			$transient->response['jch-optimize/jch-optimize.php'] = $obj;

			unset( $transient->no_update['jch-optimize/jch-optimize.php'] );
		}

		return $transient;
	}

	/**
	 * Get update information from our update site
	 *
	 *
	 * @return null
	 */
	private function queryUpdateSite()
	{
		$return = false;
		//update site
		$url = 'https://updates.jch-optimize.net/wordpress-pro.xml';

		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) && 200 == (int)wp_remote_retrieve_response_code( $response ) )
		{
			//Should return an xml document containing the update information
			$oXml = simplexml_load_string( wp_remote_retrieve_body( $response ) );

			if ( $oXml instanceof SimpleXMLElement )
			{
				//Get the most recent update site in the document
				$this->updateinfo = $oXml->update;
				$return           = true;
			}
		}

		return $return;
	}
}
