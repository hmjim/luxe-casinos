<?php
/**
 * Non-class functions.
 */

/********** INDEPENDENTLY-OPERABLE FUNCTIONS **********/

/**
 * Returns the plugin's User-Agent value.
 * Can be used as a WordPress filter.
 *
 * @since 0.1
 * @uses SU_USER_AGENT
 *
 * @return string The user agent.
 */
function su_get_user_agent() {
	return SU_USER_AGENT;
}

/**
 * Records an event in the debug log file.
 * Usage: su_debug_log(__FILE__, __CLASS__, __FUNCTION__, __LINE__, "Message");
 *
 * @since 0.1
 * @uses SU_VERSION
 *
 * @param string $file The value of __FILE__
 * @param string $class The value of __CLASS__
 * @param string $function The value of __FUNCTION__
 * @param string $line The value of __LINE__
 * @param string $message The message to log.
 */
function su_debug_log( $file, $class, $function, $line, $message ) {
	global $seo_ultimate_plus;
	if ( isset( $seo_ultimate_plus->modules['settings'] ) && $seo_ultimate_plus->modules['settings']->get_setting( 'debug_mode' ) === true ) {

		$date    = date( "Y-m-d H:i:s" );
		$version = SU_VERSION;
		$message = str_replace( "\r\n", "\n", $message );
		$message = str_replace( "\n", "\r\n", $message );

		$log     = "Date: $date\r\nVersion: $version\r\nFile: $file\r\nClass: $class\r\nFunction: $function\r\nLine: $line\r\nMessage: $message\r\n\r\n";
		$logfile = trailingslashit( dirname( __FILE__ ) ) . "seo-ultimate-pro.log";

		@error_log( $log, 3, $logfile );
	}
}

/**
 * Joins strings into a natural-language list.
 * Can be internationalized with gettext or the su_lang_implode filter.
 *
 * @since 1.1
 *
 * @param array $items The strings (or objects with $var child strings) to join.
 * @param string|false $var The name of the items' object variables whose values should be imploded into a list.
 * If false, the items themselves will be used.
 * @param bool $ucwords Whether or not to capitalize the first letter of every word in the list.
 *
 * @return string|array The items in a natural-language list.
 */
function su_lang_implode( $items, $var = false, $ucwords = false ) {

	if ( is_array( $items ) ) {

		if ( strlen( $var ) ) {
			$_items = array();
			foreach ( $items as $item ) {
				$_items[] = $item->$var;
			}
			$items = $_items;
		}

		if ( $ucwords ) {
			$items = array_map( 'ucwords', $items );
		}

		switch ( count( $items ) ) {
			case 0:
				$list = '';
				break;
			case 1:
				$list = $items[0];
				break;
			case 2:
				$list = sprintf( __( '%s and %s', 'seo-ultimate-pro' ), $items[0], $items[1] );
				break;
			default:
				$last = array_pop( $items );
				$list = implode( __( ', ', 'seo-ultimate-pro' ), $items );
				$list = sprintf( __( '%s, and %s', 'seo-ultimate-pro' ), $list, $last );
				break;
		}

		return apply_filters( 'su_lang_implode', $list, $items );
	}

	return $items;
}

/**
 * Escapes an attribute value and removes unwanted characters.
 *
 * @since 0.8
 *
 * @param string $str The attribute value.
 *
 * @return string The filtered attribute value.
 */
function su_esc_attr( $str ) {
	if ( ! is_string( $str ) ) {
		return $str;
	}
	$str = str_replace( array( "\t", "\r\n", "\n" ), ' ', $str );
	$str = esc_attr( $str );

	return $str;
}

/**
 * Escapes HTML.
 *
 * @since 2.1
 */
function su_esc_html( $str ) {
	return esc_html( $str );
}

/**
 * Escapes HTML. Double-encodes existing entities (ideal for editable HTML).
 *
 * @since 1.5
 *
 * @param string $str The string that potentially contains HTML.
 *
 * @return string The filtered string.
 */
function su_esc_editable_html( $str ) {
	return _wp_specialchars( $str, ENT_QUOTES, false, true );
}

