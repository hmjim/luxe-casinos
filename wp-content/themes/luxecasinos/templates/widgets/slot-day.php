<?php
$title      = get_field( 'sidebar_slot_day_title', 'options' );
$wins_nums  = explode( ',', get_field( 'sidebar_slot_day_wins', 'options' ) );
$total_nums = explode( ',', get_field( 'sidebar_slot_day_total', 'options' ) );
$posts      = get_posts( array(
	'posts_per_page' => 1,
	'post_type'      => 'post',
	'orderby'        => 'rand',
	'exclude'        => array( get_the_ID() )
) );
if ( $posts ) : ?>
	<div class="custom-widget custom-widget__slot-day">
		<div class="custom-widget__title"><?= $title; ?></div>

		<?php foreach ( $posts as $post ) {
			setup_postdata( $post );
			$thumb     = get_post( get_post_thumbnail_id() );
			$image_alt = get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true );
			$wins      = rand( (int) $wins_nums[0], (int) $wins_nums[1] );
			$total     = rand( (int) $total_nums[0], (int) $total_nums[1] );
			?>
			<a href="<?php the_permalink(); ?>" class="custom-widget__slot-day-item">
				<img src="<?= kama_thumb_src( 'w=270 &h=165' ); ?>" class="custom-widget__slot-day-img" title="<?= $thumb->post_title; ?>" alt="<?= $image_alt; ?>">
				<span class="custom-widget__slot-day-text">
						<span class="custom-widget__slot-day-title"><?php the_title(); ?></span>
						<span class="custom-widget__slot-day-wins"><?= num_decline( $wins, 'победитель, победителя, победителей' ); ?></span>
						<span class="custom-widget__slot-day-total"><span><?= $total; ?>$</span> выигрыш</span>
					</span>
			</a>
		<?php }
		wp_reset_postdata(); ?>
	</div>
<?php endif; ?>
