<?php

/* Регистрация произвольного типа записи */
add_action( 'init', 'scf_register_casino_pt' );
function scf_register_casino_pt() {
	$labels = array(
		'name'               => 'Казино',
		'singular_name'      => 'Казино',
		'add_new'            => 'Добавить казино',
		'add_new_item'       => 'Добавить новое казино',
		'edit_item'          => 'Редактировать Казино',
		'new_item'           => 'Новое казино',
		'all_items'          => 'Все казино',
		'view_item'          => 'Просмотр казино на сайте',
		'search_items'       => 'Искать казино',
		'not_found'          => 'Казино не найдено.',
		'not_found_in_trash' => 'В корзине нет казино.',
		'menu_name'          => 'Казино'
	);
	$args   = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui'       => true,
		'has_archive'   => false,
		'menu_icon'     => 'dashicons-admin-post',
		'menu_position' => 8,
		'supports'      => array( 'title', 'comments', 'author', 'thumbnail' )
	);
	register_post_type( 'casino', $args );
}

/* Регистрация произвольного типа записи */
add_action( 'init', 'scf_register_news_pt' );
function scf_register_news_pt() {
	$labels = array(
		'name'               => 'Новости',
		'singular_name'      => 'Новость',
		'add_new'            => 'Добавить новость',
		'add_new_item'       => 'Добавление новости',
		'edit_item'          => 'Редактирование новости',
		'new_item'           => 'Новая новость',
		'all_items'          => 'Все новости',
		'view_item'          => 'Смотреть новость',
		'search_items'       => 'Найти новость',
		'not_found'          => 'Не найдено',
		'not_found_in_trash' => 'Не найдено в корзине',
		'menu_name'          => 'Новости',
	);
	$args   = array(
		'labels'        => $labels,
		'public'        => true,
		'show_ui'       => true,
		'has_archive'   => true,
		'menu_icon'     => 'dashicons-admin-post',
		'menu_position' => 9,
		'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);
	register_post_type( 'news', $args );
}
