<?php
function su_schema_meta_inc() {
	
}




function su_schema_meta_boxes() {
	add_meta_box( 'su-schema-meta', __( 'Schema', 'suschema' ), 'su_schema_meta_show', array(
		'post',
		'page',
		'product'
	) );
}

add_action( 'init', 'su_schema_meta_inc' );
add_action( 'add_meta_boxes', 'su_schema_meta_boxes' );


function su_schema_meta_show( $post ) {

	global $wpdb;
	global $post;
	$old_schema = "";
	global $wpdb;

	$result = $wpdb->get_results ("
    SELECT * 
    FROM  $wpdb->postmeta
        WHERE meta_key LIKE '%_su_rich_snippet%' AND post_id = $post->ID
" );

	if (count($result)> 1){
		$old_schema = "<div class=\"row\">
		<div class=\"col-md-12 well\" style='text-align: center;'><label style='color: red;'>Previous Schema Detected:</label>
		<textarea id=\"oldschema\" rows=\"5\" class=\"form-control\">";

	}
	foreach ( $result as $schemas )
	{

		$old_schema .= $schemas->meta_key . ":" . PHP_EOL . $schemas->meta_value . PHP_EOL . "-----------------" . PHP_EOL;
	}
	if (count($result)> 1) {
		$old_schema .= "</textarea><br><button class=\"btn btn-primary\" onclick=\"copyOldSchema()\">Copy Deprecated Schema to Clipboard</button><br><br> <span class='form-control' style='display: inline-block; width: 300px; height: auto;'> >>> &nbsp;&nbsp;<input style=\"-ms-transform: scale(2);  -moz-transform: scale(2);  -webkit-transform: scale(2);  -o-transform: scale(2); padding: 10px;\" type=\"checkbox\" name=\"oldschema\" value=\"yes\"> &nbsp;&nbsp; <<< <label style='padding-top: 10px;'>Delete Deprecated Schema on Post Update/Save?</label><br>(<i>Recommended</i>)</span><br>POST ID: . " . $post->ID . "</div>
		</div>";
	} else {
		$old_schema = "";
	}

	$su_schema_form = <<< formout
<style>
div.schema_div {
	clear: both;
	margin: 0;
}

label.schema_label {
  width: 200px;
  border-radius: 3px;
  border: 1px solid #eeeeee
}

/* hide input */
input.schema_radio:empty {
	margin-left: -9999px;
}

/* style label */
input.schema_radio:empty ~ label {
	position: relative;
	float: left;
	line-height: 2.5em;
	text-indent: 3.25em;
	margin-top: 2em;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

input.schema_radio:empty ~ label:before {
	position: absolute;
	display: block;
	top: 0;
	bottom: 0;
	left: 0;
	content: '';
	width: 2.5em;
	background: #efeef7;
	border-radius: 3px 0 0 3px;
}

/* toggle hover */
input.schema_radio:hover:not(:checked) ~ label:before {
	content:'\\2714';
	text-indent: .9em;
	color: #000000;
}

input.schema_radio:hover:not(:checked) ~ label {
	color: #888;
}

/* toggle on */
input.schema_radio:checked ~ label:before {
	content:'\\2714';
	text-indent: .9em;
	color: #000;
	background-color: #e49217;
}

input.schema_radio:checked ~ label {
	color: #777;
}

/* radio focus */
input.schema_radio:focus ~ label:before {
	box-shadow: 0 0 0 0px #999;
}
</style>
<script>
jQuery(document).ready(function() {
    var $ = jQuery;
    if ($('.set_custom_images').length > 0) {
        if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $('.set_custom_images').on('click', function(e) {
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
	<div class="container-responsive">
$old_schema
		<div class="row" style="padding-bottom: 10px;">
		<br><br>
			<div class="col-md-12">
			
				<label>Select Schema Type: </label>
				<select class="input-sm" style="font-size: small;" data-bind="options: schemas, value: schema, optionsText: 'title', valueAllowUnset: true">
					<optgroup label="Select Schema">

					</optgroup>
				</select>
			</div>
			</div>
<div style="padding:10px;background-color: #c4e3f3;text-align: center; border-color: #002a80;">
<h4>If you need to use an image for image URL fields, please open the Media Manager using the button below. You will need to select "Copy Link" in the Media Manager.</h4>
<br>
    <button class="set_custom_images btn btn-primary"><i class="fa fa-image"></i> Open Media Library</button>
    </div>
		</div>
		<div class="row" style="padding: 49px;">
				 <div id="ko-ready" class="readout" style="display:none; max-width: 60%;">

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

				<template id="country">
					<select class="input-sm form-control" data-bind="options: choices, value: value, optionsText: 'countryName', optionsValue: 'countryCode', optionsCaption: 'Choose...', valueAllowUnset: true">
				</template>

				<template id="string">
					<input class="form-control input-sm" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
					<span style="max-width: 100%; padding: 10px;" class="help-block" data-bind="html: info"></span>

				</template>
				
				
				<template id="standardDate">
					<input class="form-control input-sm datepick" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
					<span style="max-width: 80%;" class="help-block" data-bind="html: info"></span>

				</template>
				
				
				<template id="logoimg">
					<input id="upload_image" class="form-control input-sm" name=upload_image" type="text" data-bind="value: value, attr: {placeholder:placeholder}"  />
					<span style="max-width: 80%;" class="help-block" data-bind="html: info"></span>

				</template>

				<template id="textbox">
					<textarea type="text" data-bind="value: value, attr: {placeholder:placeholder}"></textarea>
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
				
<div style="padding: 10px; border-style: solid; border-width: 1px; border-color: #EEE; box-shadow: silver;" class="row schema_div">
			<div class="row">
<div class="col-md-2">
<input type="radio" name="schema_action" id="radio1" class="schema_radio" value="noaction" checked/>
<label class="schema_label" for="radio1">Take No Action</label>
</div>

<div class="col-md-2">
<input type="radio" name="schema_action" id="radio2" class="schema_radio" value="replace"/>
<label class="schema_label" for="radio2">Replace</label>
</div>

<div class="col-md-2">
<input type="radio" name="schema_action" id="radio3" class="schema_radio" value="append"/>
<label class="schema_label" for="radio3">Append</label>
</div>

<div class="col-md-2">
<input type="radio" name="schema_action" id="radio4" class="schema_radio" value="delete"/>
<label class="schema_label" for="radio4">Delete</label>
</div>
</div>
<br><br>
				<strong>If you wish to create schema manually, add your schema to the box below, without selecting a schema type from the drop down.</strong>



					<textarea class="input-sm code form-control" rows="10" id="su_code" name="su_code" data-bind="text: jsonLD" style="background-color: rgb(244,244,244);"></textarea>
				
				<!-- <iframe style="padding-top: 10px;" width="100%" id="su_preview" onload="this.style.height=this.contentDocument.body.scrollHeight;">></iframe> -->
			</div>

			  


</div>
</div>
	<script>
	function copyOldSchema() {
  var copyText = document.getElementById("oldschema");
  copyText.select();
  document.execCommand("copy");
  alert("Copied deprecated schema to clipboard");
}
</script>		


    <script>
    //<![CDATA[
    (function () {
        var c = document.body.classList;
        c.remove('no-js');
        c.add('js');
    })();
    //]]>
</script>


<script>
jQuery(document).ready( function( $ ) {

    $('#upload_image_button').click(function() {

        formfield = $('#upload_image').attr('name');
        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
        window.send_to_editor = function(html) {
           imgurl = $(html).attr('src');
           $('#upload_image').val(imgurl);
           tb_remove();
        }

        return false;
    });

});
</script>
formout;
	echo $su_schema_form;
	// $su_nounce = wp_create_nonce('delete_');
	global $wpdb;
	$su_customPagHTML = "";
	$query            = "SELECT * FROM " . $wpdb->prefix . "postmeta JOIN " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "postmeta.post_id = " . $wpdb->prefix . "posts.ID WHERE meta_key = 'su_post_schema' and " . $wpdb->prefix . "postmeta.post_id = " . $post->ID . "";
	$schema_result    = $wpdb->get_results( $query . " ORDER BY meta_id DESC" );


	echo $su_customPagHTML;

	echo "<table class=\"tablesorter table\">";
	echo "<tr><th>Post ID</th><th>Title</th><th>Post Type</th><th>Schema</th></tr>";

	foreach ( $schema_result as $su_schema_result ) {
		echo '<tr></td><td>' . $su_schema_result->post_id . '</td><td>' . $su_schema_result->post_title . '</td><td>' . $su_schema_result->post_type . '</td><td><textarea name="current_schema" rows="5" class="form-control">' . stripslashes_deep( $su_schema_result->meta_value ) . '</textarea></td></tr>';

	}
	// echo "</tr>";

	echo "</table>";

}

function su_schema_meta_save( $post_id ) {
	global $wpdb;
	// file_put_contents( 'action.txt', $_POST['su_code'] );
	// $value = get_post_meta( $post_id, 'su_post_schema', true );

	if ( array_key_exists( 'oldschema', $_POST ) ) {
		if ( $_POST['oldschema'] == "yes" ) {
			$sql = "DELETE FROM $wpdb->postmeta WHERE post_id = " . $post_id . " AND meta_key LIKE '%_su_rich_snippet%'";
			$wpdb->query($sql);
		}
	}


	if(isset($_POST['su_code'])) {
		$su_markup = "";
		$su_markup = $_POST['su_code'];


		if ( isset( $_POST['combo2'] ) && ! empty( $_POST['su_code'] ) ) {
			$contact2  = "";
			$contact2  = "	 },{ 
     \"@type\": \"ContactPoint\",\n
     \"contactType\": \"" . $_POST['combo2'] . "\",\n
     \"telephone\": \"" . $_POST['contact2phone'] . "\",\n
     \"email\": \"" . $_POST['contact2email'] . "\"\n
     }\n";
			$su_markup = str_replace( "###contact2###", $contact2, $su_markup );

		} else {
			$su_markup = str_replace( "###contact2###", "", $su_markup );
			$su_markup = str_replace( "</script>", "}</script>", $su_markup );

		}

		if ( isset( $_POST['combo3'] ) && ! empty( $_POST['su_code'] ) ) {
			$contact3  = "";
			$contact3  = "	 ,{ 
     \"@type\": \"ContactPoint\",\n
     \"contactType\": \"" . $_POST['combo3'] . "\",\n
     \"telephone\": \"" . $_POST['contact3phone'] . "\",\n
     \"email\": \"" . $_POST['contact3email'] . "\"\n
     \n";
			$su_markup = str_replace( "###contact3###", $contact3, $su_markup );

		} else {
			$su_markup = str_replace( "###contact3###", "", $su_markup );
		}
	}

	if ( array_key_exists( 'schema_action', $_POST ) ) {
		if ( $_POST['schema_action'] == "noaction" && empty($_POST['su_code'])) {
			return;
		} elseif ( $_POST['schema_action'] == "replace" ) {
			update_post_meta(
				$post_id,
				'su_post_schema',
				$su_markup
			);
		} elseif ( $_POST['schema_action'] == "append" ) {
			update_post_meta(
				$post_id,
				'su_post_schema',
				$_POST['current_schema'] . $su_markup

			);
		} elseif ( $_POST['schema_action'] == "delete" ) {

			delete_post_meta( $post_id, 'su_post_schema' );


		} elseif(isset($_POST['su_code']) && $_POST['su_code'] != "") {

			update_post_meta(
				$post_id,
				'su_post_schema',
				$su_markup
			);
		}




	}



}


add_action( 'save_post', 'su_schema_meta_save' );


?>