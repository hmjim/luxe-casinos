=== JCH Optimize ===

Contributors: codealfa
Tags: performance, pagespeed, cache, optimize, seo
Tested up to: 5.8.2
Stable tag: 3.0.3
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

This plugin automatically performs several front end optimizations to your site to increase download speed and reduce server load and bandwidth.


== Description ==

Speed up your WordPress site instantly with JCH Optimize! This plugin provides all the front end optimizations you need to optimize your website download speed. These optimizations are applied automatically by the plugin based on how it's configured and reduce both your webpage size and the number of http requests required to download your webpages and results in reduced server load, lower bandwidth requirements, and faster page loading times.


= Major Features =

* Page Cache
* Optimize CSS/JS/HTML
* GZip compress the combined files.
* Generate sprite to combine background images.
* Ability to exclude files from combining to resolve conflicts
* Defer/Load combined javascript file asynchronously
* Optimize CSS Delivery to eliminate render blocking
* CDN/Cookie-less Domain support
* Lazy load images

= How to use =

To use, first temporarily deactivate all page caching features and plugins, then use the 'Automatic Settings' (Minimum - Optimum) to configure the plugin. The 'Automatic Settings' are concerned with the combining of the CSS and javascript files, and the management of the combined files, and automatically sets the options in the 'Automatic Settings Groups'. Use the Exclude options to exclude files or plugins that don't work so well when combined with JCH Optimize. You can then try the other optimization features in turn such as Sprite Generator, Add Image Attributes, Lazy Load Images, CDN/Cookieless Domain, Optimize CSS Delivery, etc., based on the optimization needs of your site. Flush all your cache before re-enabling caching features and plugins.

= Documentation =

