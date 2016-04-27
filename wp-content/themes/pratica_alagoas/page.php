<?php get_header(); the_post(); ?>
	<div id="internal-page" class="container">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content row">
				<section>
					<div class="coluna-conteudo grid_8">
						<h1><?php echo get_the_title() ?></h1>
						<?php the_content(); ?>
					</div>
				</section>

				<?php get_template_part('sidebar','pratica') ?>
			</div>
		</article>
	</div>
<?php get_footer(); ?>