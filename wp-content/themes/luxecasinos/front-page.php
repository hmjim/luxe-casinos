<?php
/**
 * Template Name: Главная страница
 */
?>

<?php get_header(); ?>

<?php get_template_part( 'templates/header-banner' ); ?>

<div class="content__wrapper">
	<div class="content container">
		<div class="content__inner">
			<div class="content__main no-sidebar">
				<?php $page_slots_title = get_field( 'page_slots_title', 'options' ) ? get_field( 'page_slots_title', 'options' ) : 'Рейтинг казино'; ?>
				<h1><?= $page_slots_title; ?></h1>

				<?php if ( ! is_paged() ) { ?>
					<?php $page_slots_text = get_field( 'page_slots_text', 'options' ); ?>
					<?php if ( $page_slots_text ) { ?>
						<div class="page_descr the_content"><?= $page_slots_text; ?></div>
					<?php } ?>

					<?php get_template_part( 'templates/casino-ratings' ); ?>

					<?php $page_slots_content = get_field( 'page_slots_content', 'options' ); ?>
					<?php if ( $page_slots_content ) { ?>
						<div class="main_content the_content"><?= $page_slots_content; ?></div>
					<?php } ?>
				<?php } ?>

				<?php $page_slots_title_2 = get_field( 'page_slots_title_2', 'options' ) ? get_field( 'page_slots_title_2', 'options' ) : 'Игровые автоматы'; ?>
				<h2 class="page_title"><?= $page_slots_title_2; ?></h2>

				<?php $slots = get_field( 'page_slots', 'options' ) ?>
				<div class="row">
					<?php
					if ( $slots ) :
						foreach ( $slots as $slot ) :
							$post = get_post( $slot['slot'] );
							setup_postdata( $post );
							get_template_part( 'templates/loops/loop-slot' );
						endforeach;
						wp_reset_postdata();
					else :
						get_template_part( 'templates/loops/loop-none' );
					endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
