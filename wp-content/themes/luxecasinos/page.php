<?php get_header(); ?>
<?php the_post(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">

			<main class="content__main">

				<div class="the_content"><?php the_content(); ?></div>

			</main>

			<?php get_sidebar(); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
