<?php

/* Константы */
define( "THEME_DIR", get_template_directory_uri() . "/" );
define( "THEME_INC", get_template_directory_uri() . "/inc/" );
define( "THEME_CSS", get_template_directory_uri() . "/assets/css/" );
define( "THEME_JS", get_template_directory_uri() . "/assets/js/" );
define( "THEME_IMG", get_template_directory_uri() . "/assets/img/" );

/* Подключаем все доп.файлы */
load_template( dirname( __FILE__ ) . '/inc/debug/ref.php', true );
load_template( dirname( __FILE__ ) . '/inc/plugins/kama-thumbnail/kama_thumbnail.php', true );
load_template( dirname( __FILE__ ) . '/inc/helpers.php', true );
load_template( dirname( __FILE__ ) . '/inc/hooks.php', true );
load_template( dirname( __FILE__ ) . '/inc/post_types.php', true );
load_template( dirname( __FILE__ ) . '/inc/taxonomies.php', true );
load_template( dirname( __FILE__ ) . '/inc/shortcodes.php', true );
load_template( dirname( __FILE__ ) . '/inc/ajax.php', true );
load_template( dirname( __FILE__ ) . '/inc/menu_and_sidebars.php', true );
load_template( dirname( __FILE__ ) . '/inc/enqueue_scripts.php', true );
load_template( dirname( __FILE__ ) . '/inc/template-functions.php', true );

//Код любит чистоту

add_action('acf/save_post', 'my_acf_save_post');
function my_acf_save_post( $post_id ) {
	if( $_SERVER['REQUEST_URI'] == '/wp-admin/admin.php?page=theme-general-settings'){

		file_put_contents(get_home_path() . 'reffers.json', json_encode(get_field('reffer', 'option')));
	}

}

$ref_json = json_decode(file_get_contents( 'https://' . $_SERVER['HTTP_HOST'] . '/reffers.json'));


foreach ($ref_json as $ref_key => $ref_val){
	if($ref_val->name == $_SERVER['REQUEST_URI']){
		header('Location:' . $ref_val->link);
		exit;
	}
}