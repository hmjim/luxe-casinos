<?php $rating = get_field( 'casino_rating', 'options' ); ?>
<div class="row rating-row">

	<?php $i = 1; ?>
	<?php foreach ( $rating as $casino ) { ?>
		<div class="rating rating-<?= $i; ?>">
			<div class="rating__inner">
				<p class="rating__title"><?= $casino['rating_title']; ?></p>
				<?php foreach ( $casino['rating_item'] as $item ) {
					$post = get_post( $item['casino'] );
					setup_postdata( $post );
					$casino_year   = get_field( 'year' );
					$casino_rating = $item['rating'] ? $item['rating'] : get_field( 'rating' );
					?>
					<div  class="rating__item">
						<div class="rating__item-thumb">
							<a href="<?php the_field( 'link' ); ?>" target="_blank">
						   <img src="<?= kama_thumb_src( 'w=100 &nocrop' ); ?>" alt="Онлайн казино <?php the_title(); ?>" title="Онлайн казино <?php the_title(); ?>">
						   </a>
						</div>
						<div class="rating__item-text">
							<a href="<?php echo get_permalink(); ?>">
							<div class="rating__item-title"><?php the_title(); ?></div>
							<div class="rating__item-year"><?= $casino_year; ?></div>
							</a>
						</div>
						<div class="rating__item-rate">
							<a href="<?php echo get_permalink(); ?>">
							<?php
							$wh  = 60; // percent-bar width/height px
							$sw  = 4; // stroke width px
							$sc  = '#73CA2F'; // stroke color
							$sbw = 2; // stroke background width px
							$sbc = '#576363'; // stroke background color
							?>
							<div class="percent-bar" style="width: <?= $wh; ?>px; height: <?= $wh; ?>px;">
								<svg>
									<circle cx="<?= ( $wh - $sw ) / 2; ?>" cy="<?= ( $wh - $sw ) / 2; ?>" r="<?= ( $wh - $sw ) / 2; ?>" class="percent-bar-bg"
									        style="stroke-width: <?= $sbw; ?>px; stroke: <?= $sbc; ?>; transform: rotate(-90deg) translate(<?= $sw / 2; ?>px,<?= $sw / 2; ?>px)"></circle>
									<circle cx="<?= ( $wh - $sw ) / 2; ?>" cy="<?= ( $wh - $sw ) / 2; ?>" r="<?= ( $wh - $sw ) / 2; ?>" class="percent-bar-progress"
									        style="stroke-dasharray: <?= ( $wh - $sw ) / 2 * 6.25; ?>; stroke-dashoffset: calc(<?= ( $wh - $sw ) / 2 * 6.25; ?> - (<?= ( $wh - $sw ) / 2 * 6.25; ?> * <?= $casino_rating; ?>) / 100); stroke-width: <?= $sw; ?>px; stroke: <?= $sc; ?>; transform: rotate(-90deg) translate(<?= $sw / 2; ?>px,<?= $sw / 2; ?>px)"></circle>
								</svg>
								<div class="count"><?= number_format( $casino_rating / 10, 1, '.', '' ); ?></div>
							</div>
							</a>
						</div>
					</div>
				<?php } ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<?php $i++; ?>
	<?php } ?>

</div>
<style>
.rating__item a{
	text-decoration:none;
	color:white !important;
}
</style>