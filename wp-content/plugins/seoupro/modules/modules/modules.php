<?php
/**
 * Module Manager Module
 *
 * @since 0.7
 */

if ( class_exists( 'SU_Module' ) ) {

	class SU_Modules extends SU_Module {

		static function get_module_title() {
			return __( 'Module Manager', 'seo-ultimate-pro' );
		}

		static function get_menu_title() {
			return __( 'Modules', 'seo-ultimate-pro' );
		}

		static function get_menu_pos() {
			return 0;
		}

		function is_menu_default() {
			return true;
		}

		function init() {
			if(isset($_GET['setmodule'])) {
				$psdata = (array) get_option( 'seo_ultimate_plus', array() );
				$modulename = $_GET['setmodule'];
				$modulevalue = $_GET['value'];
				if($psdata['modules'][ 'sitemap' ] != '10' || $psdata['modules'][ 'sitemap' ] != '-10') {
					$psdata['modules'][ 'sitemap' ] = $modulevalue;
				}
				$psdata['modules'][ $modulename ] = $modulevalue;
				update_option( 'seo_ultimate_plus', $psdata );
				if($_GET['setmodule'] == "su_silo_active") {
					update_option( "su_silo_active", 10);

				}
				if($_GET['setmodule'] == "su_silo_deactive") {
					update_option( "su_silo_active", -10);

				}
				wp_redirect( esc_url( add_query_arg( 'su-modules-updated', '1', suurl::current() ) ), 301 );
			}
			/*
			$t = 0;
			if ( $this->is_action( 'update' ) ) {

				$psdata = (array) get_option( 'seo_ultimate_plus', array() );

				foreach ( $_POST as $key => $newvalue ) {
					if ( substr( $key, 0, 3 ) == 'su-' ) {
						$key = str_replace( array( 'su-', '-module-status' ), '', $key );

						$newvalue = intval( $newvalue );
						$oldvalue = $psdata['modules'][ $key ];

						if ( $oldvalue != $newvalue ) {
							if ( $oldvalue == SU_MODULE_DISABLED ) {
								$this->plugin->call_module_func( $key, 'activate' );
							}
							if ( $newvalue == SU_MODULE_DISABLED ) {
								$this->plugin->call_module_func( $key, 'deactivate' );
							}
						}

						$psdata['modules'][ $key ] = $newvalue;
					}
				}

				update_option( 'seo_ultimate_plus', $psdata );

				wp_redirect( esc_url( add_query_arg( 'su-modules-updated', '1', suurl::current() ) ), 301 );
				exit;
			} */
		}

		function admin_page_contents() {
			$psdata = (array) get_option( 'seo_ultimate_plus', array() );
			$su_silo = "";
			$su_silo = get_option("su_silo_active");
			$su_modules_layout = "";
			$su_modules_layout = "
<div>
</div>
<div class=\"features-boxed\">
    <div class=\"container\">
        <div class=\"intro\" style=\"max-width: 900px;\">
            <h2 class=\"text-center\" style=\"margin-top: 0px;\">Modules</h2>
            <p class=\"text-left\" style=\"font-size: 16px; color: #000;\">SEO Ultimate Pro’s features are located in groups called “modules.” By default, most of these modules are listed in the “SEO” menu on the left or above in the “SEO” Menu above. <br /><br />The Module Manager lets you enable, manage or disable modules you don’t use. Some modules like the Link Mask Generator do not turn off or on. <br /></p>
        </div>
        <div class=\"row features\">
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-link fa-4x faicon\"></i>
                    <h3 class=\"name\">Deeplink Juggernaut</h3>
                    <p class=\"description\">Automatically link specific words or phrases in your page/post content to a URL you specify.</p>
                    <a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=autolinks&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=autolinks&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'autolinks' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-autolinks"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>

            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fas fa-list fa-4x faicon\"></i>
                    <h3 class=\"name\">HTML and XML Site Maps</h3>
                    <p class=\"description\">Create HTML or XML sitemaps to submit to search engines to increase crawl-depth and indexation.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=sitemap&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=sitemap&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= (@$psdata['modules'][ 'sitemap' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-sitemap"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-unlink fa-4x faicon\"></i>
                    <h3 class=\"name\">404 Monitor</h3>
                    <p class=\"description\">Discover broken links from internal pages, user-agents, and bots with the 404 Monitor module.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=fofs&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=fofs&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'fofs' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-fofs"> Manage Settings </a>' : 'Disabled';
                    $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-image fa-4x faicon\"></i>
                    <h3 class=\"name\">Alt Attribute Editor</h3>
                    <p class=\"description\">Mass-edit and optimize your images alternative attributes (alt tags) conveniently from the media library.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=alt-attribute&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=alt-attribute&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'alt-attribute' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="upload.php?mode=list"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-directions fa-4x faicon\"></i>
                    <h3 class=\"name\">Canonicalizer</h3>
                    <p class=\"description\">Generate " . htmlentities('<link rel="canonical" />') . " meta tags to prevent duplicate content or consolidate page authority.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=canonical&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=canonical&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'canonical' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-canonical-url"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-code fa-4x faicon\"></i>
                    <h3 class=\"name\">Code Inserter</h3>
                    <p class=\"description\">Add HTML or 3rd party scripts globally to your websites header, footer, above or below content.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=user-code&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=user-code&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'user-code' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-user-code"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
                        <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fas fa-file-code fa-4x faicon\"></i>
                    <h3 class=\"name\">Code Inserter+</h3>
                    <p class=\"description\">Add HTML or 3rd-party scripts conveniently to pages, posts or post types instead of globally.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=user-code-plus&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=user-code-plus&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'user-code-plus' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-user-code-plus"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-edit fa-4x faicon\"></i>
                    <h3 class=\"name\">File Editor</h3>
                    <p class=\"description\">Edit your websites .htaccess file conveniently (without using FTP) from the file editor module.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=files&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=files&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'files' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-files"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-tag fa-4x faicon\"></i>
                    <h3 class=\"name\">Semantic Tags</h3>
                    <p class=\"description\">Semantic tags allow you to create a linked-data vocabulary for your website by marking up pages or custom tag pages with linked-data / schema.</p><a class=\"btndon btn-enable\" href=\"#\">This Module is Always Active</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: Enabled | </strong><a style=\"color: #1c2198; text-decoration-line: underline;\" href=\"edit-tags.php?taxonomy=semantictags\"> Manage Settings </a></p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-file-csv fa-4x faicon\"></i>
                    <h3 class=\"name\">Import / Export</h3>
                    <p class=\"description\">Save time with the SEO Ultimate PRO Import/Export to CSV feature to conveniently mass-edit data.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=importexport&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=importexport&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'importexport' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-importexport"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>

            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-pencil-alt fa-4x faicon\"></i>
                    <h3 class=\"name\">Title Tag Rewriter</h3>
                    <p class=\"description\">Mass-edit and rewrite title tags conveniently with SEO Ultimate PRO's Title Tag Rewriter Module.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=titles&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=titles&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'titles' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-titles"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                 </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-cogs fa-4x faicon\"></i>
                    <h3 class=\"name\">Meta Description Editor</h3>
                    <p class=\"description\">Conveniently mass-edit your websites meta descriptions sitewide to enhance click-through rates.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=meta-descriptions&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=meta-descriptions&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'meta-descriptions' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-meta-descriptions"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-robot fa-4x faicon\"></i>
                    <h3 class=\"name\">Meta Robots Tags Editor</h3>
                    <p class=\"description\">Conveniently mass edit your websites meta robots tags globally for pages, posts and post types.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=meta-robots&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=meta-robots&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'meta-robots' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-meta-robots"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fa fa-ellipsis-h fa-4x faicon\"></i>
                    <h3 class=\"name\">Miscellaneous Module</h3>
                    <p class=\"description\">The miscellaneous module is comprised of group settings that do not warrant their own dashboard.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=misc&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=misc&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'misc' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-misc"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fas fa-ban fa-4x faicon\"></i>
                    <h3 class=\"name\">No Follow Manager</h3>
                    <p class=\"description\">Add or manage the rel=\"nofollow\" tag to prevent potential penalties for specific pages & links.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=link-nofollow&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=link-nofollow&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'link-nofollow' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-misc#su-link-nofollow"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fab fa-facebook-square fa-4x faicon\"></i>
                    <h3 class=\"name\">Open Graph+</h3>
                    <p class=\"description\">Optimize your top pages and posts to show specific images on social media websites with the OpenGraph+.</p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=opengraph&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=opengraph&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($psdata['modules'][ 'opengraph' ] == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=su-opengraph"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fas fa-file-import fa-4x faicon\"></i>
                    <h3 class=\"name\">SEO Data Importer</h3>
                    <p class=\"description\">Import SEO Settings from Yoast and other plugins or themes into SEO Ultimate PRO.</p><br><a class=\"btndon btn-enable\" href=\"#\">This Module is Always Active</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: Enabled | </strong><a style=\"color: #1c2198; text-decoration-line: underline;\" href=\"admin.php?page=seodt\"> Manage Settings </a></p>
                    </div>
            </div>

            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fab fa-js fa-4x faicon\"></i>
                    <h3 class=\"name\">Schema Generator</h3>
                    <p class=\"description\">Manage 22 types of schema markup for pages, posts and custom post types to help search engines classify & rank your content.</p><a class=\"btndon btn-enable\" href=\"#\">This Module is Always Active</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: Enabled | </strong><a style=\"color: #1c2198; text-decoration-line: underline;\" href=\"admin.php?page=su-schema\"> Manage Settings </a></p>
                 </div>
            </div>
            <div class=\"col-md-4 col-sm-6 item\">
                <div class=\"box\"><i class=\"fab fa-buffer fa-4x faicon\"></i>
                    <h3 class=\"name\">Deep Silo Builder</h3>
                    <p class=\"description\">Create SEO friendly topic-based site architecture and silo linking structures for higher rankings.<br><strong style=\"color: red; font-size: small;\">Permalink structure will be changed when using this module.</strong></p><a class=\"btndon btn-enable\" href=\"admin.php?page=seo&setmodule=su_silo_active&value=10\">Enable</a><a class=\"btndon btn-disable\" href=\"admin.php?page=seo&setmodule=su_silo_deactive&value=-10\">Disable</a>
                    <p style=\"padding-top: 5px;\"><strong>Status: </strong>"; $su_modules_layout .= ($su_silo == 10)? 'Enabled | <a style="color: #1c2198; text-decoration-line: underline;" href="admin.php?page=wpu-silo-builder"> Manage Settings </a>' : 'Disabled'; $su_modules_layout .= "</p>
                    </div>
            </div>
        </div>
    </div>
</div>";
			echo $su_modules_layout;

/*
			 $psdata = (array) get_option( 'seo_ultimate_plus', array() );


			echo "<pre>";
			print_r( $psdata );
			echo "</pre>";
*/

		}

		function add_help_tabs($screen) {

			$screen->add_help_tab(array(
				'id' => 'su-modules-options'
			, 'title' => __('Options Help', 'seo-ultimate-pro')
			, 'content' => __("
<p>SEO Ultimate Pro features are located in groups called &#8220;modules.&#8221; By default, most of these modules are listed in the &#8220;SEO&#8221; menu on the left or under the SEO menu at the top of the screen.</p>
<p>The Module Manager lets you customize the visibility and accessibility of each module; here are the options available:</p>
<ul>
	<li><strong>Enabled</strong> &mdash; The default option, except for the Silo Builder and Meta Keywords Editor, these are disabled with the option to enable. Otherwise, enabled modules are fully enabled and accessible.</li>
	<li><strong>Disabled</strong> &mdash; TThe module will be completely disabled and inaccessible.</li>
</ul>
", 'seo-ultimate-plus')));

			$screen->add_help_tab(array(
				'id' => 'su-modules-faq'
			, 'title' => __('FAQ', 'seo-ultimate-pro')
			, 'content' => __("
<ul>
	<li><strong>What are modules?</strong><br />SEO Ultimate Pro features are divided into groups called &#8220;modules.&#8221; SEO Ultimate Pro &#8220;Module Manager&#8221; lets you enable or disable each of these groups of features. This way, you can pick-and-choose which SEO Ultimate Pro features you want.</li>
	<li><strong>Can I access a module again after I’ve disabled it?</strong><br />No. Disabled modules are not accessible in the menu, nor are their options. This is because if you have another plugin or solution in place and would like to disable a module, it does not interfere. If you’d like to put the module back in the &#8220;SEO&#8221; menu, just re-enable the module in the Module Manager and click &#8220;Save Changes.&#8221;</li>
</ul>
", 'seo-ultimate-pro')));
		}


	}
}
?>