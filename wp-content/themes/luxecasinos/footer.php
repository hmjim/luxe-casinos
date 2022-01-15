<a href="#0" class="btn-top"></a>

<footer id="footer" class="footer__wrapper">
	<div class="footer container">
		<div class="footer__top">
			<?php $logo = get_post( get_field( 'logo_footer', 'options' ) ); ?>
			<a href="/" class="footer__logo">
				<img src="<?= $logo->guid; ?>" title="<?= $logo->post_title; ?>" alt="<?= get_post_meta( $logo->ID, '_wp_attachment_image_alt', true ); ?>">
			</a>

			<nav class="footer__menu footer__menu-1">
				<?php wp_nav_menu( [
					'theme_location' => 'footer_1',
					'container'      => false
				] ); ?>
			</nav>
		</div>

		<div class="footer__bottom">
			<div class="footer__copyright"><?= get_field( 'copyright', 'options' ); ?></div>
			<div class="footer__limit"><?= get_field( 'limit', 'options' ); ?></div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