Visit our [documentation](https://www.jch-optimize.net/documentation.html) on the main plugin site for more information on how the plugin works and how to configure it to improve your scores on [GtMetrix](https://gtmetrix.com/) and [PageSpeed Insights](https://developers.google.com/speed/pagespeed/insights/)

= Advanced Features and Premium Support =

If you need assistance on your website in configuring the plugin to resolve any conflicts or if you need access to more advanced features such as Http/2 support, Remove unused CSS, Lazy-load iframes, Optimize Images, using multiple domains with CDN, then there's a [Pro version](https://www.jch-optimize.net/subscribe/levels.html#wordpress) available on a subscription basis. With an active subscription you get premium technical support through our ticket system, access to downloads of new versions, and access to our Optimize Image API.


== Installation ==

Just install from your WordPress "Plugins|Add New" screen. Manual installation is as follows:

1. Upload the zip-file and unzip it in the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to `Settings -> JCH Optimize` and enable the options you want
4. Use the Automatic Settings (Minimum - Optimum) to configure the plugin. This automatically sets the options in the 'Automatic Settings Groups'. You can then try the other manual options to further configure the plugin and optimize your site. Use the Exclude options to exclude files/plugins/images that don't work so well with the plugin.



== Frequently Asked Questions ==

= How does the plugin speed up your site? =

The plugin modifies the generated HTML of your website in ways that makes it faster to download and parsed by the browser. Simply put, the changes makes the webpage and resources smaller, and reduces the number of http requests the browser has to make to render the page. The result is a better user experience and higher search engine rankings.

= How do I know if it's working? =

After installing and activating the plugin, combining CSS and javascript files are selected by default so it should start working right away. If you look at your web page and it doesn't look any different that's a good sign...maybe. To confirm if it's working, take a look at the HTML page source. You can do that in most browsers by right clicking on the page and selecting that option. You should see the links to your CSS/Js files removed and replaced by the aggregated file URL in the source that looks like this:
`/wp-content/plugins/jch-optimize/assets/wp-content/plugins/gz/63fccd8dc82e3f5da947573d8ded3bd4.css`

= There's no CSS Formatting after enabling the plugin =

The combined files are accessed by the browser via a jscss.php file in the `/wp-content/plugins/jch-optimize/assets/` directory. If you're not seeing any formatting on your page it means that the browser is not accessing this file for some reason. View the source of your page and try to access the JCH generated url to the combined file in your browser. You should see an error message that can guide you in fixing the problem. Generally it's a file permission issue so ensure the file at '/wp-content/plugins/jch-optimize/assets/jscss.php` has the same permission setting as your /index.php file (usually 644) and make sure all the folders in this hierarchy have the same permissions as your htdocs or public_html folder(Usually 644).

= How do I reverse the change JCH Optimize makes to my website? =

Simply deactivate or uninstall the plugin to reverse any changes it has made. The plugin doesn't modify any existing file or code but merely manipulates the HTML before it is sent to the browser. Any apparent persistent change after the plugin is deactivated is due to caching so ensure to flush all your WordPress, third party or browser cache.


== Changelog ==

= 3.0.3 =
* Bug Fix: Fixed bug with Exclude javascript settings
* Added setting to disable plugin for logged in users

= 3.0.2 =
* Bug Fix: Fix issue with sites using FTP to upgrade

= 3.0.1 =
* Bug Fix: Fix PHP error when using Page Cache on some sites

= 3.0.0 =
* Complete redo of Admin Configuration page to be more user-friendly and aesthetic.
* Added Remove javascript/Remove CSS setting.
* Added Utility feature to generate new cache hash.
* Added Smart Combine feature. [Pro version only]
* Added Reduce DOM feature. [Pro version only]
* Using icon images instead of font icons on action buttons on dashboard.

= 2.10.0 =
* New Feature: Added admin menu item to delete cache.
* Improvement: When Lazyload image is enabled, hidden images are also only loaded when they become visible.
* Improvement: Added support for older browsers that don't use WEBP images.
* Improvement: Recursing into subfolders while using the Manual Optimize Image section is now optional.
* Improvement: Urls in srcsets are now being processed when using Automatic Optimize Image option and are also now being converted to WEBP images where necessary.
* Other minor bug fixes and improvements.

= 2.9.0 =
* New Feature: Support for webp images in teh Optimize Image Feature. [Pro version]
* New Feature: Utility options to restore optimized images and to delete folder with backup images.
* Improvements: Improved implementation of http/2.
* Improvements: Improved implementation of Optimize CSS Delivery to determine HTML above the fold particularly with large number of menu items
* Other minor bug fixes and improvements.

= 2.8.0 =
* New Feature: Basic settings for Http2 feature now available in Free version.
* New Feature: Optimize Google Fonts feature to speed up the loading of Google Fonts. [Pro version]
* New Feature: Ability to add files to the Http2 Push if critical files are being loaded dynamically so cannot normally be accessed by the plugin. [Pro Version]
* New Feature: Added preconnect resource hints settings for CDN domains.
* Improvements: Improved method to generate key hash for Optimize CSS Delivery to avoid incorrect critical CSS being loaded on page, so improving Cumulative layout shift across pages.
* Other minor bug fixes and improvements.

= 2.7.0 =
* Improvements: Dynamic CSS Selectors will add CSS to critical CSS even if Remove Unused CSS is disabled.
* Improvements: In the Http/2 push feature, the plugin will now only push woff files or woff2 files instead if present. This avoids pushing font files that are not being used.
* New Feature: Added settings to Lazy-load background images and Audio and Video elements. [Pro Version]
* New Feature: Added settings to add files loaded over CDN to the Link header, and to exclude files in the Http/2 Push feature. [Pro Version]
* New Feature: Added platform specific caching. [Pro Version]
* Other minor bug fixes and improvements.

= 2.6.2 =
* Bug fix. Error in combined CSS files caused by media type in file being different to type in media attribute on the LINK tag.
* Bug Fix: Lazyloading images with srcset attributes broke W3C HTML validation.
* Big Fix: PHP Notice in profiler.php
* Improvements: Organized settings in fieldsets on Settings page
* Improvements: Add Image Attributes will ignore images with both height and width attributes present. If one attribute is present the other will be added based on aspect ratio.
* Improvements: Page cache lifetime setting and handling separated from combined files.

= 2.6.1 =
* Bug fix: Combined files delivery using PHP files were broken

= 2.6.0 =
* New feature: Option to remove unused CSS. This is added as an additional setting in the Optimize CSS Delivery feature. [PRO VERSION]
* New feature: Setting to disable plugin for logged in users in Miscellaneous Settings on Combine CSS/JS tab.
* Improvement: Will now generate different hash for multiple combined files. This will help to reduce build-up of cache.
* Improvement: All excluded and combined javascript files are placed at bottom of page with Premium/Optimum setting.
* Improvement: Add Image Attributes feature now ignores img elements with existing width and height attributes. If one attribute is found the other will be added using aspect ratio of image.

= 2.5.2 =
* Bug fix: Add image attributes will use the same type of delimiter for width/height as that used around the url to avoid potential issues
* Bug Fix: Validate HTML before processing to avoid problems.

= 2.5.1 =
* Bug fix: PHP error in html.php file
* Bug fix: Occasionally shows blank page while using Page Cache

= 2.5.0 =
* Changes to the settings admin page and availability of features
* Bux fixes and code improvements

= 2.4.2 =
* Minor bug fixes and improvements
* Added option to autosize images in Lazyload [PRO VERSION]
* Load CSS file asynchronously using preload attribute instead of javascript in Optimize CSS Delivery [PRO VERSION]
* Fixed bug in Optimize Image not working on Safari [PRO VERSION]

= 2.4.1 =
* Improved compatibility with page caching and third party plugins
* Fixed bug in HTML Minifier library
* Fixed issue with font not showing correctly on some sites
* Fixed bug in Lazy-load feature [PRO VERSION]

= 2.4.0 =
* Minor bug fixes and improvements
* Improved efficiency of caching policy of static assets
* Added Http/2 push feature [PRO VERSION]
* Added support for srcsets and iframe to Lazyload images feature [PRO VERSION] 
* Removed font-face from combined CSS file when Optimize CSS Delivery is enabled [PRO VERSION]

= 2.3.2 =
* Fixed issue with plugin not running on some sites with last version
* Added ability to mark and skip images already optimized in subfolders [PRO VERSION]
* Fixed issue with auto-update of PRO version reverting to FREE version [PRO VERSION]

= 2.3.1 =
* Fixed issue in page cache causing PHP errors

= 2.3.0 =
* Added page cache feature
* Improved support for LiteSpeed Cache
* Other minor bug fixes and improvements.

= 2.2.3 =
* Minor bug fixes and improvement

= 2.2.2 =
* Improved caching to reduce instances of excess cache.
* Fixed issue with xml sitemaps when 'Debug plugin' is enabled.
* Fixed issue with deprecating PHP error using the 'each' function.
* Added minifier for json
* Other bug fixes and improvements.

= 2.2.1 =
* Fixed bug with exclude settings not being saved

= 2.2.0 =
* Expired cache flushed daily
* Codes added to .htaccess file to gzip compress files
* Major improvement to Optimize Image feature handling more images much more efficiently (PRO VERSION)
* Various bug fixes and improvement

= 2.1.0 =
* Ability to exclude files while maintaining original execution order for all Automatic Settings added.
* Ability to select static files for combined css and js files added.
* Cache lifetime hardcoded to 1 day and setting removed.
* 'Exclude javascript dynamically' setting removed.
* Ability to select file type for each CDN domain added.(PRO VERSION)
* CDN feature will use base element to determine the base url for relative urls.(PRO VERSION)
* Automatically exclude images above the fold from Lazy-load feature to avoid css render-blocking issues.(PRO VERSION)
* Improvements in the Optimize CSS Delivery feature.(PRO VERSION)
* Various bug fixes and improvements.

= 2.0.8 =
* Fixed bug creating errors in JchOptimizeSettings
* Removed some exclusion settings
* Fix javascript error in options page
* Other minor bug fixes

= 2.0.7 =
* Fixed conflicts with select plugins that cause JCH Optimize to generate a Fatal Error
* Removed cache lifetime setting. Lifetime hardcoded to 1 day
* Other minor bug fixes and improvement

= 2.0.6 =
* Fix issue with the plugin not running on some sites
* Now Compatible with Google AMP pages
* Added setting to exclude pages from the plugin that don't work well or you don't want optimized
 
= 2.0.5 =
* Couple bug fixes from the last version

= 2.0.4 =
* Improved compatibility with PHP7
* Improved support for Google font files
* Fixed issue with script that flushes expired cache daily
* Other minor fixes and improvements.

= 2.0.3 =
* Fixed bug that was causing some javascript errors in some browsers on some sites.

= 2.0.2 =
* Fixed bug with handling Google font files
* Grouped settings related to the combine CSS/javascript feature together to make it more intuitive to configure and added setting to disable/enable this feature
* Added feature to add missing height and width attributes to img elements
* Fixed bug with lazy-load feature that was affecting other javascript libraries
* Other minor bug fixes and improvements

= 2.0.1 = 
* Fixed issue with CSS Optimize library that caused some pages to load slowly

= 2.0.0 =
* The settings in the backend are rearranged in a more logical and intuitive manner
* Support for up to 3 CDN/Cookieless domains and the ability to select the file type to load over CDN
* Exclude images from Lazy Load based on the folder (useful if you want to exclude all images from an extension), or by the CSS class defined on the image
* Improved compatibility with slideshows and ajax content with the LazyLoad function and also support for non-javascript users (probably some mobile)
* Ability to remove files from loading on the page for eg., if you have more than one jQuery libraries or libraries you're not using like Mootools.
* Psuedo-cron script that flush expired cache daily to reduce the build up of excess cache
* Support for those pesky Google font files that are always blocking on PageSpeed
* Option to 'Leverage Browser Cache' for common resource files.
* Option to correct permissions of files/folders in plugin.
* Added functionality to recursively optimize images in subfolders
* Can scale images during optimization if image dimensions are larger than required.
* Optimized/resized images will be automatically backed up in a folder.
* Developed our own API for optimizing images so we'll no longer be using Kraken.io
* Added language translations for Spanish, French, Russian, German, and Hebrew
* Other improvements to existing features and various bug fixes.

= 1.2.2 =
* Fixed issue in validating HTML that prevented the plugin running on some sites.

= 1.2.1 =
* Fix links to combined file to include scheme and domain for better compatibility with other plugins
* Improved code that manipulates urls in the plugins

= 1.2.0 =
* Fixed bug in Autoloader function that conflicts with other plugins that have classes beginning with 'JCH'
* Fixed bug with HTML Minify removing spaces from inside pre elements when it contains other HTML elements
* Fixed compatibility issue with plugins using PHP internal buffering eg. CDN Linker, cache plugins, etc.
* Will delete plugin options on uninstall
* Multisite supported
* Fixed issue with Optimize Images not working with open_basedir setting (PRO VERSION)
* Now able to automatically update the Pro version when your download id is saved in the plugin (PRO VERSION)

= 1.1.4 =
* Improved method of accessing HTML for optimization considering levels of buffering
* Corrected function used to access home url in backend so that exclude options lists can be populated
* Fixed bug in and improved HTML minification library
* Fixed bug with Sprite Generator
* Fixed bug with CDN/Cookie-less domain feature (PRO VERSION)
* Improved Image Optimization feature (PRO VERSION)

= 1.1.3 =
* Fixed issue with the setting 'Use url rewrite - Yes (Without Options+SynLinks)' not working properly
* Fixed issue with combine javascript options sometimes creates javascript errors
* Now using Kraken.io API to optimize images (PRO VERSION)

= 1.1.2 =
* Fixed compatibility issue with XML sitemaps and feeds.
* Minor bug fixes

= 1.1.1 =
* Improved code running in admin section
* Add Profiler menu item on Admin Bar to review the times taken for the plugin methods to run.
* Keep HTML comments in 'Basic' HTML Minification level. Required for some plugins to work eg. Nextgen gallery.
* Saving cache in non-PHP files to make it compatible with  WP Engine platform.
* Minor bug fixes and improvements.

= 1.1.0 =
* Added visual indicators to show which Automatic setting is enabled
* Added multiselect exclude options so it's easier to find files/plugins to exclude from combining if they cause problems
* Bug fixes and improvements in the HTML, CSS, and javascript minification libraries
* Added levels of HTML minification

= 1.0.2 =
* Fixed bug in HMTL Minify library manifested on XHTML templates
* Fails gracefully on PHP5.2

= 1.0.1 =
* First public release on WordPress plugins repository.
