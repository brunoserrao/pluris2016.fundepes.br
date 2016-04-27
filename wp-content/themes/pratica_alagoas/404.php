<?php get_header();  ?>
	<div id="internal-page" class="container">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content row">
				<section class="error-404 not-found">
					<div class="coluna-conteudo grid_8">
						<h1 class="page-title">404 - página não encontrada.</h1>

						<p>
							A pagina que voce solicitou nao pode ser mostrada no momento. <a href="<?php echo home_url('/') ?>">Clique aqui para voltar para a página inicial</a>.
						</p>
					</div>
				</section>

				<?php get_template_part('sidebar','pratica') ?>
			</div>
		</article>
	</div>
<?php get_footer(); ?>