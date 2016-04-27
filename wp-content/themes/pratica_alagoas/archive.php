<?php get_header(); ?>
	<div id="internal-page" class="container">

	<div class="entry-content row">
		<section>
			<div class="coluna-conteudo grid_8">
				<?php if ( have_posts() ) { ?>
					
					<?php if(is_author()){ $usuario = get_user_by('ID',get_the_author_ID()) ?>
						<div class="page-header">
							<div class="the_author_info">
								
								<img class="circle" src="<?php echo thumb_image(array('image' => get_wp_user_avatar_src(get_the_author_ID()), 'width' => 250, 'height' => 250)); ?>" alt="<?php echo get_the_title($post->ID); ?>">
								
								<h1><?php echo get_the_author() ?></h1>
							</div>
							<p><?php echo get_the_author_description() ?></p>



							<div class="author-social-network">
								<?php
									$facebook = get_user_meta(get_the_author_ID(), 'facebook', true);
									$twitter = get_user_meta(get_the_author_ID(), 'twitter', true);
									$youtube = get_user_meta(get_the_author_ID(), 'youtube', true);
									$user_url = $usuario->user_url;
								?>
								<?php if (!empty($facebook)) {?>
									<a href="<?php echo $facebook ?>"><span class="fa fa-facebook"></span>
								<?php } ?>
								<?php if (!empty($twitter)) {?>
									<a href="<?php echo $twitter ?>"><span class="fa fa-twitter"></span>
								<?php } ?>
								<?php if (!empty($youtube)) {?>
									<a href="<?php echo $youtube ?>"><span class="fa fa-youtube"></span>
								<?php } ?>
								<?php if (!empty($user_url)) {?>
									<a href="<?php echo $user_url ?>"><span class="fa fa-globe"></span>
								<?php } ?>
							</div>
						</div>
					<?php } else { ?>

						<h1><?php echo single_cat_title( '', false ); ?></h1>
						<p><?php the_archive_description() ?></p>

					<?php } ?>

					<h2 class="align-left">Posts</h2>
				
					<ul class="archive-list">
						<?php while ( have_posts() ) {  the_post() ?>
							<li class="archive-item">
								<div class="archive-image">
									<a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title();?>">
										<img src="<?php echo thumb(array('id' => get_the_ID(), 'width' => 230, 'height' => 200)); ?>" alt="<?php echo get_the_title() ?>">	
									</a>
								</div>
								
								<div class="archive-title">
									<h3>
										<a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title();?>">
											<?php echo get_the_title(); ?>
										</a>
									</h3>
								
									<a href="<?php echo get_permalink(); ?>" title="<?php echo get_the_title();?>">
										<p>
											<?php echo get_the_excerpt() ?>
										</p>
									</a>
									
								</div>
							</li>
						<?php } ?>
					</ul>	
					
					<?php 
						the_posts_pagination( array(
							'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
							'next_text'          => __( 'Next page', 'twentyfifteen' ),
							'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
						) );
					?>
				
				<?php } else { ?>

					<?php get_template_part( 'content', 'none' ); ?>

				<?php } ?>
			</div>

			<?php get_template_part('sidebar','pratica') ?>

		</section>
	</div>

<?php get_footer(); ?>