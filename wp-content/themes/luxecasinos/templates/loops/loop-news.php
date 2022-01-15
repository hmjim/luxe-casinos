<?php
$thumb     = get_post( get_post_thumbnail_id() );
$image_alt = get_post_meta( $thumb->ID, '_wp_attachment_image_alt', true );
?>
<article class="news">
	<div class="news__inner">
		<a href="<?php the_permalink(); ?>" class="news__img">
			<img src="<?= kama_thumb_src( "w=510 &h=418" ) ?>" title="<?php echo $thumb->post_title; ?>" alt="<?php echo $image_alt; ?>">
		</a>
		<div class="news__text">
			<a class="news__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<div class="news__descr"><?php the_excerpt(); ?></div>
		</div>
	</div>
</article>
