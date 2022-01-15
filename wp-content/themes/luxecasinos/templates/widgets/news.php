<?php
$count = get_field( 'sidebar_news_count', 'options' ) ? get_field( 'sidebar_news_count', 'options' ) : - 1;
$title = get_field( 'sidebar_news_title', 'options' );
$posts = get_posts( array(
	'posts_per_page' => $count,
	'post_type'      => 'news'
) );
if ( $posts ) : ?>
	<div class="custom-widget custom-widget__news">
		<p class="custom-widget__title"><?= $title; ?></p>

		<ul class="custom-widget__list custom-widget__news-list">
			<?php $i = 0; ?>
			<?php foreach ( $posts as $post ) : ?>
				<?php setup_postdata( $post ); ?>
				<li class="custom-widget__news-item">
					<span class="custom-widget__news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
				</li>
				<?php
				if ( $i == $count - 1 ) {
					break;
				} else {
					$i ++;
				}
				?>
			<?php endforeach; ?>
			<?php wp_reset_postdata() ?>
			<li class="custom-widget__news-item custom-widget__news-item-last"><a href="/news/">Смотреть все</a></li>
		</ul>
	</div>
<?php endif; ?>
