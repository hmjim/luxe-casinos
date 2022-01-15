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
					<div class="the_content"><?= $text; ?></div>
				<?php } ?>

				<?php get_template_part( 'templates/slots' ); ?>

				<?php if ( ! is_paged() ) { ?>
					<div class="the_content"><?php echo category_description(); ?></div>
				<?php } ?>

			</main>

			<?php get_sidebar(); ?>

		</div>
	</div>
</div>

<?php get_footer(); ?>
