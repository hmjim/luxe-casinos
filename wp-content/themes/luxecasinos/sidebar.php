<aside class="content__sidebar">

	<?php
	if ( is_home() ) :

		get_template_part( 'templates/widgets/top-casino' );
		get_template_part( 'templates/widgets/top-slots' );

	elseif ( is_category() ) :

		get_template_part( 'templates/widgets/top-casino' );
		get_template_part( 'templates/widgets/search' );
		get_template_part( 'templates/widgets/news' );

	elseif ( is_page_template( 'template-casino.php' ) ) :

		get_template_part( 'templates/widgets/top-slots' );
		get_template_part( 'templates/widgets/links' );

	elseif ( is_tax( 'developer' ) ) :

		get_template_part( 'templates/widgets/top-slots' );
		get_template_part( 'templates/widgets/links' );

	elseif ( is_singular( 'casino' ) ) :

		get_template_part( 'templates/widgets/top-slots' );
		get_template_part( 'templates/widgets/links' );

	else :

		get_template_part( 'templates/widgets/related-slots' );
		get_template_part( 'templates/widgets/search' );
		get_template_part( 'templates/widgets/news' );

		//get_template_part( 'templates/widgets/top-slots' );
		//get_template_part( 'templates/widgets/top-casino' );
		//get_template_part( 'templates/widgets/developers' );
		//get_template_part( 'templates/widgets/news' );
		//get_template_part( 'templates/widgets/links' );
		//get_template_part( 'templates/widgets/related-slots' );
		//get_template_part( 'templates/widgets/slot-day' );
		//get_template_part( 'templates/widgets/search' );

	endif;
	?>

</aside>
