<?php get_header(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">
			<main class="content__main">

				<?php
				$term = get_queried_object();
				$text = get_field( 'page_top_descr', $term );
				?>
				<?php if ( $text && ! is_paged() ) { ?>
					<div class="the_content"><?php the_field( 'page_top_descr', $term ); ?></div>
				<?php } ?>

				<div class="section">
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
				</div>

				<?php if ( ! is_paged() ) { ?>
					<?php $description = term_description(); ?>
					<?php if ( $description != null ) : ?>
						<div class="the_content"><?= $description; ?></div>
					<?php endif; ?>
				<?php } ?>
			</main>

			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
