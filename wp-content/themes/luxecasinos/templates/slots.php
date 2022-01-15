<div class="row">

	<?php if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			get_template_part( 'templates/loops/loop-slot' );
		endwhile;
	else :
		get_template_part( 'templates/loops/loop-none' );
	endif; ?>

</div>

<?php if ( function_exists( 'pagination' ) ) {
	pagination();
} ?>
