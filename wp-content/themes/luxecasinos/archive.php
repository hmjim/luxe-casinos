<?php get_header(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">

			<div class="content__main">

				<div class="the_content"><?php the_field( 'page_top_descr' ); ?></div>

				<?php get_template_part( 'templates/slots' ); ?>

			</div>

			<?php get_sidebar(); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
