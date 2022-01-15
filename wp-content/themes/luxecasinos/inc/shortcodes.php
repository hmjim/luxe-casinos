<?php

add_shortcode( 'screen', 'add_screens' );
function add_screens() {
	$html = '';
	if ( have_rows( 'screens' ) ):
		$html .= '<div class="screens-box">';
		$html .= '<div class="screens-carousel swiper-container">';
		$html .= '<div class="swiper-wrapper">';
		while ( have_rows( 'screens' ) ): the_row();
			$thumb     = get_post( get_sub_field( 'image' ) );
			$image_alt = get_post_meta( get_sub_field( 'image' ), '_wp_attachment_image_alt', true );
			$link      = wp_get_attachment_url( get_sub_field( 'image' ) );
			$html      .= '<div class="swiper-slide">';
			$html      .= '<a href="' . $link . '" class="cboxElement">';
			$html      .= '<img src="' . kama_thumb_src( "w=249 &h=162", $link ) . '" title="' . $thumb->post_title . '" alt="' . $image_alt . '" class="colorbox-99991"></a></div>';
		endwhile;
		$html .= '</div>';
		$html .= '<div class="screens-pagination"></div>';
		$html .= '</div>';
		$html .= '</div>';
	endif;

	return $html;
}

// Добавляем поддержку шорткодов в ACF полях
add_filter('acf/format_value/type=text', 'copy_acf_format_value', 10, 3);
function copy_acf_format_value( $value, $post_id, $field ) {

	// Render shortcodes in all textarea values.
	return do_shortcode( $value );
}
// Шорткод вывода текущего года
add_shortcode('year', 'copy_year_func');
function copy_year_func() {
	$year = date('Y');
	return $year;
}
// Шорткод вывода домена
add_shortcode('site', 'copy_site_func');
function copy_site_func() {
	$site = $_SERVER['HTTP_HOST'];
	return $site;
}
// Шорткод вывода заголовка
add_shortcode('title', 'scf_title_func');
function scf_title_func() {
	$title = get_the_title();
	return $title;
}
