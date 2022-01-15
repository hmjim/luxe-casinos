<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<?php $favicon = get_field( 'favicon', 'options' ) ? get_field( 'favicon', 'options' ) : THEME_IMG . 'favicon/favicon.ico'; ?>
	<link rel="shortcut icon" type="image/x-icon" href="<?= $favicon; ?>">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="header__wrapper">
	<div class="header container">
		<div class="header__inner">
			<div class="header__logo">
				<?php $logo = get_post( get_field( 'logo_header', 'options' ) ); ?>
				<a href="/"><img src="<?= $logo->guid; ?>" title="<?= $logo->post_title; ?>" alt="<?= get_post_meta( $logo->ID, '_wp_attachment_image_alt', true ); ?>"></a>
			</div>

			<span class="burger js-toggle-btn" data-toggle-container="header__menu"></span>
			<nav class="header__menu">
				<?php wp_nav_menu( [
					'theme_location' => 'main',
					'container'      => false
				] ); ?>
			</nav>
		</div>
	</div>
</header>
