<?php get_header(); ?>

<?php get_template_part( 'templates/page-header' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">
			<div class="content__main">

				<?php
				$page_slots_text = get_field( 'page_slots_text_blocks', 'options' );
				$slot            = 1;
				$text            = 0;
				if ( ! is_paged() ) {
					echo '<div class="page_descr the_content">' . $page_slots_text[ $text ]['text'] . '</div>';
				}
				echo '<div class="row">';
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();

						if ( ! is_paged() ) {
							get_template_part( 'templates/loops/loop-slot' );

							if ( ( $slot % 6 ) == 0 ) {
								echo '</div>';
								$text ++;
								echo '<div class="page_descr the_content">' . $page_slots_text[ $text ]['text'] . '</div>';
								echo '<div class="row">';
							}

							$slot ++;
						} else {
							get_template_part( 'templates/loops/loop-slot' );
						}
					}
					$text ++;
				} else {
					get_template_part( 'templates/loops/loop-none' );
				}
				echo '</div>';
				if ( ! is_paged() ) {
					echo '<div class="page_descr the_content">' . $page_slots_text[ $text ]['text'] . '</div>';
				}
				?>

				<?php if ( function_exists( 'pagination' ) ) {
					pagination();
				} ?>

			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
