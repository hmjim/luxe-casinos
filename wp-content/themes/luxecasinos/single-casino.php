<?php get_header(); ?>
<?php the_post(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<?php
$casino_thumb    = get_post( get_post_thumbnail_id( get_the_ID() ) );
$casino_rating       = get_field( 'rating' );
$casino_rating_count = number_format( $casino_rating / 20, 0, '', '' );
$casino_bonus    = get_field( 'bonus' );
$casino_pays     = get_the_terms( get_the_ID(), 'currency' );
$casino_features = get_field( 'features' );
$casino_site     = get_field( 'link' );
?>

<div class="content__wrapper no-padding mb40">
	<div class="content container">
		<div class="content__inner">
			<div class="content__main no-sidebar">

				<div class="rating-casino-single page-casino-single">

					<div class="rating-casino-single__item">
						<div class="rating-casino-single__logo">
							<div class="rating-casino-single__thumb">
							    <a href="<?= get_field( 'link' ); ?>" target="_blank">
								<img src="<?= kama_thumb_src( 'w=123 &h=75', $casino_thumb->guid ); ?>" class="thumb"
								     title="<?= $casino_thumb->post_title; ?>"
								     alt="<?= get_post_meta( $casino_thumb->ID, '_wp_attachment_image_alt', true ); ?>">
								</a>
							</div>
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

						<div class="rating-casino-single__pays">
							<p class="rating-casino-single__pays-title">Способы выплаты</p>
							<ul class="rating-casino-single__pays-list">
								<?php foreach ( $casino_pays as $term ): ?>
									<?php $term_logo = get_field( 'logo', $term ); ?>
									<li>
										<img src="<?= kama_thumb_src( 'h=30 &nocrop=true', $term_logo['ID'] ); ?>" title="<?= $term_logo['title']; ?>"
										     alt="<?= $term_logo['alt']; ?>">
									</li>
								<?php endforeach; ?>
							</ul>
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
							<a href="<?= get_field( 'link' ); ?>" class="btn btn-play" title="Играть в казино" target="_blank">На сайт казино</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php get_template_part( 'templates/anchor-menu' ); ?>
<div class="content__wrapper no-padding mb40">
	<div class="content container">
		<div class="content__inner">
			<div class="content__main no-sidebar">
                <div class="main-slot__btn">
                	<a href="<?= get_field( 'link' ); ?>" class="btn btn-main btn-fullwidth" target='_blank'><span>Играть в казино</span></a>
                </div>
			</div>
		</div>
	</div>
</div>
<div class="content__wrapper no-padding mb40">
	<div class="content container">
		<div class="content__inner">
			<div class="content__main">
				<?php get_template_part( 'templates/rating-casino-single' ); ?>
			</div>
		</div>
	</div>
</div>
<style>
.rating-casino-single__nav .btn-play {
    width: 180px;
    white-space:nowrap;
}
.main-slot__btn {
    padding: 20px 0px;
    width: 75%;
}
</style>
<?php get_footer(); ?>

