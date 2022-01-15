<?php
$casino_square_thumb = get_post( get_field( 'logo' ) );
$casino_rating       = get_field( 'rating' );
$ref_banner_title    = get_field( 'casino_single_referal_title', 'options' );
$ref_banner_descr    = get_field( 'casino_single_referal_descr', 'options' );
?>
<div class="ref-banner__wrapper">
	<div class="ref-banner container">
		<p class="page_title"><?= $ref_banner_title; ?></p>
		<div class="page_descr the_content"><?= $ref_banner_descr; ?></div>

		<div class="ref-banner__inner">

			<div class="ref-banner__info">
				<div class="ref-banner__logo">
					<div class="percent-bar">
						<svg>
							<circle cx="71" cy="71" r="71" style="stroke-dashoffset: calc(446 - (446 * <?= $casino_rating; ?>) / 100)"></circle>
						</svg>
						<img src="<?= kama_thumb_src( 'w=120 &h=120', $casino_square_thumb->guid ); ?>" class="thumb"
						     title="<?= $casino_square_thumb->post_title; ?>"
						     alt="<?= get_post_meta( $casino_square_thumb->ID, '_wp_attachment_image_alt', true ); ?>">
					</div>
				</div>

				<div class="ref-banner__bonus">
					<p class="ref-banner__bonus-count"><i></i><span><?php the_field( 'bonus' ); ?></span></p>
					<p class="ref-banner__bonus-title"><?php the_field('casino_single_referal_text', 'options'); ?></p>
				</div>
			</div>

			<?php $link = get_field('casino_single_rating_link', 'options') ? get_field('casino_single_rating_link', 'options') : '/'; ?>
			<div class="ref-banner__nav">
				<a href="<?= $link; ?>" class="btn-light">Вернуться в ТОП</a>
				<a href="<?php the_field( 'link' ); ?>" class="btn btn-site">Получить бонус</a>
			</div>

		</div>
	</div>
</div>
