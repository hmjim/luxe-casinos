<?php
$count     = get_field( 'sidebar_related_slots_count', 'options' ) ? get_field( 'sidebar_related_slots_count', 'options' ) : - 1;
$title     = get_field( 'sidebar_related_slots_title', 'options' );
$posts     = get_posts( array(
	'numberposts' => $count,
	'post_type'   => 'post',
	'orderby'     => 'rand',
	'exclude'     => array( get_the_ID() )
) );
if ( $posts ) : ?>
	<div class="custom-widget custom-widget__slot-related">
		<p class="custom-widget__title"><?= $title; ?></p>

		<div class="custom-widget__list custom-widget__slot-related-list">
			<?php foreach ( $posts as $post ) {
				setup_postdata( $post );
				$thumb     = get_post( get_post_thumbnail_id() );
				$image_alt = get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true );
				?>
				<div class="custom-widget__slot-related-item">
					<a href="<?php the_permalink(); ?>" class="custom-widget__slot-related-img">
						<img src="<?= kama_thumb_src( 'w=75 &h=75' ); ?>" title="<?= $thumb->post_title; ?>" alt="<?= $image_alt; ?>">
					</a>
					<div class="custom-widget__slot-related-text">
						<p class="custom-widget__slot-related-title"><?php the_title(); ?></p>
						<a href="<?php the_permalink(); ?>" class="btn btn-play">Играть</a>
					</div>
				</div>
			<?php }
			wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif; ?>
