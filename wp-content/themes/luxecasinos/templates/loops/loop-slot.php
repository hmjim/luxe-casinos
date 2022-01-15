<?php
$thumb     = get_post( get_post_thumbnail_id() );
$image_alt = get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true );
$link      = get_field( 'home_link' ) ? get_field( 'home_link' ) : get_field( 'game_link_default', 'options' );
?>
<article class="slot">
	<div class="slot__inner">
		<div class="slot__title"><?php the_title(); ?></div>

		<a href="<?php the_permalink(); ?>" class="slot__thumb">
			<img src="<?= kama_thumb_src( 'w=280 &h=152', get_post_thumbnail_id() ); ?>" title="Игровой автомат <?php echo $thumb->post_title; ?>" alt="Игровой автомат <?php echo $image_alt; ?>">
		</a>

		<div class="slot__btns">
			<a href="<?php the_permalink(); ?>" class="btn btn-demo" title="<?php the_title(); ?> бесплатно">Демо</a>
			<a href="<?= $link; ?>" class="btn btn-play" title="<?php the_title(); ?> на реальные деньги" >На деньги</a>
		</div>
	</div>
</article>


