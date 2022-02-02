<?php

/**
 * JCH Optimize - Performs several front-end optimizations for fast downloads
 *
 * @package   jchoptimize/core
 * @author    Samuel Marshall <samuel@jch-optimize.net>
 * @copyright Copyright (c) 2020 Samuel Marshall / JCH Optimize
 * @license   GNU/GPLv3, or later. See LICENSE file
 *
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>.
 */

namespace JchOptimize\Core\Admin\Ajax;

use JchOptimize\Core\Admin\Helper as AdminHelper;
use JchOptimize\Core\Combiner;
use JchOptimize\Core\Html\FilesManager;
use JchOptimize\Core\Webp;
use JchOptimize\Platform\FileSystem;
use JchOptimize\Core\Url;
use JchOptimize\Core\Helper;
use JchOptimize\Core\Html\Processor as HtmlProcessor;
use JchOptimize\Core\Admin\ImageUploader;
use JchOptimize\Core\Admin\Json;
use JchOptimize\Core\Logger;
use JchOptimize\Platform\Html;
use JchOptimize\Platform\Paths;
use JchOptimize\Platform\Plugin;
use JchOptimize\Platform\Utility;

defined( '_JCH_EXEC' ) or die( 'Restricted access' );

class OptimizeImage extends Ajax
{
	static public $backup_folder_name = 'jch_optimize_backup_images';

	/**
	 * @deprecated
	 */
	public static function getOptimizedFiles()
	{
		return AdminHelper::getOptimizedFiles();
	}

	/**
	 *
	 * @param   string  $src
	 * @param   string  $dest
	 *
	 * @return bool|false|int
	 * @deprecated
	 */
	public static function copy( $src, $dest )
	{
		return AdminHelper::copyImage( $src, $dest );
	}

	/**
	 * @param $file
	 *
	 * @deprecated
	 */
	protected static function markOptimized( $file )
	{
		AdminHelper::markOptimized( $file );
	}

	/**
	 *
	 * @param   string  $file
	 *
	 * @depecated
	 * @return string|string[]|null
	 */
	protected static function incrementBackupFileName( $file )
	{
		$backup_file = preg_replace_callback( '#(?:(_)(\d++))?(\.[^.\s]++)$#',
			function ( $m ) {
				$m[1] = $m[1] == '' ? '_' : $m[1];
				$m[2] = $m[2] == '' ? 0 : (int)$m[2];

				return $m[1] . (string)++$m[2] . $m[3];
			}, rtrim( $file ) );

		if ( @file_exists( $backup_file ) )
		{
			$backup_file = self::incrementBackupFileName( $backup_file );
		}

		return $backup_file;
	}

	/**
	 *
	 * @return Json
	 */
	public function run()
	{
		error_reporting( 0 );

		$root = Paths::rootPath();

		set_time_limit( 0 );

		//Package of files being sent to be optimized
		$aFilePack = Utility::get( 'filepack', array(), 'array' );
		//Selected subdirectories from the folder tree
		$aSubDirs = Utility::get( 'subdirs', array(), 'array' );
		//Relevant plugin parameters captured by javascript and sent via ajax
		$params = (object)Utility::get( 'params', array(), 'array' );
		//(optimize|getfiles) Whether we're gathering the files to be packaged or
		//we're sending the packages of files to be optimized
		$task     = Utility::get( 'optimize_task', '0', 'string' );
		$sApiMode = Utility::get( 'api_mode', 'auto', 'string' );

		//First task is to get and return an array of image files in the subdirectories that were selected to be optimized
		if ( $task == 'getfiles' )
		{
			$files = array();

			if ( $sApiMode == 'auto' )
			{
				$files = $this->getImageFilesFromUrls();
			}
			elseif ( ! empty( $aSubDirs ) )
			{
				if ( count( array_filter( $aSubDirs ) ) )
				{
					foreach ( $aSubDirs as $sSubDir )
					{
						//$subdir = rtrim(Utility::decrypt($subdir), '/\\');
						$sSubDir = rtrim( $sSubDir, '/\\' );
						$files   = array_merge( $files, $this->getImageFiles( $root . $sSubDir, $params->recursive ) );
					}
				}
			}

			if ( $sApiMode == 'manual' && ! empty( $files ) )
			{
				//Remove optimized files from the array if option is set
				if ( $params->ignore_optimized )
				{
					$files = array_diff( $files, AdminHelper::getOptimizedFiles() );
				}

				//Limit number of files optimized at any time to 10,000
				$files = array_slice( $files, 0, 10000 );

				$files = array_map( function ( $v ) {
					return AdminHelper::prepareImageUrl( $v );
				}, $files );

				$files = array_values( $files );
			}

			$data = array(
				'files'    => $files,
				'log_path' => Utility::getLogsPath()
			);

			return new Json( $data );
		}

		$options = array(
			"files"       => array(),
			"lossy"       => true,//(bool) $params->kraken_optimization_level
			"resize"      => array(),
			"resize_mode" => 'manual',
			"webp"        => $params->pro_next_gen_images,
			"url"         => ''
		);


		if ( $sApiMode == 'manual' )
		{ //Iterate through the packet of files
			foreach ( $aFilePack as $file )
			{
				//Populate the files array with the file names
				//$filepath = rtrim(Utility::decrypt($file['path']), '/\\');
				$filepath           = rtrim( $file['path'], '/\\' );
				$options['files'][] = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $filepath );

				//If resize dimensions are specified, save them in resize array using file path as index
				if ( ! empty( $file['width'] ) || ! empty( $file['height'] ) )
				{
					$filename                                 = AdminHelper::contractFileNameLegacy( $filepath );
					$options['resize'][ $filename ]['width']  = (int)( ! empty( $file['width'] ) ? $file['width'] : 0 );
					$options['resize'][ $filename ]['height'] = (int)( ! empty( $file['height'] ) ? $file['height'] : 0 );
				}

			}
		}
		else
		{
			$options['files']       = $aFilePack['images'];
			$options['url']         = $aFilePack['url'];
			$options['resize_mode'] = $params->pro_api_resize_mode ? 'auto' : 'manual';
		}

