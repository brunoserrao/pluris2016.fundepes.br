<?php
	function pratica_alagoas_setup() {
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
			'primary' => __( 'Primary Menu' ),
			'social'  => __( 'Social Links Menu'),
		) );

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'default'
		) );
	}

	add_action( 'after_setup_theme', 'pratica_alagoas_setup' );

	function pratica_alagoas_categorias(){
		/*
		 * Categorias ID
		 *
		 */
		define('CALENDAR_CATEGORY_ID',5);
		define('ESPORTE_CATEGORY_ID',6);
		define('ESPORTE_TERRA_CATEGORY_ID',7);
		define('ESPORTE_CEU_CATEGORY_ID',8);
		define('ESPORTE_MAR_CATEGORY_ID',9);
		define('DICAS_CATEGORY_ID',10);
		define('NOTICIAS_CATEGORY_ID',11);
		define('GALERIAS_CATEGORY_ID',21);
		define('POLITICA_ID',109);
	}
	add_action( 'init', 'pratica_alagoas_categorias' );

	remove_filter('term_description','wpautop');

	function thumb($options = array()){
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($options['id']), 'thumbnail_size' );

		if (empty($thumb)) {
			$thumb[0] = get_template_directory_uri().'/images/sem-imagem.jpg';
		}

		$data = explode('/', str_replace(site_url(),'',$thumb[0]));

		return site_url(). '/thumb/cache/' . $data[3] . '/' . $data[4] . '/' . $options['width'] . 'x' . $options['height'] . '/' . $data[5];
	}

	function thumb_image($options = array()){
		$data = explode('/', str_replace(site_url(),'',$options['image']));
		return site_url(). '/thumb/cache/' . $data[3] . '/' . $data[4] . '/' . $options['width'] . 'x' . $options['height'] . '/' . $data[5];
	}


	function post_image($post_id){
		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail_size' );
		$image = array();

		if (empty($thumb)) {
			$image['image'] = get_template_directory_uri().'/images/sem-imagem.jpg';
		} else {
			$image['image'] = $thumb[0];
		}

		$image_size = getimagesize($image['image']);
		$image['width'] = $image_size[0];
		$image['height'] = $image_size[1];

		return $image;
	}

	/*
	* Redes Sociais do Usuário
	*
	*/
	add_filter('user_contactmethods', 'modify_contact_methods');
	function modify_contact_methods($profile_fields) {
		// Add new fields
		$profile_fields['facebook'] = 'Facebook';
		$profile_fields['twitter'] = 'Twitter';
		$profile_fields['youtube'] = 'Youtube';
		$profile_fields['cargo'] = 'Cargo';

		return $profile_fields;
	}

	/*
	* Contar posts por categoria
	*
	*/
	function get_categeogy_post_count($category_id) {
		global $wpdb;

		$prepare = $wpdb->prepare("SELECT count FROM $wpdb->term_taxonomy WHERE term_id = %d", $category_id);
		$result = $wpdb->get_results($prepare);
		$count = !empty($result) ? $result[0]->count : 0;

		return $count;
	}


	/*
	* Admin thumbnail
	*
	*/
	if (function_exists( 'add_theme_support' )){
		add_filter('manage_posts_columns', 'posts_columns', 5);
		add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);
		add_filter('manage_pages_columns', 'posts_columns', 5);
		add_action('manage_pages_custom_column', 'posts_custom_columns', 5, 2);
	}
	function posts_columns($defaults){
		$defaults['wps_post_thumbs'] = __('Image');
		return $defaults;
	}
	
	function posts_custom_columns($column_name, $id){
		if($column_name === 'wps_post_thumbs'){
		    echo the_post_thumbnail( array(125,80) );
		}
	}

	
	/**
	* Register widget area.
	*
	*/
	function pratica_alagoas_widgets_init() {
		register_sidebar( array(
			'name'          => 'Lateral',
			'id'            => 'sidebar-1',
			'description'   => 'Adicione funções na barra lateral',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );

		for ($i=1; $i <= 3; $i++) { 
			register_sidebar( array(
				'name'          => 'Rodapé '.$i,
				'id'            => 'rodape-'.$i,
				'description'   => 'Adicione funções no Rodapé, Coluna '.$i,
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h6>',
				'after_title'   => '</h6>',
			) );
		}
	}
	add_action( 'widgets_init', 'pratica_alagoas_widgets_init' );


	/**
	* Remove CSS padrão da galeria
	*
	*/
	add_filter( 'use_default_gallery_style', '__return_false' );


	/**
	* Campos novos para o General Setting
	*
	*/
	$new_general_setting = new new_general_setting();

	class new_general_setting {
		function new_general_setting( ) {
			add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
		}
		
		function register_fields() {
			register_setting( 'general', 'facebook_url', 'esc_attr' );
			add_settings_field('facebook_url', '<label for="facebook_url">'.__('Facebook' , 'Facebook' ).'</label>' , array(&$this, 'fields_facebook') , 'general' );

			register_setting( 'general', 'twitter_url', 'esc_attr' );
			add_settings_field('twitter_url', '<label for="twitter_url">'.__('Twitter' , 'Twitter' ).'</label>' , array(&$this, 'fields_twitter') , 'general' );

			register_setting( 'general', 'instagram_url', 'esc_attr' );
			add_settings_field('instagram_url', '<label for="instagram_url">'.__('Instagram' , 'Instagram' ).'</label>' , array(&$this, 'fields_instagram') , 'general' );

			register_setting( 'general', 'pinterest_url', 'esc_attr' );
			add_settings_field('pinterest_url', '<label for="pinterest_url">'.__('Pinterest' , 'Pinterest' ).'</label>' , array(&$this, 'fields_pinterest') , 'general' );

			register_setting( 'general', 'youtube_url', 'esc_attr' );
			add_settings_field('youtube_url', '<label for="Pinterest">'.__('Pinterest' , 'Pinterest' ).'</label>' , array(&$this, 'fields_pinterest') , 'general' );
		}
		
		function fields_facebook() {
			$value = get_option( 'facebook_url', '' );
			echo '<input type="text" id="facebook_url" class="regular-text" name="facebook_url" value="' . $value . '" />';
		}

		function fields_twitter(){
			$value = get_option( 'twitter_url', '' );
			echo '<input type="text" id="twitter_url" class="regular-text" name="twitter_url" value="' . $value . '" />';
		}

		function fields_instagram(){
			$value = get_option( 'instagram_url', '' );
			echo '<input type="text" id="instagram_url" class="regular-text" name="instagram_url" value="' . $value . '" />';
		}

		function fields_pinterest(){
			$value = get_option( 'pinterest_url', '' );
			echo '<input type="text" id="pinterest_url" class="regular-text" name="pinterest_url" value="' . $value . '" />';
		}
	}

	/**
	* Permitir blogueiros upload
	*
	*/
	function my_files_only( $wp_query ) {
		if ($wp_query->query_vars['post_type']=="attachment"){
			if ( !current_user_can( 'level_5' ) ) {
				global $current_user;
				$wp_query->set( 'author', $current_user->id );
			}
		}
	}

	add_filter('parse_query', 'my_files_only' );
	
	if ( current_user_can('contributor') && !current_user_can('upload_files') )
		add_action('admin_init', 'allow_contributor_uploads');

	function allow_contributor_uploads() {
		$contributor = get_role('contributor');
		$contributor->add_cap('upload_files');
	}

	/**
	* Personalizar tags
	*
	*/
	function wpb_tags() { 
		$tags =  get_tags();
		$string = "";
		
		foreach ($tags as $tag) { 
			$string .= '<span class="tagbox"><a class="taglink" href="'. get_tag_link($tag->term_id) .'">'. $tag->name . '</a><span class="tagcount">'. $tag->count .'</span></span>' . "\n";
		} 
		
		return $string;
	} 
	

	function get_the_tags_by_comas($tags) { 
		$string = "";
		
		foreach ($tags as $tag) { 
			$string .= $tag->name;

			if ($tag !== end($tags)){
				$string.= ',';
			}
		} 
		
		return $string;
	} 


	// Removes the white spaces from wp_title
	function title_remove_space($title) {
		return trim($title);
	}

	add_filter('wp_title', 'title_remove_space');