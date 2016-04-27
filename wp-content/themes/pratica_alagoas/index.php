<?php get_header(); ?>
	<main>
		<?php
			$args = array(
				'posts_per_page' => 5,
				'featured' => 'yes',
				'orderby' => 'date',
				'order' => 'DESC'
			);

			$noticias_especiais = query_posts($args);
		?>
		<!-- BANNER -->
		<section class="triangle-block">
			<div id="banners-home" clas="owl-carousel">
				<?php foreach ($noticias_especiais as $post) { ?>
					<div class="banner" style="background-image: url(<?php echo thumb(array('id' => $post->ID, 'width'=> 960, 'height' => 600)); ?>)">
						<h1>
							<a href="<?php echo get_permalink($post->ID) ?>" title="<?php echo get_the_title($post->ID); ?>">
								<span>
									<?php echo get_the_title($post->ID); ?>
								</span>	
							</a>
						</h1>
					</div>
				<?php } ?>
			</div>
		</section>
		<!-- #BANNER -->

		<!--  ESPORTES -->
		<section class="well1">
			<div class="container">
				<a href="<?php echo get_category_link(ESPORTE_CATEGORY_ID) ?>" title="<?php echo get_cat_name(ESPORTE_CATEGORY_ID); ?>">
					<h2><?php echo get_cat_name(ESPORTE_CATEGORY_ID); ?></h2>
				</a>
				<div class="row">
					<div class="preffix_1 grid_10">
						<p class="text1 mb3">
							<a href="<?php echo get_category_link(ESPORTE_CATEGORY_ID) ?>" title="<?php echo get_cat_name(ESPORTE_CATEGORY_ID); ?>">
								<?php echo category_description(ESPORTE_CATEGORY_ID); ?>
							</a>
						</p>
					</div>
				</div>
				<div class="row">
					<?php
						$args = array(
							'child_of' => ESPORTE_CATEGORY_ID
						); 

						$esportes_categories = get_categories( $args );
						$grid = 12 / count($esportes_categories);
					?>
					<?php foreach ($esportes_categories as $key => $category) { ?>
						<div class="grid_<?php echo $grid; ?>">
							<div class="box">
								<a href="<?php echo get_category_link($category->cat_ID); ?>" title="<?php echo get_cat_name($category->cat_ID); ?>">
									<img class="box_icon" src="<?php echo z_taxonomy_image_url($category->cat_ID); ?>" alt="<?php echo get_the_title($post->ID); ?>">
								</a>
								
								<div class="box_cnt">
									<h3>
										<a href="<?php echo get_category_link($category->cat_ID); ?>" title="<?php echo get_cat_name($category->cat_ID); ?>">
											<?php echo get_cat_name($category->cat_ID); ?>
										</a>
									</h3>
									<p>
										<a href="<?php echo get_category_link($category->cat_ID); ?>" title="<?php echo get_cat_name($category->cat_ID); ?>">
											<?php echo category_description($category->cat_ID); ?>
										</a>
									</p>
								</div>
							</div>
						</div>
					<?php } ?>
					
				</div>
			</div>
		</section>
		
		<section>
			<div class="gallery">
				<?php
					$args = array(
						'cat' => ESPORTE_CATEGORY_ID,
						'posts_per_page' => 4
					);

					$esportes_posts = query_posts($args);
				?>
				<?php foreach ($esportes_posts as $post) { ?>
					<div class="gallery_item">
						<img src="<?php echo thumb(array('id' => $post->ID, 'width' => 512, 'height' => 280)); ?>" alt="<?php echo get_the_title($post->ID); ?>">
						<div class="cover">
							<div class="middle_inner">
								<div class="gallery_title">
									<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID);?>">
										<?php echo get_the_title($post->ID); ?>
									</a>
								</div>
								<div class="cover_hidden">
									<p><?php echo get_the_subtitle($post->ID); ?></p>
									<a class="btn" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">Leia mais</a>
								</div>
							</div>
							<div class="helper"></div>
						</div>
					</div>
				<?php } ?>
			</div>
		</section>
		<!-- #ESPORTES -->

		<!-- NOTICIAS -->
		<section class="well1 bb">
			<div class="container wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
				<h2 class="mb3">Últimas Notícias</h2>
					<?php
						$args = array(
							'cat' => NOTICIAS_CATEGORY_ID,
							'posts_per_page' => 3
						);

						$noticias_posts = query_posts($args);
					?>

					<?php foreach ($noticias_posts as $post) { ?>
						<div class="item-home">
							<img  class="item-home-destaque" src="<?php echo thumb(array('id' => $post->ID, 'width' => 370, 'height' => 230)); ?>" alt="<?php echo get_the_title($post->ID); ?>">
							<div class="cover">
								<div class="owl_inner">
									<h3>
										<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
											<?php echo get_the_title($post->ID); ?>
										</a>
									</h3>
									<time datetime="<?php echo get_the_date('Y-m-d'); ?>"><span class="fa fa-calendar"></span><?php echo get_the_date('d/m/Y', $post->ID); ?></time>
									<div class="cover_hidden">
										<p>
											<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
												<?php echo get_the_subtitle($post->ID); ?>
											</a>
										</p>
										<a class="btn2" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
											Leia mais
										</a>
									</div>
								</div>
								<div class="helper"></div>
							</div>
						</div>
					<?php } ?>
				</div>
				
				<div class="tc">
					<a class="btn" href="<?php echo get_category_link(ESPORTE_CATEGORY_ID); ?>" title="<?php echo category_description(ESPORTE_CATEGORY_ID); ?>">
						Veja todas as notícias
					</a>
				</div>
			</div>
		</section>
		<!-- #NOTICIAS -->


		<!-- NOTICIAS -->
		<section class="well1 bb">
			<div class="container wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
				<h2 class="mb3"><?php echo get_cat_name(GALERIAS_CATEGORY_ID); ?></h2>
					<?php
						$args = array(
							'cat' => GALERIAS_CATEGORY_ID,
							'posts_per_page' => 3
						);

						$noticias_posts = query_posts($args);
					?>

					<?php foreach ($noticias_posts as $post) { ?>
						<div class="item-home">
							<img  class="item-home-destaque" src="<?php echo thumb(array('id' => $post->ID, 'width' => 370, 'height' => 230)); ?>" alt="<?php echo get_the_title($post->ID); ?>">
							<div class="cover">
								<div class="owl_inner">
									<h3>
										<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
											<?php echo get_the_title($post->ID); ?>
										</a>
									</h3>
									<time datetime="<?php echo get_the_date('Y-m-d'); ?>"><span class="fa fa-calendar"></span><?php echo get_the_date('d/m/Y', $post->ID); ?></time>
									<div class="cover_hidden">
										<p>
											<a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
												<?php echo get_the_subtitle($post->ID); ?>
											</a>
										</p>
										<a class="btn2" href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>">
											Leia mais
										</a>
									</div>
								</div>
								<div class="helper"></div>
							</div>
						</div>
					<?php } ?>
				</div>
				
				<div class="tc">
					<a class="btn" href="<?php echo get_category_link(GALERIAS_CATEGORY_ID); ?>" title="<?php echo category_description(GALERIAS_CATEGORY_ID); ?>">
						Veja todas as galerias
					</a>
				</div>
			</div>
		</section>
		<!-- #NOTICIAS -->

		
		<!-- DICAS -->
		<section class="well2 bb">
			<div class="container wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
				
				<h2><?php echo get_cat_name(DICAS_CATEGORY_ID); ?></h2>
			
				<div class="row">
					<div class="preffix_1 grid_10">
						<p class="text1">
							<?php echo category_description(DICAS_CATEGORY_ID); ?>
						</p>
					</div>
				</div>

				<?php
					$args = array(
						'child_of' => DICAS_CATEGORY_ID
					); 
					
					$dicas_categories = array_chunk(get_categories( $args ), 3);
				?>

				<div class="index-list">
					<?php foreach ($dicas_categories as $categories) { ?>
					<ul class="row">
						<?php foreach ($categories as $category) { ?>
							<li class="grid_4" style="::before{content: '10'}">
								<span class="count-posts">
									<?php echo get_categeogy_post_count($category->cat_ID); ?>
								</span>
								<h3>
									<a href="<?php echo get_category_link($category->cat_ID); ?>" title="<?php echo get_cat_name($category->cat_ID); ?>">
										<?php echo get_cat_name($category->cat_ID); ?>
									</a>
								</h3>
								<p>
									<a href="<?php echo get_category_link($category->cat_ID); ?>" title="<?php echo get_cat_name($category->cat_ID); ?>">
										<?php echo category_description($category->cat_ID); ?>
									</a>
								</p>
							</li>
						<?php } ?>
					</ul>
					<?php } ?>
				</div>
			</div>
		</section>
		<!-- #DICAS -->

		<!-- USUARIOS -->
		<section class="well1">
			<div class="container">
				<h2 class="mb1">Blogueiros</h2>
				<?php 
					$usuarios = get_users( 'role=author' );
					$grid = 12 / count($usuarios);
				?>
				<div class="row">
					<?php foreach ($usuarios as $usuario) { ?>
						<div class="grid_<?php echo $grid; ?> wow fadeInLeft animated" style="visibility: visible; animation-name: fadeInLeft;">
							<div class="box">
								<a href="<?php echo get_author_posts_url($usuario->ID); ?>" title="<?php echo $usuario->display_name; ?>">
									<img class="circle" src="<?php echo thumb_image(array('image' => get_wp_user_avatar_src($usuario->ID), 'width' => 205, 'height' => 205)); ?>" alt="<?php echo get_the_title($post->ID); ?>">
								</a>
								<div class="box_cnt">
									<h4><?php echo $usuario->display_name; ?></h4>
									<h5><?php echo get_user_meta($usuario->ID, 'cargo', true); ?></h5>
									<ul class="social-list">
										<?php
											$facebook = get_user_meta($usuario->ID, 'facebook', true);
											$twitter = get_user_meta($usuario->ID, 'twitter', true);
											$youtube = get_user_meta($usuario->ID, 'youtube', true);
											$user_url = $usuario->user_url;
										?>
										<?php if (!empty($facebook)) {?>
											<li><a href="<?php echo $facebook ?>"><span class="fa fa-facebook"></span></a></li>
										<?php } ?>
										<?php if (!empty($twitter)) {?>
											<li><a href="<?php echo $twitter ?>"><span class="fa fa-twitter"></span></a></li>
										<?php } ?>
										<?php if (!empty($youtube)) {?>
											<li><a href="<?php echo $youtube ?>"><span class="fa fa-youtube"></span></a></li>
										<?php } ?>
										<?php if (!empty($user_url)) {?>
											<li><a href="<?php echo $user_url ?>"><span class="fa fa-globe"></span></a></li>
										<?php } ?>
									</ul>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</section>
		<!-- #USUARIOS -->
	</main>
<?php get_footer(); ?>