// Add a shortcut link for admin toolbar
function seo_ultimate_plus_admin_bar_menu( $meta = true ) {
	global $wp_admin_bar, $seo_ultimate_plus;
	if ( ! is_user_logged_in() ) {
		return;
	}
	if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
		return;
	}
	if ( isset( $seo_ultimate_plus->modules['settings'] ) && $seo_ultimate_plus->modules['settings']->get_setting( 'seo_toolbar_menu' ) === false ) {
		return;
	}

	// Add the parent link for admin toolbar
	$args = array(
		'id'    => 'suplus-menu',
		'title' => __( 'SEO', 'seo-ultimate-pro' ),
		'href'  => self_admin_url( 'admin.php?page=seo' ),
		'meta'  => array(
			'class' => 'seo-ultimate-pro',
			'title' => __( 'SEO', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	// Add the child link for SEO Settings
	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-menu',
		'id'     => 'suplus-menu-settings',
		'title'  => __( 'SEO Settings', 'seo-ultimate-pro' ),
		'#',
	) );

	$args = array(
		'id'     => 'su-modules',
		'title'  => __( 'Modules', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=seo' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-modules',
			'title' => __( 'Modules', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-opengraph',
		'title'  => __( 'Open Graph+', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-opengraph' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-opengraph',
			'title' => __( 'Open Graph+', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-titles',
		'title'  => __( 'Title Tag Rewriter', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-titles' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-titles',
			'title' => __( 'Title Tag Rewriter', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-meta-descriptions',
		'title'  => __( 'Meta Description Editor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-meta-descriptions' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-meta-descriptions',
			'title' => __( 'Meta Description Editor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-meta-robots',
		'title'  => __( 'Meta Robot Tags Editor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-meta-robots' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-meta-robots',
			'title' => __( 'Meta Robot Tags Editor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-autolinks',
		'title'  => __( 'Deeplink Juggernaut', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-autolinks' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-autolinks',
			'title' => __( 'Deeplink Juggernaut', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-canonical-url',
		'title'  => __( 'Global Canonical Manager', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-canonical-url' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-canonical-url',
			'title' => __( 'Global Canonical Manager', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	if ( isset( $seo_ultimate_plus->modules['alt-attribute'] ) ) {
		$args = array(
			'id'     => 'su-alt-attribute',
			'title'  => __( 'Alt Attribute', 'seo-ultimate-pro' ),
			'href'   => self_admin_url( 'upload.php' ),//'admin.php?page=su-alt-attribute' ),
			'parent' => 'suplus-menu-settings',
			'meta'   => array(
				'class' => 'su-alt-attribute',
				'title' => __( 'Alt Attribute', 'seo-ultimate-pro' )
			)
		);
		$wp_admin_bar->add_node( $args );
	}

	if ( isset( $seo_ultimate_plus->modules['sitemap'] ) ) {
		$args = array(
			'id'     => 'su-sitemap',
			'title'  => __( 'Sitemap Creator', 'seo-ultimate-pro' ),
			'href'   => self_admin_url( 'admin.php?page=su-sitemap' ),
			'parent' => 'suplus-menu-settings',
			'meta'   => array(
				'class' => 'su-sitemap',
				'title' => __( 'Sitemap Creator', 'seo-ultimate-pro' )
			)
		);
		$wp_admin_bar->add_node( $args );
	}

	if ( $seo_ultimate_plus->modules['user-code'] ) {
		$args = array(
			'id'     => 'su-user-code',
			'title'  => __( 'Code Inserter', 'seo-ultimate-pro' ),
			'href'   => self_admin_url( 'admin.php?page=su-user-code' ),
			'parent' => 'suplus-menu-settings',
			'meta'   => array(
				'class' => 'su-user-code',
				'title' => __( 'Code Inserter', 'seo-ultimate-pro' )
			)
		);
		$wp_admin_bar->add_node( $args );
	}

	if ( $seo_ultimate_plus->modules['user-code-plus'] ) {
		$args = array(
			'id'     => 'su-user-code-plus',
			'title'  => __( 'Code Inserter +', 'seo-ultimate-pro' ),
			'href'   => self_admin_url( 'admin.php?page=su-user-code-plus' ),
			'parent' => 'suplus-menu-settings',
			'meta'   => array(
				'class' => 'su-user-code-plus',
				'title' => __( 'Code Inserter +', 'seo-ultimate-pro' )
			)
		);
		$wp_admin_bar->add_node( $args );
	}

	$args = array(
		'id'     => 'su-link-nofollow',
		'title'  => __( 'Nofollow Manager', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-link-nofollow' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-link-nofollow',
			'title' => __( 'Nofollow Manager', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'seo-data-importer',
		'title'  => __( 'SEO Data Importer', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=seodt' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'seo-data-importer',
			'title' => __( 'SEO Data Importer', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-internal-link-aliases',
		'title'  => __( 'Link Mask Generator', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-internal-link-aliases' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-internal-link-aliases',
			'title' => __( 'Link Mask Generator', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-permalinks',
		'title'  => __( 'Permalink Tweaker', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-permalinks' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-permalinks',
			'title' => __( 'Permalink Tweaker', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-hreflang-language',
		'title'  => __( 'Hreflang Global Setting', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-hreflang-language' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-hreflang-language',
			'title' => __( 'Hreflang Global Setting', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-fofs',
		'title'  => __( '404 Monitor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-fofs' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-fofs',
			'title' => __( '404 Monitor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );



	$args = array(
		'id'     => 'su-canonical',
		'title'  => __( 'Canonicalizer', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-canonical' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-canonical',
			'title' => __( 'Canonicalizer', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-files',
		'title'  => __( 'File Editor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-files' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-files',
			'title' => __( 'File Editor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-meta-keywords',
		'title'  => __( 'Meta Keywords Editor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-meta-keywords' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-meta-keywords',
			'title' => __( 'Meta Keywords Editor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-more-links',
		'title'  => __( 'More Link Customizer', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-more-links' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-more-links',
			'title' => __( 'More Link Customizer', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-misc',
		'title'  => __( 'Miscellaneous', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-misc',
			'title' => __( 'Miscellaneous', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-options',
		'title'  => __( 'Plugin Settings', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'options-general.php?page=seo-ultimate' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-options',
			'title' => __( 'Plugin Settings', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );
	$args = array(
		'id'     => 'su-importexport',
		'title'  => __( 'Import/Export', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-importexport' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-importexport',
			'title' => __( 'Import/Export', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );
	$args = array(
		'id'     => 'su-schema',
		'title'  => __( 'Global Schema Generator', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-schema' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-schema',
			'title' => __( 'Global Schema Generator', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-rich-snippets',
		'title'  => __( 'Rich Snippet Creator', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-rich-snippets' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-rich-snippets',
			'title' => __( 'Rich Snippet Creator', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-widgets',
		'title'  => __( 'SEO Ultimate Pro Widgets', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'widgets.php' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-widgets',
			'title' => __( 'SEO Ultimate Pro Widgets', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-wp-settings',
		'title'  => __( 'Settings Monitor', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-wp-settings' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-wp-settings',
			'title' => __( 'Settings Monitor', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-sharing-buttons',
		'title'  => __( 'Sharing Facilitator', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-sharing-buttons' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-sharing-buttons',
			'title' => __( 'Sharing Facilitator', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-webmaster-verify',
		'title'  => __( 'Webmaster Verification Assistant', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-misc#su-webmaster-verify' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-webmaster-verify',
			'title' => __( 'Webmaster Verification Assistant', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	$args = array(
		'id'     => 'su-sds-blog',
		'title'  => __( 'What&#39;s New', 'seo-ultimate-pro' ),
		'href'   => self_admin_url( 'admin.php?page=su-sds-blog' ),
		'parent' => 'suplus-menu-settings',
		'meta'   => array(
			'class' => 'su-sds-blog',
			'title' => __( 'What&#39;s New', 'seo-ultimate-pro' )
		)
	);
	$wp_admin_bar->add_node( $args );

	// Add the child link for Keyword Research
	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-menu',
		'id'     => 'suplus-kwresearch',
		'title'  => __( 'Keyword Research', 'seo-ultimate-pro' ),
		'#',
	) );
	$args = array(
		'id'     => 'su-text-tools',
		'title'  => 'Semantic Content/Competitor Analysis',
		'href'   => 'http://www.text-tools.net/members/aff/go/seodesignframework',
		'parent' => 'suplus-kwresearch',
		'meta'   => array(
			'class'  => 'su-text-tools',
			'title'  => 'Keyword and Competitor Analysis',
			'target' => '_blank'
		)
	);
	$wp_admin_bar->add_node( $args );

	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-kwresearch',
		'id'     => 'suplus-adwordsexternal',
		'title'  => __( 'Google AdWords Keyword Planner', 'seo-ultimate-pro' ),
		'href'   => 'http://adwords.google.com/keywordplanner',
		'meta'   => array( 'target' => '_blank' ),
	) );
	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-kwresearch',
		'id'     => 'suplus-googleinsights',
		'title'  => __( 'Google Insights', 'seo-ultimate-pro' ),
		'href'   => 'https://www.google.com/trends/',
		'meta'   => array( 'target' => '_blank' ),
	) );
	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-kwresearch',
		'id'     => 'suplus-googleinsights',
		'title'  => __( 'Google Insights', 'seo-ultimate-pro' ),
		'href'   => 'https://www.google.com/trends/',
		'meta'   => array( 'target' => '_blank' ),
	) );
	$wp_admin_bar->add_menu( array(
		'parent' => 'suplus-kwresearch',
		'id'     => 'suplus-googletesttool',
		'title'  => __( 'Google Structured Data Testing Tool', 'seo-ultimate-pro' ),
		'href'   => 'https://search.google.com/structured-data/testing-tool/',
		'meta'   => array( 'target' => '_blank' ),
	) );

	if ( ! is_admin() ) {
		$su_canonical = new SU_Canonical();
		$url          = $su_canonical->get_canonical_url();

		if ( is_string( $url ) ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-menu',
				'id'     => 'suplus-analysis',
				'title'  => __( 'Page Analysis Tools', 'seo-ultimate-pro' ),
				'#',
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-inlinks-ose',
				'title'  => __( 'Check OSE DA/PA', 'seo-ultimate-pro' ),
				'href'   => '//moz.com/researchtools/ose/links?site=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-kwdensity',
				'title'  => __( 'Check Keyword Density', 'seo-ultimate-pro' ),
				'href'   => '//www.zippy.co.uk/keyworddensity/index.php?url=' . urlencode( $url ) . '&keyword=',
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-cache',
				'title'  => __( 'Check Google Cache', 'seo-ultimate-pro' ),
				'href'   => '//webcache.googleusercontent.com/search?strip=1&q=cache:' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-header',
				'title'  => __( 'Check Headers', 'seo-ultimate-pro' ),
				'href'   => '//quixapp.com/headers/?r=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-richsnippets',
				'title'  => __( 'Check Rich Snippets', 'seo-ultimate-pro' ),
				'href'   => '//www.google.com/webmasters/tools/richsnippets?q=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-facebookdebug',
				'title'  => __( 'Facebook Debugger', 'seo-ultimate-pro' ),
				'href'   => '//developers.facebook.com/tools/debug/og/object?q=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-pinterestvalidator',
				'title'  => __( 'Pinterest Rich Pins Validator', 'seo-ultimate-pro' ),
				'href'   => '//developers.pinterest.com/rich_pins/validator/?link=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-htmlvalidation',
				'title'  => __( 'HTML Validator', 'seo-ultimate-pro' ),
				'href'   => '//validator.w3.org/check?uri=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-cssvalidation',
				'title'  => __( 'CSS Validator', 'seo-ultimate-pro' ),
				'href'   => '//jigsaw.w3.org/css-validator/validator?uri=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-pagespeed',
				'title'  => __( 'Google Page Speed Test', 'seo-ultimate-pro' ),
				'href'   => '//developers.google.com/speed/pagespeed/insights/?url=' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-modernie',
				'title'  => __( 'Modern IE Site Scan', 'seo-ultimate-pro' ),
				'href'   => '//www.modern.ie/en-us/report#' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'suplus-analysis',
				'id'     => 'suplus-page-archive',
				'title'  => __( 'Wayback Machine Page Archive', 'seo-ultimate-pro' ),
				'href'   => 'https://www.archive.org/web/*/' . urlencode( $url ),
				'meta'   => array( 'target' => '_blank' ),
			) );
		}
	}
}

add_action( 'admin_bar_menu', 'seo_ultimate_plus_admin_bar_menu', 95 );

// remove jetpack open graph tags
add_filter( 'jetpack_enable_open_graph', '__return_false', 1000 );

?>