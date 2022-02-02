<?php

/**
 * Plugin Name: JCH Optimize
 * Plugin URI: http://www.jch-optimize.net/
 * Description: JCH Optimize performs several front-end optimizations to your webpages for fast downloads
 * Version: 3.0.3
 * Author: Samuel Marshall
 * License: GNU/GPLv3
 * Text Domain: jch-optimize
 * Domain Path: /languages
 */

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

$jch_no_optimize = false;

define( '_WP_EXEC', '1' );

define( 'JCH_PLUGIN_FILE', __FILE__ );
define( 'JCH_PLUGIN_URL', plugin_dir_url( JCH_PLUGIN_FILE ) );
define( 'JCH_PLUGIN_DIR', plugin_dir_path( JCH_PLUGIN_FILE ) );
define( 'JCH_CACHE_DIR', WP_CONTENT_DIR . '/cache/jch-optimize/' );

require_once( JCH_PLUGIN_DIR . 'autoload.php' );
require_once( JCH_PLUGIN_DIR . 'version.php' );
require_once( JCH_PLUGIN_DIR . 'classes/JchOptimizeLoader.php' );

/**
 * Upgrade settings from versions less than 3.0.0
 */
JchOptimizeLoader::preboot_init();

/**
 * Initialize and run plugin
 */
JchOptimizeLoader::init();
