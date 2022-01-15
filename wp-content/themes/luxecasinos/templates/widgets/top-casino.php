<?php
$count      = get_field( 'sidebar_casino_count', 'options' ) ? get_field( 'sidebar_casino_count', 'options' ) : -1;
$title      = get_field( 'sidebar_casino_title', 'options' );
$casino_top = get_field( 'casino_rating', 'options' );
$casino_ids = $casino_top['casino_rating_2']['rating_item'];
if ( $casino_ids ) : ?>
	<div class="custom-widget custom-widget__casino">
		<p class="custom-widget__title"><?= $title; ?></p>

		<div class="custom-widget__list custom-widget__casino-list">
			<?php $i = 0; ?>
			<?php foreach ( $casino_ids as $casino ) :
				$post = $casino['casino'];
				setup_postdata( $post );
				$casino_link   = get_post_permalink();
				$casino_thumb  = get_post( get_field( 'logo' ) );
				$casino_rating = $casino['rating'] ? $casino['rating'] : get_field( 'rating' );
				?>

				<div class="custom-widget__casino-item">
					<a href="<?= $casino_link; ?>" class="custom-widget__casino-img">
						<img src="<?= kama_thumb_src( 'w=150 &h=150', $casino_thumb->guid ); ?>" class="thumb"
						     title="<?= $casino_thumb->post_title; ?>"
						     alt="<?= get_post_meta( $casino_thumb->ID, '_wp_attachment_image_alt', true ); ?>">

						<?php
						$wh  = 60; // percent-bar width/height px
						$sw  = 4; // stroke width px
						$sc  = '#73CA2F'; // stroke color
						$sbw = 2; // stroke background width px
						$sbc = '#576363'; // stroke background color
						?>
						<div class="percent-bar" style="width: <?= $wh + 8; ?>px; height: <?= $wh + 8; ?>px;">
							<svg>
								<circle cx="<?= ( $wh - $sw ) / 2; ?>" cy="<?= ( $wh - $sw ) / 2; ?>" r="<?= ( $wh - $sw ) / 2; ?>" class="percent-bar-bg"
								        style="stroke-width: <?= $sbw; ?>px; stroke: <?= $sbc; ?>; transform: rotate(-90deg) translate(<?= $sw / 2; ?>px,<?= $sw / 2; ?>px)"></circle>
								<circle cx="<?= ( $wh - $sw ) / 2; ?>" cy="<?= ( $wh - $sw ) / 2; ?>" r="<?= ( $wh - $sw ) / 2; ?>" class="percent-bar-progress"
								        style="stroke-dasharray: <?= ( $wh - $sw ) / 2 * 6.25; ?>; stroke-dashoffset: calc(<?= ( $wh - $sw ) / 2 * 6.25; ?> - (<?= ( $wh - $sw ) / 2 * 6.25; ?> * <?= $casino_rating; ?>) / 100); stroke-width: <?= $sw; ?>px; stroke: <?= $sc; ?>; transform: rotate(-90deg) translate(<?= $sw / 2; ?>px,<?= $sw / 2; ?>px)"></circle>
							</svg>
							<div class="count"><?= number_format( $casino_rating / 10, 1, '.', '' ); ?></div>
						</div>

					</a>

					<div class="custom-widget__casino-nav">
						<a href="<?= $casino_link; ?>" class="btn btn-review" title="Интернет казино <?php the_title(); ?>">Обзор</a>
						<a href="<?= get_field( 'link' ); ?>" class="btn btn-site" title="Играть <?php the_title(); ?> на деньги" target="_blank">Сайт</a>
					</div>
				</div>
				<?php
				if ( $i == $count - 1 ) {
					break;
				} else {
					$i ++;
				}
				?>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif; ?>