		$oJchio = new ImageUploader( $params->pro_downloadid, $params->hidden_api_secret );

		$message = '';
		$return  = array();
		$data    = null;

		try
		{
			//return an array of responses in the data property
			$responses = $oJchio->upload( $options );

			//Check if response is formatted properly
			if ( ! isset( $responses->success ) )
			{
				$this->logMessage( $responses, 'Server error' );

				throw new \Exception( 'Unrecognizable response from server:' . $responses, 500 );
			}

			//Handle responses that are exceptions (ie, codes 403, 500)
			if ( ! $responses->success )
			{
				$this->logMessage( $responses->message );

				throw new \Exception( $responses->message, $responses->code );
			}

			//Iterate through the array of data
			foreach ( $responses->data as $i => $response )
			{
				$datas = array();

				$original_file = $options['files'][ $i ];
				$message       = $original_file . ': ';

				//Check if file was successfully optimized
				if ( $response[0]->success )
				{
					//Save backup of file
					$backup_file = self::getBackupFilename( $original_file );

					if ( ! @file_exists( $backup_file ) )
					{
						AdminHelper::copyImage( $original_file, $backup_file );
					}

					//Copy optimized file over original file
					if ( AdminHelper::copyImage( $response[0]->data->kraked_url, $original_file ) )
					{
						$message .= 'Optimized! You saved ' . $response[0]->data->saved_bytes . ' bytes.';
						AdminHelper::markOptimized( $original_file );
					}
					else
					{
						//If copy failed
						$message .= 'Could not copy optimized file.';
						$data    = new \Exception( $message, 404 );
					}

				}
				else
				{
					//File cannot be optimized further
					if ( $response[0]->code == 304 )
					{
						AdminHelper::markOptimized( $original_file );
					}

					//If file wasn't optimized format response accordingly
					$message .= $response[0]->message;
					$data    = new \Exception( $message, $response[0]->code );
				}

				//Format each response
				$data = new Json( $data, $message );

				self::logMessage( $data->message );

				//Save each response in the response array
				$datas[] = $data;

				if ( isset( $response[1] ) )
				{
					$webp_message = '&ensp;└───────────────>  ';
					$webp_data    = null;

					if ( $response[1]->success )
					{
						$new_file = Webp::getWebpPath( $original_file );

						if ( @file_exists( $new_file ) )
						{
							$webp_data = new \Exception( $webp_message . 'Webp format already exists!' );
						}
						else
						{
							if ( AdminHelper::copyImage( $response[1]->data->webp_url, $new_file ) )
							{
								$webp_message .= 'Converted to webp! You saved ' . $response[1]->data->webp_savings . ' more bytes';
								//If this file wasn't backed up before, save a backup now to facilitate restoration
								$backup_file = self::getBackupFilename( $original_file );

								if ( ! @file_exists( $backup_file ) )
								{
									AdminHelper::copyImage( $original_file, $backup_file );
								}

							}
							else
							{
								$webp_message .= 'Could not save the webp version of file!';
								$webp_data    = new \Exception( $webp_message );
							}
						}
					}
					else
					{
						$webp_message .= $response[1]->message;
						$webp_data    = new \Exception( $webp_message, $response[1]->code );
					}

					$webp_data = new Json( $webp_data, $webp_message );

					self::logMessage( $webp_data->message );

					$datas[] = $webp_data;
				}

				$return[] = $datas;
			}
		}
		catch ( \Exception $e )
		{
			//Save exceptions to datas variable in place of array.
			$return = $e;

			self::logMessage( $e->getMessage() );
		}

		clearstatcache();

