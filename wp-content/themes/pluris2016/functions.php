<?php
/**
 * Twenty Fifteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 660;
}

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'pluris2016_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Fifteen 1.0
 */
function pluris2016_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on pluris2016, use a find and replace
	 * to change 'pluris2016' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'pluris2016', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'pluris2016' ),
		'social'  => __( 'Social Links Menu', 'pluris2016' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'default'
	) );

	$color_scheme  = pluris2016_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'pluris2016_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', pluris2016_fonts_url() ) );

	/*
	 * WooCommerce Support theme
	 */
	add_theme_support( 'woocommerce' );
}

/*
 * Thumb Cropped
 */
add_image_size( 'thumb_galeria', 200, 200, true );


endif; // pluris2016_setup
add_action( 'after_setup_theme', 'pluris2016_setup' );

/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function pluris2016_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'pluris2016' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'pluris2016' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'pluris2016_widgets_init' );

if ( ! function_exists( 'pluris2016_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Fifteen.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function pluris2016_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'pluris2016' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'pluris2016' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'pluris2016' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'pluris2016' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Fifteen 1.1
 */
function pluris2016_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'pluris2016_javascript_detection', 0 );

/**
 * Enqueue scripts and styles.
 *
 * @since Twenty Fifteen 1.0
 */
function pluris2016_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'pluris2016-fonts', pluris2016_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'pluris2016-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'pluris2016-ie', get_template_directory_uri() . '/css/ie.css', array( 'pluris2016-style' ), '20141010' );
	wp_style_add_data( 'pluris2016-ie', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'pluris2016-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'pluris2016-style' ), '20141010' );
	wp_style_add_data( 'pluris2016-ie7', 'conditional', 'lt IE 8' );

	wp_enqueue_script( 'pluris2016-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20141010', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'pluris2016-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20141010' );
	}

	wp_enqueue_script( 'pluris2016-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20150330', true );
	wp_localize_script( 'pluris2016-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'pluris2016' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'pluris2016' ) . '</span>',
	) );
}
add_action( 'wp_enqueue_scripts', 'pluris2016_scripts' );

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function pluris2016_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); border-top: 0; }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'pluris2016-style', $css );
}
add_action( 'wp_enqueue_scripts', 'pluris2016_post_nav_background' );

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function pluris2016_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'pluris2016_nav_description', 10, 4 );

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function pluris2016_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'pluris2016_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';



add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text', 1 ); 

function woo_custom_order_button_text() {
    return __( 'Choose payment method', 'pluris2016' ); 
}


/**
* Remove tabs from productsd
*/
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['reviews'] );
	unset( $tabs['additional_information'] );

	return $tabs;

}

/**
* Renomear Label do campo Company Name
*/
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
	$fields['billing']['billing_company']['label'] =  __( 'Badge Name', 'pluris2016' );
	$fields['billing']['billing_instituition']['label'] =  __( 'Instituition Name', 'pluris2016' );
	return $fields;
}

/**
* Renomear Label do campo Company Name
*/
add_filter('woocommerce_order_button_html','custom_woocommerce_order_button_html');
function custom_woocommerce_order_button_html(){
	return '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' .__('Finish payment', 'pluris2016') . '" data-value="' .__('Finish payment', 'pluris2016') . '" />';
}

/**
* Adicionar resumo em páginas
*/
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}


/**
* Adicionar apenas um produto no carrinho
*/
add_filter ( 'woocommerce_add_to_cart_validation' , 'pluris_only_one_in_cart' );
function pluris_only_one_in_cart( $cart_item_data ) {
	global $woocommerce;

	$woocommerce->cart->empty_cart();
	return $cart_item_data;
}

/**
 * Define custom product data fields
 */
 add_action('wc_cpdf_init', 'prefix_custom_product_data', 10, 0);
if(!function_exists('prefix_custom_product_data')) :
 
   function prefix_custom_product_data(){
 
		$current_prod = null;
			if(isset($_GET['post']) && !empty($_GET['post']) ){
			$current_prod = $_GET['post'];
		}
 
     	$custom_product_data_fields = array();
 
		$get_products = get_posts(array(
			'post_type' 	 => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'post__not_in'   => array( $current_prod )
		));

		$prods = array();

		if ( ! empty( $get_products ) ) :
			foreach ($get_products as $key => $value) {
				$prods[$value] = get_the_title($value);
			}
		endif;

		$custom_product_data_fields['qty_articles'] = array(

			 array(
				 'tab_name' => __('Articles', 'pluris2016')
			 ),
			 array(
				 'id'	        => 'qty_articles',
				 'type'		  	=> 'number',
				 'label'        => __('Qty of articles', 'pluris2016')
			 )
		);

		return $custom_product_data_fields;
   }
 
