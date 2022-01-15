<?php


/*  Copyright 2013  Adamidis Athanasios  (email : info@mediapoint.gr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( ! class_exists( 'ST_Taxonomy' ) ) {
	global $havejs;

	class ST_Taxonomy {
		/**
		 * Construct the plugin object
		 */
		public function __construct() {
			global $havejs;
			$havejs = false;
			// Initialize Settings
			//require_once(sprintf("%s/ST_settings.php", dirname(__FILE__)));
			//$SemanticTags_Plugin_Settings = new SemanticTags_Plugin_Settings();


			add_action( 'init', array( $this, 'build_SemanticTags' ), 11 );

			add_shortcode( 'semantic_tags', array( $this, 'get_semantic_tags' ) );


		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate() {

			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate() {
			// Do nothing
		} // END public static function deactivate


		public static function copy_below_table( $taxonomy ) {
			echo 'Add rewrite rule on .htaccess	<code>RewriteRule ^semantictags/(.*)$ /semantictags [L,R=301]</code>';
		}


		public static function copy_above_form( $taxonomy ) {
			echo '<p>Dolor sit amet</p>';
		}

		public function build_SemanticTags() {

			// Add new taxonomy, NOT hierarchical (like tags)
			$labels = array(
				'name'                       => _x( 'Wiki', 'taxonomy general name' ),
				'singular_name'              => _x( 'Semantic Tag', 'taxonomy singular name' ),
				'search_items'               => __( 'Search Semantic Tags' ),
				'popular_items'              => __( 'Popular Semantic Tags' ),
				'all_items'                  => __( 'All SemanticTags' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Semantic Tag' ),
				'update_item'                => __( 'Update Semantic Tag' ),
				'add_new_item'               => __( 'Add New Semantic Tag' ),
				'new_item_name'              => __( 'New SemanticTags Name' ),
				'separate_items_with_commas' => __( 'Separate SemanticTags with commas' ),
				'add_or_remove_items'        => __( 'Add or remove SemanticTags' ),
				'choose_from_most_used'      => __( 'Choose from the most used SemanticTags' ),
				'not_found'                  => __( 'No SemanticTags found.' ),
				'menu_name'                  => __( 'SemanticTags' ),
			);

			$args = array(
				'hierarchical'          => false,
				'labels'                => $labels,
				'show_ui'               => true,
				'show_admin_column'     => true,
				'update_count_callback' => '_update_post_term_count',
				'show_in_rest'          => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'wiki', true ),
			);

			$post_types = get_post_types( '', 'names' );
			register_taxonomy( 'semantictags', $post_types, $args );
			//flush_rewrite_rules();

			add_action( 'semantictags_add_form_fields', array(
				'ST_Taxonomy',
				'semantictags_taxonomy_add_new_meta_field'
			) );
			add_action( 'semantictags_edit_form_fields', array(
				'ST_Taxonomy',
				'semantictags_taxonomy_edit_meta_field'
			) );
			add_action( 'edited_semantictags', array( &$this, 'save_taxonomy_semantic_meta' ) );
			add_action( 'create_semantictags', array( &$this, 'save_taxonomy_semantic_meta' ) );

		}


		public static function semantictags_taxonomy_add_new_meta_field() {

			?>

            <div class="form-field">
                <label for="term_meta[semantictag_term_meta_keyword]"><?php _e( 'Keyword', 'semantictags' ); ?></label>
                <input type="text" name="term_meta[semantictag_term_meta_keyword]"
                       id="term_meta[semantictag_term_meta_keyword]" value="">
                <p class="description"><?php _e( 'This is a keyword. Typically it will be the same keyword as the name of the tag.)', 'semantictags' ); ?></p>
            </div>
            <div class="form-field">
                <label for="term_meta[semantictag_term_meta_citation]"><?php _e( 'Citation', 'semantictags' ); ?></label>
                <input type="text" name="term_meta[semantictag_term_meta_citation]"
                       id="term_meta[semantictag_term_meta_citation]" value="">
                <p class="description"><?php _e( 'This is the @location of the reference, e.g. Wikipedia (where is the other publication, web page, scolarly article, etc.)', 'semantictags' ); ?></p>
            </div>
            <div class="form-field">
                <label for="term_meta[semantictag_term_meta_link]"><?php _e( 'Link', 'semantictags' ); ?></label>
                <input type="text" name="term_meta[semantictag_term_meta_link]"
                       id="term_meta[semantictag_term_meta_link]" value="">
                <p class="description"><?php _e( 'Enter internal or external URL (Link)' ); ?></p>
            </div>


			<?php
		}


		// Edit term page
		public static function semantictags_taxonomy_edit_meta_field( $term ) {

			$t_id = $term->term_id;

			// retrieve the existing value(s) for this meta field. This returns an array
			$term_meta = get_option( "taxonomy_$t_id" ); ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_citation]"><?php _e( 'Citation:', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_citation]"
                           id="term_meta[semantictag_term_meta_citation]"
                           value="<?php if ( isset( $term_meta['semantictag_term_meta_citation'] ) ) {
						       echo esc_attr( $term_meta['semantictag_term_meta_citation'] );
					       } else {
						       echo '';
					       } ?>">
                    <p class="description"><?php _e( 'This is the @location of the reference, e.g. Wikipedia (where is the other publication, web page, scolarly article, etc.)*', 'semantictags' ); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_keyword]"><?php _e( 'Keyword:', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_keyword]"
                           id="term_meta[semantictag_term_meta_keyword]"
                           value="<?php if ( isset( $term_meta['semantictag_term_meta_keyword'] ) ) {
                               echo esc_attr( $term_meta['semantictag_term_meta_keyword'] );
                           } else {
                               echo '';
                           } ?>">
                    <p class="description"><?php _e( 'This is a keyword. Typically it will be the same keyword as the name of the tag.)*', 'semantictags' ); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_link]"><?php _e( 'Link', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_link]"
                           id="term_meta[semantictag_term_meta_link]"
                           value="<?php echo esc_attr( $term_meta['semantictag_term_meta_link'] ) ? esc_attr( $term_meta['semantictag_term_meta_link'] ) : ''; ?>">
                    <p class="description"><?php _e( 'Enter internal or external URL (Link)' ); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_url1]"><?php _e( 'URL 1:', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_url1]"
                           id="term_meta[semantictag_term_meta_url1]"
                           value="<?php if ( isset( $term_meta['semantictag_term_meta_url1'] ) ) {
						       echo esc_attr( $term_meta['semantictag_term_meta_url1'] );
					       } else {
						       echo '';
					       } ?>">
                    <p class="description"><?php _e( 'URL to reference', 'semantictags' ); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_url2]"><?php _e( 'URL 2:', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_url2]"
                           id="term_meta[semantictag_term_meta_url2]"
                           value="<?php if ( isset( $term_meta['semantictag_term_meta_url2'] ) ) {
						       echo esc_attr( $term_meta['semantictag_term_meta_url2'] );
					       } else {
						       echo '';
					       } ?>">
                    <p class="description"><?php _e( 'URL to reference', 'semantictags' ); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row" valign="top"><label
                            for="term_meta[semantictag_term_meta_url3]"><?php _e( 'URL 3:', 'semantictags' ); ?></label>
                </th>
                <td>
                    <input type="text" name="term_meta[semantictag_term_meta_url3]"
                           id="term_meta[semantictag_term_meta_url3]"
                           value="<?php if ( isset( $term_meta['semantictag_term_meta_url3'] ) ) {
						       echo esc_attr( $term_meta['semantictag_term_meta_url3'] );
					       } else {
						       echo '';
					       } ?>">
                    <p class="description"><?php _e( 'URL to reference', 'semantictags' ); ?></p>
                </td>
            </tr>

			<?php

		}


		// Save extra taxonomy fields callback function.
		public function save_taxonomy_semantic_meta( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				//
				$t_id      = $term_id;
				$term_meta = get_option( "taxonomy_$t_id" );

				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][ $key ] ) ) {
					    $postterm_meta = "";
                        $postterm_meta = strip_tags($_POST['term_meta'][ $key ]);
						$term_meta[ $key ] = $postterm_meta;
					}
				}

				update_option( "taxonomy_$t_id", $term_meta );


			}
		}


		public static function get_semantic_tags( $atts ) {
			global $post;
			extract( shortcode_atts( array( 'text' => '', ), $atts ) );

			$html  = "";
			$terms = array();
			if ( isset( $atts['text'] ) ) {
				if ( $text != '' ) {
					$terms[] = get_term_by( 'name', $text, 'semantictags' );
					if ( empty( $terms[0] ) ) {
						$html = $text;
					}
				}
			} else {
				$terms = wp_get_post_terms( $post->ID, 'semantictags', '' );
			}

			if ( ! empty( $terms[0] ) ) {
				foreach ( $terms as $term ) {
					$html .= self::get_template_tag( $term );
				}
			}

			return $html;
		}

		public static function get_template_tag( $term ) {

			$term_meta_options                   = get_option( "taxonomy_$term->term_id" );
			$html                                = '';
			$semantictag_term_schema_citation    = ( isset( $term_meta_options['semantictag_term_meta_citation'] ) );
			$semantictag_term_schema_name        = ( isset( $term_meta_options['semantictag_term_meta_name'] ) );
			$semantictag_term_schema_url1        = ( isset( $term_meta_options['semantictag_term_meta_url1'] ) );
			$semantictag_term_schema_url2        = ( isset( $term_meta_options['semantictag_term_meta_url2'] ) );
			$semantictag_term_schema_url3        = ( isset( $term_meta_options['semantictag_term_meta_url3'] ) );
			$semantictag_term_schema_description = ( isset( $term_meta_options['semantictag_term_meta_description'] ) );
			$semantictag_term_meta_link          = ( isset( $term_meta_options['semantictag_term_meta_link'] ) ) ? $term_meta_options['semantictag_term_meta_link'] : '';
			$semantictag_term_meta_content       = ( isset( $term_meta_options['semantictag_term_meta_content'] ) ) ? $term_meta_options['semantictag_term_meta_content'] : '';
			$semantictag_term_meta_title         = ( isset( $term_meta_options['semantictag_term_meta_title'] ) ) ? $term_meta_options['semantictag_term_meta_title'] : '';


			return $html;
		}

		// add sortcode e.g. [semantic_tags]


	}


	// END class ST_Taxonomy
} // END if(!class_exists('ST_Taxonomy'))

function hasSemTerm( $content ) {

	global $post;

	$schema =
		'<script type="application/ld+json">
	        {
		        "@context": "http://schema.org",
		        "@type": "CreativeWork",
		        "keywords": "#keywords#",
		        "citation": "#citation#",
		        "url": "#urls#",
		        "description": "#description#"
	        }
	        </script>';

	if ( has_term( '', 'semantictags' ) && taxonomy_exists( 'semantictags' ) && !is_singular() ) {

		$terms = wp_get_post_terms( $post->ID, 'semantictags', array( 'fields' => 'ids' ) );
		$urls  = array();
// echo $terms[0];
	//	foreach ( $terms as $ids ) {
			$term      = get_term( $terms[0], 'semantictags' );
			$term_meta = get_option( "taxonomy_" . $term->term_id );

			$schema = str_ireplace( "#keywords#", strip_tags($term_meta['semantictag_term_meta_keyword']), $schema );
			$schema = str_ireplace( "#citation#", $term_meta['semantictag_term_meta_citation'], $schema );
			$schema = str_ireplace( "#description#", $term->description, $schema );
			$urllist    = "";
			if ( isset( $term_meta['semantictag_term_meta_url1'] ) && $term_meta['semantictag_term_meta_url1'] != "" ) {
				$urls[] = $term_meta['semantictag_term_meta_url1'];
				$urllist    .= '<ul><li><a href="' . $term_meta['semantictag_term_meta_url1'] . '">' . $term_meta['semantictag_term_meta_url1'] . '</a></li>';
			}

			if ( isset( $term_meta['semantictag_term_meta_url2'] ) && $term_meta['semantictag_term_meta_url2'] != "" ) {
				$urls[] = $term_meta['semantictag_term_meta_url2'];
				$urllist    .= '<ul><li><a href="' . $term_meta['semantictag_term_meta_url2'] . '">' . $term_meta['semantictag_term_meta_url2'] . '</a></li>';
			}

			if ( isset( $term_meta['semantictag_term_meta_url3'] ) && $term_meta['semantictag_term_meta_url3'] != "" ) {
				$urls[] = $term_meta['semantictag_term_meta_url3'];
				$urllist    .= '<ul><li><a href="' . $term_meta['semantictag_term_meta_url3'] . '">' . $term_meta['semantictag_term_meta_url3'] . '</a></li>';
			}

			$urllist    .= "</ul>";
			$urlreplace = "";
            $linkurl = "";
			// print_r($urls);
			if ( ! empty( $urls ) ) {
				foreach ( $urls as $addurl ) {
					$urlreplace .= "\"" . $addurl . "\",";
				}
			}
				$urlreplace = substr( $urlreplace, 0, - 1 );
                if(isset($term_meta['semantictag_term_meta_link'])) {
                    $linkurl = $term_meta['semantictag_term_meta_link'];
                }
				$schema = str_ireplace( "#urls#", $linkurl, $schema );

				$content = $schema . $content . $urllist;

				unset( $urls );

	//	}

	}


	return $content;
}
function add_su_suicidal_filter( $hook, $callback, $priority = 10, $params = 1 ) {
	add_filter( $hook, function( $first_arg ) use( $callback ) {
		static $ran = false;

		if ( $ran ) {
			return $first_arg;
		}

		$ran = true;
		return call_user_func_array( $callback, func_get_args() );
	}, $priority, $params );
}
add_su_suicidal_filter( 'the_content', 'hasSemTerm' );