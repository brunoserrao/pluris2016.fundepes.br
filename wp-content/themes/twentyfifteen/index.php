<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) { ?>
			<?php while ( have_posts() ) { the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php } ?>
		<?php } else { ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php } ?>

		</main>
	</div>

<?php get_footer(); ?>
