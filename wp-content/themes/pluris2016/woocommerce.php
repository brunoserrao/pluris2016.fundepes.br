<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-<?php the_ID(); ?>" class="hentry">
			<div class="entry-content">
				<?php woocommerce_content(); ?>
			</div>
			</article>
		</main>
	</div>

<?php get_footer(); ?>
