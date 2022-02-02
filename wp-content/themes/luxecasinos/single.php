<?php get_header(); ?>
<?php the_post(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">

			<main class="content__main">
				<article>
					<div class="the_content"><?php the_field( 'seo_text' ); ?></div>
					<?php $page_slots_text = get_field( 'page_top_descr' ); ?>
					<?php if ( $page_slots_text ) { ?>
						<div class="the_content"><?= $page_slots_text; ?></div>
					<?php } ?>

					<?php
					//$home_link     = get_field( 'home_link' );
					$home_link     = get_field('ssylka_na_zal', 'category_1');
					$slot_btn_text = get_field( 'slot_btn_text', 'options' );
					?>
					<div class="main-slot__wrapper">
						<div class="main-slot js-slot">
							<div class="main-slot__inner">
								<div class="main-slot__header"><?php if ( function_exists( 'the_ratings' ) ) {
										the_ratings();
									} ?></div>

								<iframe src="<?php the_field( 'iframe' ) ?>" width="100%" height="477"></iframe>

								<div class="main-slot__btn">
									<a href="<?= $home_link; ?>" class="btn btn-main btn-fullwidth"><span><?= $slot_btn_text; ?></span></a>
								</div>
							</div>
						</div>
					</div>

					<div class="the_content"><?php the_content(); ?></div>

					<?php
					$count      = get_field( 'slot_single_casino_count', 'options' ) ? get_field( 'slot_single_casino_count', 'options' ) : - 1;
					$title      = get_field( 'slot_single_casino_title', 'options' );
					$casino_top = get_field( 'casino_rating', 'options' );
					$casino_ids = $casino_top['casino_rating_2']['rating_item'];
					if ( $casino_ids ) : ?>
						<div class="rating-casino-single">
							<h3 class="rating-casino-single__title page_title"><?= $title; ?></h3>

							<div class="rating-casino-single__list">
								<?php $count_i = 0; ?>
								<?php foreach ( $casino_ids as $casino ) :
									$post = $casino['casino'];
									setup_postdata( $post );
									$casino_link         = get_post_permalink();
									$casino_thumb        = get_post( get_field( 'logo' ) );
									$casino_rating       = $casino['rating'] ? $casino['rating'] : get_field( 'rating' );
									$casino_rating_count = number_format( $casino_rating / 20, 0, '', '' );
									?>

									<div class="rating-casino-single__item">
										<div class="rating-casino-single__logo">
											<a href="<?= $casino_link; ?>" class="rating-casino-single__thumb">
												<img src="<?= kama_thumb_src( 'w=123 &h=75', $casino_thumb->guid ); ?>" class="thumb"
												     title="<?= $casino_thumb->post_title; ?>"
												     alt="<?= get_post_meta( $casino_thumb->ID, '_wp_attachment_image_alt', true ); ?>">
											</a>
											<div class="rating-casino-single__rate">
												<div class="rating-casino-single__rate-count"><span><?= $casino_rating_count; ?></span>/5</div>
												<div class="rating-casino-single__rate-stars">
													<?php
													for ( $i = 1; $i <= $casino_rating_count; $i ++ ) {
														echo file_get_contents( THEME_IMG . 'svg/star-filled.svg' );
													}
													if ( $casino_rating_count < 5 ) {
														for ( $i = 1; $i <= 5 - $casino_rating_count; $i ++ ) {
															echo file_get_contents( THEME_IMG . 'svg/star-empty.svg' );
														}
													}
													?>
												</div>
											</div>
										</div>

										<?php
										$casino_bonus_name = get_field( 'bonus_name' );
										$casino_bonus      = get_field( 'bonus' );
										?>
										<div class="rating-casino-single__bonus">
											<div class="rating-casino-single__bonus-title"><?= $casino_bonus_name; ?></div>
											<div class="rating-casino-single__bonus-descr"><?= $casino_bonus; ?></div>
										</div>

										<?php
										$casino_features = get_field( 'features' );
										?>
										<ul class="rating-casino-single__features">
											<?php foreach ( $casino_features as $casino_feature ) { ?>
												<li><?= $casino_feature['text']; ?></li>
											<?php } ?>
										</ul>

										<div class="rating-casino-single__nav">
											<a href="<?= get_field( 'link' ); ?>" class="btn btn-play" title="Играть в <?php the_title(); ?>" target="_blank">Играть</a>
											<a href="<?= $casino_link; ?>" class="btn btn-more" title="Обзор казино <?php the_title(); ?>" >Обзор</a>
										</div>
									</div>
									<?php
									if ( $count_i == $count - 1 ) {
										break;
									} else {
										$count_i ++;
									}
									?>
								<?php endforeach; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>
					<?php endif; ?>
				</article>
			</main>

			<?php get_sidebar(); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