endif;


/**
 * Salvar Relacionamento ORDER / Articles
 */
add_action('woocommerce_checkout_order_processed','save_articles_to_order');
function save_articles_to_order($order_id){
	if (isset($_POST['articles_id']) and !empty($_POST['articles_id'])) {
		wc_add_order_item_meta($order_id, '_articles_id', json_encode($_POST['articles_id']));
	}
}

/**
 * Adicionar a coluna Artigos nos pedidos
 */
add_filter('manage_edit-shop_order_columns', 'woocommerce_order_articles_column', 15);
function woocommerce_order_articles_column($columns) {
	$new_columns = (is_array($columns)) ? $columns : array();

	//add column
	$new_columns['articles'] = __('Artigos', 'pluris2016');

	return $new_columns;
}

add_action('manage_shop_order_posts_custom_column', 'woocommerce_order_articles_column_values', 10, 2);
function woocommerce_order_articles_column_values($column) {

	global $post, $woocommerce, $the_order;

	switch ($column) {
		case 'articles' :
			$articles_id = json_decode(wc_get_order_item_meta($the_order->id, '_articles_id'));
			if (!empty($articles_id)) {
				echo "<ul>";
				foreach ($articles_id as $article_id) {
					if ((int)$article_id) {
						$title = get_the_title($article_id);
						echo "<li><a href=".get_the_permalink($article_id)." target='_blank' >".substr($title, 0,30)."...</a></li>";
					}					
				}
				echo "</ul>";
			}
		break;
	}
}


/**
 * Add custom media metadata fields
 *
 * Be sure to sanitize your data before saving it
 * http://codex.wordpress.org/Data_Validation
 *
 * @param $form_fields An array of fields included in the attachment form
 * @param $post The attachment record in the database
 * @return $form_fields The final array of form fields to use
 */
function add_image_attachment_fields_to_edit( $form_fields, $post ) {
	$image_from_gallery = (bool) get_post_meta($post->ID, 'image_from_gallery', true);

	$form_fields['image_from_gallery'] = array(
		'label' => __('Publicar na galeria'),
		'input' => "html",
		'html' => '<input type="checkbox" id="attachments-'.$post->ID.'-image_from_gallery" name="attachments['.$post->ID.'][image_from_gallery]" value="'.$image_from_gallery.'"'.($image_from_gallery ? ' checked="checked"' : '').'/>',
		'value' => esc_attr( get_post_meta($post->ID, 'image_from_gallery', true) )
	);

	return $form_fields;
}
add_filter('attachment_fields_to_edit', 'add_image_attachment_fields_to_edit', null, 2);


/**
 * Save custom media metadata fields
 *
 * Be sure to validate your data before saving it
 * http://codex.wordpress.org/Data_Validation
 *
 * @param $post The $post data for the attachment
 * @param $attachment The $attachment part of the form $_POST ($_POST[attachments][postID])
 * @return $post
 */
function add_image_attachment_fields_to_save( $post, $attachment ) {
	$image_from_gallery_meta = (bool) get_post_meta($post['ID'], 'image_from_gallery', true);
	$image_from_gallery_value = isset( $attachment['image_from_gallery'] ) ? 1 : 0;

	if ($image_from_gallery_meta) {
		if ($image_from_gallery_value) {
			update_post_meta( $post['ID'], 'image_from_gallery', true );
		} else {
			delete_post_meta( $post['ID'], 'image_from_gallery' );	
		}	
	} else {
		if ($image_from_gallery_value) {
			update_post_meta( $post['ID'], 'image_from_gallery', true );
		}
	}

	return $post;
}
add_filter('attachment_fields_to_save', 'add_image_attachment_fields_to_save', null , 2);


/*
* Remover a senha forte do Woocommerce
*
*/
function wc_ninja_remove_password_strength() {
	if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}
}
add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );