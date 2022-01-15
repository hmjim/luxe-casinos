<?php
if ( class_exists( 'SU_Module' ) ) {

	class SU_Schema extends SU_Module {

		static function get_module_title() {
			return __( 'Global Schema Generator', 'seo-ultimate-pro' );
		}

		static function get_menu_title() {
			return __( 'Global Schema Generator', 'seo-ultimate-pro' );
		}

		function init() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'knockout', plugin_dir_url( __FILE__ ) . 'assets/js/knockout-min.js', array( 'jquery' ) );

		}

		function admin_page_contents() {
			global $wpdb;



			if (is_admin ())
				wp_enqueue_media ();

			$args = array(
				'post_type'           => array( 'post', 'page', 'product' ),
				'post_status'         => 'publish',
				'posts_per_page'      => - 1,
				'ignore_sticky_posts' => true,
				'orderby'             => 'ID',
			);
			// microtime to append to form field names

			$formNameAppend = microtime();

			$qry = new WP_Query( $args );
// Show post titles
			$su_posts = "";
			foreach ( $qry->posts as $p ) {
				$su_posts .= '<option value="' . $p->ID . '">' . $p->post_title . '</option>';
			}
			/*
			$schemas           = array(
				"article",
				"newsarticle",
				"blogposting",
				"business",
				"course",
				"event",
				"organization",
				"person",
				"product",
				"recipe",
				"review",
				"software",
				"video"
			);
			$su_selectedSchema = "";
			$su_selectedSchema .= '<option value="" selected="selected">-------</option>';
			foreach ( $schemas as $selectedSchema ) {
				$su_selectedSchema .= '<option value="' . $selectedSchema . '">' . $selectedSchema . '</option>';
			}
			*/
			echo '
<script>
jQuery(document).ready(function() {
    var $ = jQuery;
    if ($(\'.set_custom_images\').length > 0) {
        if ( typeof wp !== \'undefined\' && wp.media && wp.media.editor) {
            $(\'.set_custom_images\').on(\'click\', function(e) {
                e.preventDefault();
                var button = $(this);
                var id = button.prev();
                wp.media.editor.send.attachment = function(props, attachment) {
                    id.val(attachment.id);
                };
                wp.media.editor.open(button);
                return false;
            });
        }
    }
});
</script>
<div>
   <div class="container">
      <div class="row">
         <div class="col-md-12" style="padding-bottom: 10px;">
            <label>Select Schema Type: </label>
            <select class="input-sm" style="font-size: small;" data-bind="options: schemas, value: schema, optionsText: \'title\', valueAllowUnset: true">
            <option selected="selected">Select Schema</option>
            </select>
         </div>
      </div>
      <div style="padding:10px;background-color: #c4e3f3;text-align: center; border-color: #002a80;">
<h4>If you need ddto use an image for image URL fields, please open the Media Manager using the button below. You will need to select "Copy Link" in the Media Manager.</h4>
<br>
    <button class="set_custom_images btn btn-primary"><i class="fa fa-image"></i> Open Media Library</button>
    </div>
      <div class="row" style="padding: 49px;">
         <div class="col-md-12"><label>Select Post/Page/Custom Post Type: </label></div>
         <div class="col-md-12" style="height: 100%;">
            <select id="postid" multiple="multiple" class="input-sm form-control" size="15" style="height: 100%; font-size: small">
               <optgroup label="Select Post/Page">' . $su_posts . '</optgroup>
            </select>
         <div id="ko-ready" class="readout" style="display:none; max-width: 80%;">

<div class="row" data-bind="if: schema" style="padding: 15px;">
<table>
                <tbody>                  
                    <!-- ko foreach: schemaItems -->
                    <tr><td><span><br /><strong data-bind="text: title"></strong></span><br /><br /><div data-bind="template: { name: type }"></div></td></tr>
                    <!-- /ko -->
                </tbody>
            </table>

				</div>
				<div class="row" data-bind="if: schema" style="padding: 15px;"></div>
				
				</div>
				
				<template id="combo">
				<select class="input-sm form-control" data-bind="options: choices, value: value, valueAllowUnset: true">
				</template>
				
								<template id="combo2">
				<select id="combo2" name="combo2" class="input-sm form-control" data-bind="options: choices, value: value, valueAllowUnset: true">
				</template>
				
				<template id="combo3">
				<select id="combo3" name="combo3" class="form-control" data-bind="options: choices, value: value, valueAllowUnset: true">
				</template>
				
				
				<template id="contact2phone">
				<input id="contact2phone" name="contact2phone" class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
				</template>
				
				<template id="contact2email">
				<input id="contact2email" name="contact2email" class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
				</template>
				
				<template id="contact3phone">
				<input id="contact3phone" name="contact3phone" class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
				</template>
				
				<template id="contact3email">
				<input id="contact3email" name="contact3email" class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
				</template>
				
				<template id="country">
				<select class="input-sm form-control" data-bind="options: choices, value: value, optionsText: \'countryName\', optionsValue: \'countryCode\', optionsCaption: \'Choose...\', valueAllowUnset: true">
				</template>
				
				<template id="string">
				<input class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
				<span style="max-width: 100%;" class="help-block" data-bind="html: info"></span>
				
				</template>
				
				<template id="standardDate">
					<input class="form-control input-sm datepick hasDatepicker" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
					<span style="max-width: 80%;" class="help-block" data-bind="html: info"></span>

				</template>
				
				
				<template id="logoimg">

					<input id="upload_image" class="form-control input-sm" name=upload_image" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />

					<span style="max-width: 80%;" class="help-block" data-bind="html: info"></span>

				</template>
				
				<template id="textbox">
				<textarea type="text" data-bind="value: value, attr: {placeholder:placeholder}"></textarea>
				</template>
				
				<template id="defaultdiv">
				<div class="defaultcode" type="text" data-bind="text: macro"></div>
				</template>

<template id="businessHours">
        <table>
            <tbody>
                <tr>
                    <th>Day
                    </th><th>Opens
                    </th><th>Closes
                </th></tr>
                <tr>
                    <td>Monday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgMonOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgMonClose"></td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgTueOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgTueClose"></td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgWedOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgWedClose"></td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgThuOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgThuClose"></td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgFriOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgFriClose"></td>
                </tr>
                <tr>
                    <td>Saturday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgSatOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgSatClose"></td>
                </tr>
                <tr>
                    <td>Sunday</td>
                    <td><input class="form-control input-sm" data-bind="value: orgSunOpen"></td>
                    <td><input class="form-control input-sm" data-bind="value: orgSunClose"></td>
                </tr>
            </tbody>
        </table>

</template>
         </div>
         <div class="col-md-6">
         <form id="schemacode" method="post">
         <textarea class="input-sm code form-control" rows="10" name="code" data-bind="text: jsonLD" style="background-color: rgb(244,244,244);"></textarea>
         Page/Post ID Selected: <input class="input-sm" id="post_id" type="text" name="post_id" value="" readonly>
         <hr>
         <div style="text-align: center">
         <input class="btn btn-primary" type="submit" value="Save Schema">
         </div>
         </form>
         <!-- <iframe style="padding-top: 10px;" width="100%" id="su_preview" onload="this.style.height=this.contentDocument.body.scrollHeight;">></iframe> -->
         </div>
      </div>
   </div>
</div>




 
 
 
    <script>
    //<![CDATA[
    (function () {
        var c = document.body.classList;
        c.remove(\'no-js\');
        c.add(\'js\');
    })();
    //]]>
</script>

<script>
jQuery(function($) {
    $("#postid").change(function() {
            $("#post_id").val(this.value);
    });
});
</script>

';

			if(isset($_POST['post_id'])) {
				$su_post_id = $_POST['post_id'];
				$su_schema  = $_POST['code'];

				if(isset($_POST['combo2'])) {
					$contact2 = "";
					$contact2 = "	 },{ 
     \"@type\": \"ContactPoint\",\n
     \"contactType\": \"" . $_POST['combo2'] . "\",\n
     \"telephone\": \"" . $_POST['contact2phone'] . "\",\n
     \"email\": \"" . $_POST['contact2email'] . "\"\n
     \n";
					$su_schema = str_replace("###contact2###",$contact2,$su_schema);

				} else {
					$su_schema = str_replace("###contact2###","",$su_schema);
					$su_schema = str_replace("</script>","}</script>",$su_schema);
				}

				if(isset($_POST['combo3']))  {
					$contact3 = "";
					$contact3 = "	 ,{ 
     \"@type\": \"ContactPoint\",\n
     \"contactType\": \"" . $_POST['combo3'] . "\",\n
     \"telephone\": \"" . $_POST['contact3phone'] . "\",\n
     \"email\": \"" . $_POST['contact3email'] . "\"\n
     \n";
					$su_schema = str_replace("###contact3###",$contact3,$su_schema);

				} else {
					$su_schema = str_replace("###contact3###","",$su_schema);
				}




				$wpdb->insert( $wpdb->prefix . 'postmeta', array( 'post_id' => $su_post_id, 'meta_key' => 'su_post_schema', 'meta_value' => $su_schema ), array( '%d', '%s', '%s' ) );
				echo '<div class="alert alert-success"><strong>Success!</strong> Schema Created.</div>';
			}

$su_nounce = wp_create_nonce('delete_');
if(isset($_GET['meta_id'])) {
	if($_GET['nounce'] == $su_nounce) {
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $wpdb->postmeta WHERE meta_id = %d",
				$_GET['meta_id']
			)
		);
	}
	echo '<div class="alert alert-success"><strong>Success!</strong> Schema Deleted.</div>';

}

			$su_customPagHTML     = "";
			$query             = "SELECT * FROM " . $wpdb->prefix . "postmeta JOIN " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "postmeta.post_id = " . $wpdb->prefix . "posts.ID WHERE meta_key = 'su_post_schema'";
			$total_query     = "SELECT COUNT(1) FROM (${query}) AS combined_table";
			$total             = $wpdb->get_var( $total_query );
			$items_per_page = 25;
			$page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
			$offset         = ( $page * $items_per_page ) - $items_per_page;
			$schema_result         = $wpdb->get_results( $query . " ORDER BY meta_id DESC LIMIT ${offset}, ${items_per_page}" );
			$totalPage         = ceil($total / $items_per_page);

			if($totalPage > 1){
				$su_customPagHTML     =  '<div><span>Page '.$page.' of '.$totalPage.' </span><br>'.paginate_links( array(
						'base' => add_query_arg( 'cpage', '%#%' ),
						'format' => '',
						'prev_text' => __('&laquo;'),
						'next_text' => __('&raquo;'),
						'total' => $totalPage,
						'current' => $page
					)).'</div>';
			}
			echo $su_customPagHTML;

			// $schema_result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "postmeta JOIN " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "postmeta.post_id = " . $wpdb->prefix . "posts.ID WHERE meta_key = 'su_post_schema' ORDER BY meta_id DESC");
			// echo $su_nounce;
			echo "<table class=\"tablesorter table\">";
			echo "<tr><th></th></th><th>Post ID</th><th>Title</th><th>Post Type</th><th>Schema</th><th>Delete</th></tr>";

			foreach($schema_result as $su_schema_result){
				echo '<tr><td><a href="post.php?post=' . $su_schema_result->post_id . '&action=edit">Edit</a> | <a href="' . get_permalink($su_schema_result->post_id) . '" target="_blank">View</a></td><td>' . $su_schema_result->post_id . '</td><td>' . $su_schema_result->post_title . '<td>' . $su_schema_result->post_type . '</td><td><textarea rows="5" class="form-control">' .stripslashes_deep($su_schema_result->meta_value) . '</textarea></td><td><a style="color: #fff;" href="admin.php?page=su-schema&meta_id=' . $su_schema_result->meta_id . '&nounce=' . $su_nounce . '" class="btn btn-danger"><i class="fa fa-trash"></i> DELETE</a></td></tr>';

			}
			// echo "</tr>";

			echo "</table>";

		}
	}
}


?>