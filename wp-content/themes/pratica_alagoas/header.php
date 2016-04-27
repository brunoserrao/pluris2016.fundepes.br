<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta property="fb:app_id" content="241836669338742"/>
	<meta property="og:locale" content="pt_BR"/>
	<meta property="og:site_name" content="<?php echo bloginfo('site-name') ?>"/>
	
	<?php if (is_single()) { ?>
		<?php 
			the_post();
			$tags =  get_tags();
			$facebook_author = get_user_meta(get_the_author_ID(), 'facebook', true);
			if (empty($facebook_author)) {
				$facebook_author = get_the_author();
			}

			$twitter = get_user_meta(get_the_author_ID(), 'twitter', true);
			if (empty($twitter)) {
				$twitter = get_option('twitter_url');	
			}

			$post_image_url = post_image(get_the_ID());
		?>
		
		<meta property="og:type" content="article"/>
		<meta property="og:title" content="<?php echo get_the_title() ?>"/>
		<meta property="og:description" content="<?php echo get_the_subtitle() ?>"/>
		<meta property="og:url" content="<?php echo get_permalink(); ?>"/>

		<meta property="og:image" content="<?php echo $post_image_url['image']; ?>"/>
		<meta property="og:image:width" content="<?php echo $post_image_url['width']; ?>"/>
		<meta property="og:image:height" content="<?php echo $post_image_url['height']; ?>"/>
		<meta property="article:author" content="<?php echo $facebook_author; ?>"/>

		<?php if (!empty($tags)) { ?>
			<meta name="keywords" content="<?php echo get_the_tags_by_comas($tags); ?>" />
		
			<?php foreach ($tags as $tag) {?>
				<meta property="article:tag" content="<?php echo $tag->name; ?>"/>
			<?php } ?>
		
		<?php } ?>
		
		<meta property="article:published_time" content="<?php echo get_the_date('Y-m-dTH:i:s') ?>"/>
		
		<meta name="twitter:card" content="summary_large_image"/>
		<meta name="twitter:title" content="<?php echo get_the_title(); ?>"/>
		<meta name="twitter:description" content="<?php echo get_the_subtitle(); ?>"/>
		<meta name="twitter:site" content="<?php echo $twitter ?>"/>
		<meta name="twitter:image" content="<?php echo $post_image_url['image']; ?>"/>
	<?php } else { ?>
		<?php if (is_home()) {?>
			<meta property="og:title" content="<?php echo bloginfo('site-name'); ?>"/>
		<?php } else { ?>
			<meta property="og:title" content="<?php echo title_remove_space(wp_title('')); ?> | <?php echo bloginfo('site-name'); ?>"/>
		<?php } ?>
		
		<meta property="og:type" content="website"/>
		<meta property="og:description" content="<?php echo bloginfo('description'); ?>"/>
		<meta property="og:url" content="<?php echo get_pagenum_link(); ?>"/>
		<meta property="og:site_name" content="<?php echo bloginfo('site-name') ?>"/>
		<meta property="og:image" content="<?php echo get_template_directory_uri().'/images/pratica-alagoas.png'; ?>"/>
	<?php } ?>
	
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<meta name="format-detection" content="telephone=no">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/grid.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/camera.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/google-map.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/owl.carousel.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/css/fullcalendar.min.css" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/animate.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/fonts.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.css">

	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-migrate-1.2.1.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/device.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/owl.carousel.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/moment.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/fullcalendar.min.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/lang-all.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>

	<script>
		var jsonUrl = "<?php echo get_category_link(CALENDAR_CATEGORY_ID); ?>";
	</script>

	<?php wp_head(); ?>
</head>

<body>
	<div class="page">
		<header>
			<div id="stuck_container" class="stuck_container">
				<div class="container">
					<div class="brand">
						<h1 class="brand_name">
							<a href="<?php echo site_url()?>" title="<?php echo bloginfo('site_name'); ?>">
								<img class="logo" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="">
							</a>
						</h1>
					</div>

					 
					<div class="header_right">
						<div class="phone">
							<ul class="social-list">
								<li><a href="<?php echo get_option('facebook_url'); ?>" target="_blank"><span class="fa fa-facebook"></span></a></li>
								<li><a href="<?php echo get_option('twitter_url'); ?>" target="_blank"><span class="fa fa-twitter"></span></a></li>
								<li><a href="<?php echo get_option('instagram_url'); ?>" target="_blank"><span class="fa fa-instagram"></span></a></li>
								<li><a href="<?php echo get_option('pinterest_url'); ?>" target="_blank"><span class="fa fa-pinterest"></span></a></li>
							</ul>
						</div>
					</div>
					
					
					<nav class="nav">
						<?php wp_nav_menu(array('menu' => 'Principal','menu_class' => 'sf-menu','data-type' => 'navbar')) ?>
					</nav>
				</div>
			</div>
		</header>