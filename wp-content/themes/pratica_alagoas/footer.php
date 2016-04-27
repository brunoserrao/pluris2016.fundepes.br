		<footer>
			<section class="footer-top">
				<div class="container">
					<div class="row">
						<div class="grid_4">
							<?php dynamic_sidebar('rodape-1'); ?>
						</div>
						<div class="grid_4">
							<?php dynamic_sidebar('rodape-2'); ?>
						</div>
						<div class="grid_4">
							<?php dynamic_sidebar('rodape-3'); ?>
						</div>
					</div>
				</div>
			</section>
			<section class="footer-bottom">
				<div class="container">
					<div class="row">
						<div class="grid_6">
							<p class="copy">
								Pratica Alagoas
								<span id="copyright-year"><?php echo date('Y'); ?></span>.
								<a href="<?php echo get_permalink(POLITICA_ID) ?>">Política de Pricacidade</a>
							</p>
						</div>
						<div class="grid_6">
							<ul class="social-list">
								<li><a href="<?php echo get_option('facebook_url'); ?>" target="_blank"><span class="fa fa-facebook"></span></a></li>
								<li><a href="<?php echo get_option('twitter_url'); ?>" target="_blank"><span class="fa fa-twitter"></span></a></li>
								<li><a href="<?php echo get_option('instagram_url'); ?>" target="_blank"><span class="fa fa-instagram"></span></a></li>
								<li><a href="<?php echo get_option('pinterest_url'); ?>" target="_blank"><span class="fa fa-pinterest"></span></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
		</footer>
	</div>
	
	<?php wp_footer(); ?>
</body>
</html>