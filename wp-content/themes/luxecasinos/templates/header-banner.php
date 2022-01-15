<?php
$banner       = get_field( 'banner', 'options' );
$banner_title = get_field( 'banner_title', 'options' );
$banner_descr = get_field( 'banner_descr', 'options' );
?>
<div class="banner__wrapper">
	<div class="banner container">
		<div class="banner__inner">
			<div class="banner__text">
				<?php if ( $banner_title ) { ?>
					<p class="banner__title"><?= $banner_title; ?></p>
				<?php } ?>
				<?php if ( $banner_descr ) { ?>
					<p class="banner__descr"><?= $banner_descr; ?></p>
				<?php } ?>
			</div>
			<div class="banner__thumb">
				<?php $item_img = get_post( $banner ); ?>
				<img src="<?= kama_thumb_src( 'w=364 &h=320', $banner ); ?>" class="banner__thumb-img"
				     title="<?= $item_img->post_title; ?>"
				     alt="<?= get_post_meta( $item_img->ID, '_wp_attachment_image_alt', true ); ?>">
			</div>
		</div>
	</div>
</div>
