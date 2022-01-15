<?php

/* ACF Options page */
if ( function_exists( 'acf_add_options_page' ) ) {
	$option_page = acf_add_options_page( array(
		'page_title' => 'Настройки сайта',
		'menu_title' => 'Настройки сайта',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
}

/* Функция вывода пагинации */
function pagination() {

	global $wp_query;
	$big = 999999999;

	if ( $wp_query->max_num_pages > 1 ) :
		?>
		<div class="pagination">
			<?php echo paginate_links( array(
				'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ), // что заменяем в формате ниже
				'format'             => '?paged=%#%', // формат, %#% будет заменено
				'current'            => max( 1, get_query_var( 'paged' ) ), // текущая страница, 1, если $_GET['page'] не определено
				'type'               => 'list', // ссылки в ul
				'prev_text'          => '<span>Предыдущая</span>', // текст назад
				'next_text'          => '<span>Следующая</span>', // текст вперед
				'total'              => $wp_query->max_num_pages, // общие кол-во страниц в пагинации
				'show_all'           => false, // не показывать ссылки на все страницы, иначе end_size и mid_size будут проигнорированны
				'end_size'           => 2, //  сколько страниц показать в начале и конце списка (12 ... 4 ... 89)
				'mid_size'           => 2, // сколько страниц показать вокруг текущей страницы (... 123 5 678 ...).
				'add_args'           => false, // массив GET параметров для добавления в ссылку страницы
				'add_fragment'       => '', // строка для добавления в конец ссылки на страницу
				'before_page_number' => '', // строка перед цифрой
				'after_page_number'  => '' // строка после цифры
			) ); ?>
		</div>
	<?php
	endif;
}

/**
 * Склонение слова после числа.
 *
 * Примеры вызова:
 * num_decline( $num, 'книга,книги,книг' )
 * num_decline( $num, [ 'книга','книги','книг' ] )
 * num_decline( $num, 'книга', 'книги', 'книг' )
 * num_decline( $num, 'книга', 'книг' )
 *
 * @param int|string $number Число после которого будет слово. Можно указать число в HTML тегах.
 * @param string|array $titles Варианты склонения или первое слово для кратного 1.
 * @param string $param2 Второе слово, если не указано в параметре $titles.
 * @param string $param3 Третье слово, если не указано в параметре $titles.
 *
 * @return string 1 книга, 2 книги, 10 книг.
 *
 * @version 2.0
 */
function num_decline( $number, $titles, $param2 = '', $param3 = '' ) {

	if ( $param2 ) {
		$titles = [ $titles, $param2, $param3 ];
	}

	if ( is_string( $titles ) ) {
		$titles = preg_split( '/, */', $titles );
	}

	if ( empty( $titles[2] ) ) {
		$titles[2] = $titles[1];
	} // когда указано 2 элемента

	$cases = [ 2, 0, 1, 1, 1, 2 ];

	$intnum = abs( intval( strip_tags( $number ) ) );

	return "<span>$number</span> " . $titles[ ( $intnum % 100 > 4 && $intnum % 100 < 20 ) ? 2 : $cases[ min( $intnum % 10, 5 ) ] ];
}

/* Кнопка в текстовом редакторе */
add_action( 'init', 'rndm_button' );
function rndm_button() {
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_external_plugins', 'rndm_plugin' );
		add_filter( 'mce_buttons_2', 'rndm_register_button' );
	}
}

function rndm_plugin( $plugin_array ) {
	$plugin_array['rndm'] = get_bloginfo( 'template_url' ) . '/assets/js/newbuttons.js';

	return $plugin_array;
}

function rndm_register_button( $buttons ) {
	array_push( $buttons, "screen" );

	return $buttons;
}

add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing

	if ( wp_attachment_is_image( $post_ID ) ) {

		$my_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ', $my_image_title );

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords( strtolower( $my_image_title ) );

		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'         => $post_ID,            // Specify the image (ID) to be updated
			'post_title' => $my_image_title,        // Set image Title to sanitized title
//			'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
//			'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );

	}
}

function percent_bar( $casino_rating, $width, $stroke_width, $stroke_color, $stroke_background_width = false, $stroke_background_color = false ) {

	$wh = $width; // percent-bar width/height px
	$sw = $stroke_width; // stroke width px
	$sc = $stroke_color; // stroke color
	if ( $stroke_background_width && $stroke_background_color ) {
		$sbw = $stroke_background_width; // stroke background width px
		$sbc = $stroke_background_color; // stroke background color
	}
	$html = '<div class="percent-bar" style="width:' . $wh . 'px; height: ' . $wh . 'px;">';
	$html .= '<svg>';
	if ( $stroke_background_width && $stroke_background_color ) {
		$html .= '<circle cx="' . ( ( $wh - $sw ) / 2 ) . '" cy="' . ( ( $wh - $sw ) / 2 ) . '" r="' . ( ( $wh - $sw ) / 2 ) . '" class="percent-bar-bg" style="stroke-width: ' . $sbw . 'px; stroke: ' . $sbc . 'px; transform: rotate(-90deg) translate(' . ( $sw / 2 ) . 'px,' . ( $sw / 2 ) . 'px)"></circle>';
	}
	$html .= '<circle cx="' . ( ( $wh - $sw ) / 2 ) . '" cy="' . ( ( $wh - $sw ) / 2 ) . '" r="' . ( ( $wh - $sw ) / 2 ) . '" class="percent-bar-progress" style="stroke-dasharray: ' . ( ( $wh - $sw ) / 2 * 6.25 ) . '; stroke-dashoffset: calc(' . ( ( $wh - $sw ) / 2 * 6.25 ) . ' - ' . ( ( $wh - $sw ) / 2 * 6.25 ) . ' * ' . $casino_rating . ') / 100); stroke-width: ' . $sw . 'px; stroke: ' . $sc . '; transform: rotate(-90deg) translate(' . ( $sw / 2 ) . 'px,' . ( $sw / 2 ) . 'px)"></circle>';
	$html .= '</svg>';
	$html .= '<div class="count">' . number_format( $casino_rating / 10, 1, '.', '' ) . '</div>';
	$html .= '</div>';

	return $html;
}

function removeHomeUrl($input) {
    $mass = explode("/", $input);
    $del_url = $mass[0]."//".$mass[2];
    $urlcorrect = $mass[0]."//".$_SERVER['HTTP_HOST'];
    preg_match('/(cdnjs|googleapi)/', $del_url, $match);
    if(!$match) {
        $input = str_replace($del_url, $urlcorrect, $input);
    }
    return $input;
}
add_filter( 'plugins_url', 'removeHomeUrl' );
add_filter( 'the_permalink', 'removeHomeUrl' );
add_filter( 'template_directory_uri', 'removeHomeUrl' );
add_filter( 'bloginfo_url', 'removeHomeUrl' );
add_filter( 'script_loader_src', 'removeHomeUrl' );
add_filter( 'style_loader_src', 'removeHomeUrl' );
add_filter( 'category_link', 'removeHomeUrl' );
add_filter( 'page_link', 'removeHomeUrl' );
add_filter( 'post_link', 'removeHomeUrl' );
add_filter( 'term_link', 'removeHomeUrl' );
add_filter( 'tag_link', 'removeHomeUrl' );