		//Format Ajax response
		return new Json( $return );
	}

	protected function getImageFilesFromUrls()
	{
		$aFiles        = array();
		$oPluginParams = Plugin::getPluginParams();
		$oParams       = clone $oPluginParams;
		$oHtml         = new Html( $oParams );

		try
		{
			$aHtmlArray = $oHtml->getMainMenuItemsHtmls( null, true );
		}
		catch ( \Exception $e )
		{
			Logger::log( $e->getMessage(), $oPluginParams );
			$aHtmlArray = array();
		}

		$oParams->set( 'combine_files_enable', '1' );
		$oParams->set( 'pro_smart_combine', '0' );
		$oParams->set( 'javascript', '0' );
		$oParams->set( 'css', '1' );
		$oParams->set( 'css_minify', '0' );
		$oParams->set( 'excludeCss', [] );
		$oParams->set( 'excludeCssComponents', [] );
		$oParams->set( 'replaceImports', '1' );
		$oParams->set( 'phpAndExternal', '1' );
		$oParams->set( 'inlineScripts', '1' );
		$oParams->set( 'cookielessdomain_enable', '0' );
		$oParams->set( 'optimizeCssDelivery_enable', '0' );
		$oParams->set( 'csg_enable', '0' );

		foreach ( $aHtmlArray as $aHtml )
		{
			$oHtmlProcessor = new HtmlProcessor( $aHtml['html'], $oParams );
			$aHtmlMatches   = $oHtmlProcessor->processImagesForApi();
			$aHtmlImages    = array();

			foreach ( $aHtmlMatches as $aMatch )
			{
				if ( $aMatch[1] == 'img' )
				{
					$aHtmlImages[] = $aMatch[4];

					if ( ! empty( $aMatch[5] ) )
					{
						$aSrcsetUrls = Helper::extractUrlsFromSrcset( $aMatch[7] );

						foreach ( $aSrcsetUrls as $sSrcset )
						{
							$aHtmlImages[] = $sSrcset;
						}
					}
				}
				else
				{
					$aHtmlImages[] = $aMatch[6];
				}

			}

			$oHtmlProcessor->processCombineJsCss();
			$aCssLinks  = FilesManager::getInstance( $oParams )->aCss;
			$oCombiner  = new Combiner( $oParams );
			$aResult    = $oCombiner->combineFiles( $aCssLinks[0], 'css' );
			$aCssImages = array_unique( $aResult['images'] );

			$aImages = array_merge( $aHtmlImages, $aCssImages );

			$aImages = array_map( function ( $a ) {
				return Helper::getFilePath( $a );
			}, $aImages );

			$aImageUrls = array_merge( ...array_column( $aFiles, 'images' ) );

			$aImages = array_filter( $aImages, function ( $a ) use ( $aImageUrls, $oParams ) {
				return Url::isInternal( $a, $oParams )
					&& preg_match( '#\.(?:jpe?g|png|gif)(?:[?\#]|$)#i', $a )
					&& ! in_array( $a, $aImageUrls )
					&& @file_exists( $a );
			} );

			//If option set, remove images already optimized
			if ( $oParams->get( 'ignore_optimized', '1' ) )
			{
				$aImages = array_diff( $aImages, AdminHelper::getOptimizedFiles() );
			}

			if ( empty( $aImages ) )
			{
				continue;
			}

			$aImages = array_values( array_unique( $aImages ) );

			$aImageChunks = $this->packageImages( $aImages );

			foreach ( $aImageChunks as $aImageChunk )
			{
				$aFiles[] = array(
					'images' => $aImageChunk,
					'url'    => $aHtml['url']
				);
			}
		}

		return $aFiles;
	}

	protected function packageImages( $aImages )
	{
		$iMaxUploadFilesize = 0.8 * AdminHelper::stringToBytes( ini_get( 'upload_max_filesize' ) );
		$iMaxFileUploads    = 0.8 * ini_get( 'max_file_uploads' );
		$aImagePackages     = array( array() );

		$iTotalFiles    = 0;
		$iTotalFileSize = 0;
		$iPackageNumber = 0;

		foreach ( $aImages as $sImage )
		{
			$iFileSize = filesize( $sImage );

			if ( $iFileSize > $iMaxUploadFilesize )
			{
				continue;
			}

			$iTotalFileSize += $iFileSize;
			$iTotalFiles++;

			if ( $iTotalFileSize > $iMaxUploadFilesize || $iTotalFiles > $iMaxFileUploads || $iTotalFiles > 5 )
			{
				$iTotalFiles    = 1;
				$iTotalFileSize = $iFileSize;
				$iPackageNumber++;
			}

			$aImagePackages[ $iPackageNumber ][] = $sImage;
		}

		return $aImagePackages;
	}

	/**
	 * @param   string  $dir
	 * @param   bool    $recursive
	 *
	 * @return array
	 */
	private function getImageFiles( $dir, $recursive = false )
	{
		$excludes = array( self::$backup_folder_name );

		//Returns an array of full paths of files in the directory (recursively)?
		return FileSystem::lsFiles( $dir, '\.(?:gif|jpe?g|png|GIF|JPE?G|PNG)$', $recursive, $excludes );
	}

	protected function logMessage( $message, $type = 'INFO' )
	{
		try
		{
			Logger::logInfo( $message );
		}
		catch ( \Exception $e )
		{

		}
	}

	/**
	 *
	 * @param   string  $file
	 *
	 * @return string
	 * @throws \Exception
	 */
	protected function getBackupFilename( $file )
	{
		$backup_parent_dir = Paths::backupImagesParentDir();

		return $backup_parent_dir . self::$backup_folder_name . '/' . AdminHelper::contractFileName( $file );
	}
}