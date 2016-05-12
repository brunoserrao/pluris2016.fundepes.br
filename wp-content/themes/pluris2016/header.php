<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<meta property="fb:app_id" content="241836669338742"/>
	<meta property="og:locale" content="pt_BR"/>
	<meta property="og:site_name" content="<?php echo bloginfo('site-name') ?>"/>
	<meta property="og:type" content="website"/>
	<meta property="og:description" content="<?php echo bloginfo('description'); ?>"/>
	<meta property="og:url" content="<?php echo get_pagenum_link(); ?>"/>
	<meta property="og:site_name" content="<?php echo bloginfo('site-name') ?>"/>
	<meta property="og:image" content="<?php echo get_template_directory_uri().'/images/pluris-2016-2.png'; ?>"/>

	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" type="image/png">

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-77647694-1', 'auto');
		ga('send', 'pageview');
	</script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'pluris2016' ); ?></a>

	<div id="sidebar" class="sidebar">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding">
				<a href="<?php echo home_url('/') ?>" title="<?php bloginfo('name'); ?>">
					<img src="<?php echo get_template_directory_uri() ?>/images/pluris-2016-05-a-07-de-outubro-de-2016.png" alt="<?php bloginfo( 'name' ); ?>">
				</a>
				<button class="secondary-toggle"><?php _e( 'Menu and widgets', 'pluris2016' ); ?></button>
			</div>
		</header>

		<?php get_sidebar(); ?>
	</div>

	<div id="content" class="site-content">