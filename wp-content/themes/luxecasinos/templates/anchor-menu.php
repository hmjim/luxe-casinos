<?php
if ( is_home() ) :
	$anchor_menu = get_field( 'page_slots_anchor_menu', 'options' );
else :
	$anchor_menu = get_field( 'anchor_menu' );
endif;
?>

<?php if ( $anchor_menu ) : ?>
<div class="anchor__wrapper">
	<div class="anchor container">
		<div class="anchor__inner">

			<div class="anchor__content">
				<?php foreach ( $anchor_menu as $menu ) : ?>
					<div id="<?= $menu['id'] ?>" class="anchor__content-section the_content"><?= $menu['section'] ?></div>
				<?php endforeach; ?>
			</div>

			<div class="anchor__sidebar" data-sticky-container>
				<ul class="anchor__menu" data-sticky-for="991">
					<?php foreach ( $anchor_menu as $menu ) : ?>
						<li class="go_to">
							<a href="#<?= $menu['id'] ?>"><?= $menu['menu'] ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

		</div>
	</div>
</div>
<?php endif; ?>
