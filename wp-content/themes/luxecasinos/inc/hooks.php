<?php
/* включаем поддержку миниатюр */
add_theme_support( 'post-thumbnails' );

/* Автоматический title через хук wp_head() */
add_theme_support( 'title-tag' );

/* Отключаем емоджи */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* HTML теги в описании категорий, меток */
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'pre_link_description', 'wp_filter_kses' );
remove_filter( 'pre_link_notes', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

// Add titles for footer menus
//add_filter('wp_nav_menu_items', 'acf_wp_nav_menu_items_title', 10, 2);
//function acf_wp_nav_menu_items_title( $items, $args ) {
//	$menu = wp_get_nav_menu_object($args->menu);
//	for ( $i = 1; $i < 6; $i++ ) {
//		if ( $args->theme_location == 'footer_'.$i ) {
//			$title = get_field('menu_title', $menu);
//			$html_title = '<li class="footer__menu-item-title"><span>'.$title.'</span></li>';
//			$items = $html_title . $items;
//		}
//	}
//	return $items;
//}

/* Меняем длину excerpt */
add_filter( 'excerpt_length', 'new_excerpt_length' );
function new_excerpt_length( $length ) {
	return 19;
}

/* Меняем в excerpt [...] на Читать дальше */
add_filter( 'excerpt_more', 'new_excerpt_more' );
function new_excerpt_more( $more ) {
	global $post;

	return '';
}

/* Добавляет SVG в список разрешенных для загрузки файлов. */
add_filter( 'upload_mimes', 'svg_upload_allow' );
function svg_upload_allow( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

/* Исправление MIME типа для SVG файлов. */
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ) {

	// WP 5.1 +
	if ( version_compare( $GLOBALS['wp_version'], '5.4.2', '>=' ) ) {
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	} else {
		$dosvg = ( '.svg' === strtolower( substr( $filename, - 4 ) ) );
	}

	// mime тип был обнулен, поправим его
	// а также проверим право пользователя
	if ( $dosvg ) {

		// разрешим
		if ( current_user_can( 'manage_options' ) ) {

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		} // запретим
		else {
			$data['ext'] = $type_and_ext['type'] = false;
		}

	}

	return $data;
}

/* Формирует данные для отображения SVG как изображения в медиабиблиотеке. */
add_filter( 'wp_prepare_attachment_for_js', 'show_svg_in_media_library' );
function show_svg_in_media_library( $response ) {
	if ( $response['mime'] === 'image/svg+xml' ) {
		// Без вывода названия файла
		$response['sizes'] = [
			'medium' => [
				'url' => $response['url'],
			],
		];
	}

	return $response;
}

/* Adding fields to Menu Items https://www.advancedcustomfields.com/resources/adding-fields-menu-items/ */
add_filter( 'wp_nav_menu_objects', 'my_wp_nav_menu_objects', 10, 2 );
function my_wp_nav_menu_objects( $items, $args ) {

	// loop
	foreach ( $items as &$item ) {

		// vars
		$icon = get_field( 'icon', $item );

		// append icon
		if ( $icon ) {

//			$img         = get_post( $icon );
//			$title       = $img->post_title;
//			$alt         = get_post_meta( $img->ID, '_wp_attachment_image_alt', true );
//			$item->title .= ' <img src="' . $img->guid . '" title="' . $title . '" alt="' . $alt . '"/>';
			$img = file_get_contents( $icon );
			$item->title .= $img;

		}

	}

	// return
	return $items;

}

/* Подсчет количества посещений страниц
* Просмотров: <?php echo get_post_meta ($post->ID,'views',true); ?>
---------------------------------------------------------- */
add_action( 'wp_head', 'kama_postviews' );
function kama_postviews() {

	/* ------------ Настройки -------------- */
	$meta_key     = 'views';  // Ключ мета поля, куда будет записываться количество просмотров.
	$who_count    = 1;            // Чьи посещения считать? 0 - Всех. 1 - Только гостей. 2 - Только зарегистрированных пользователей.
	$exclude_bots = 1;            // Исключить ботов, роботов, пауков и прочую нечесть :)? 0 - нет, пусть тоже считаются. 1 - да, исключить из подсчета.

	global $user_ID, $post;
	if ( is_singular() ) {
		$id = (int) $post->ID;
		static $post_views = false;
		if ( $post_views ) {
			return true;
		} // чтобы 1 раз за поток
		$post_views   = (int) get_post_meta( $id, $meta_key, true );
		$should_count = false;
		switch ( (int) $who_count ) {
			case 0:
				$should_count = true;
				break;
			case 1:
				if ( (int) $user_ID == 0 ) {
					$should_count = true;
				}
				break;
			case 2:
				if ( (int) $user_ID > 0 ) {
					$should_count = true;
				}
				break;
		}
		if ( (int) $exclude_bots == 1 && $should_count ) {
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$notbot    = "Mozilla|Opera"; //Chrome|Safari|Firefox|Netscape - все равны Mozilla
			$bot       = "Bot/|robot|Slurp/|yahoo"; //Яндекс иногда как Mozilla представляется
			if ( ! preg_match( "/$notbot/i", $useragent ) || preg_match( "!$bot!i", $useragent ) ) {
				$should_count = false;
			}
		}

		if ( $should_count ) {
			if ( ! update_post_meta( $id, $meta_key, ( $post_views + 1 ) ) ) {
				add_post_meta( $id, $meta_key, 1, true );
			}
		}
	}

	return true;
}

/* Отключаем автоматическую вставку <p> и <br>  для CF7 */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

/* Подключаем CodeMirror для редактора форм CF7*/
add_action( 'admin_print_styles-toplevel_page_wpcf7', function () {

	if ( empty( $_GET['post'] ) ) {
		return;
	}
	// Подключаем редактор кода для HTML.
	$settings = wp_enqueue_code_editor( array( 'type' => 'text/html' ) );
	// Ничего не делаем, если CodeMirror отключен.
	if ( false === $settings ) {
		return;
	}
	// Инициализация редактора для редактирования шаблона формы
	wp_add_inline_script( 'code-editor', sprintf( 'jQuery( function() { wp.codeEditor.initialize( "wpcf7-form", %s ); } );', wp_json_encode( $settings ) ) );
	// Инициализация редактора для редактирования шаблона письма
	wp_add_inline_script( 'code-editor', sprintf( 'jQuery( function() { wp.codeEditor.initialize( "wpcf7-mail-body", %s ); } );', wp_json_encode( $settings ) ) );
} );

add_action( 'wp_enqueue_scripts', 'wpcf7_modal_invalid_js' );
add_action( 'wp_footer', 'wpcf7_modal_invalid_js_inline', 999 );

/* Поключает библиотеку sweetalert.js для создания красивых модальных окон. */
function wpcf7_modal_invalid_js() {
	if( is_single('post') ) {
		wp_enqueue_script( 'sweetalert', 'https://unpkg.com/sweetalert/dist/sweetalert.min.js' );
	}
}

/* Выводит на экран модальное окно при ошибках валидации формы. */
function wpcf7_modal_invalid_js_inline() {

	?>
	<script>
        // Срабатывает при ошибках валидации формы.
        document.addEventListener('wpcf7invalid', function (response) {
            // Запускает модальное окно.
            swal({
                title: "Ошибка!",
                text: response.detail.apiResponse.message,
                icon: "error",
                button: "Закрыть"
            });
        }, false);

        // Срабатывает при успешной отправке формы.
        document.addEventListener('wpcf7mailsent', function (response) {
            // Запускает модальное окно.
            swal({
                title: "Спасибо!",
                text: response.detail.apiResponse.message,
                icon: "success",
                button: "Закрыть"
            });
        }, false);
	</script>

	<style>
		.wpcf7-validation-errors,
		.wpcf7-mail-sent-ok {
			display: none !important;
		}
	</style>
	<?php

}
