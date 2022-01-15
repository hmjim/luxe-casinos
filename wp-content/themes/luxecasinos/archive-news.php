<?php get_header(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">
			<main class="content__main">

				<div class="row">

					<?php if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'templates/loops/loop-news' );
						endwhile;
					else :
						get_template_part( 'templates/loops/loop-none' );
					endif; ?>

				</div>

				<?php if ( function_exists( 'pagination' ) ) {
					pagination();
				} ?>
			</main>

			<?php get_sidebar( 'slots' ); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
