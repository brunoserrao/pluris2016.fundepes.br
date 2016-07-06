<?php
/*
	Plugin Name: Bruno Artigos
	Plugin URI: http://www.brunoserrao.com/bs-articles
	Description: Criar posts do tipo artigo
	Version: 1.0.0
	Author: Bruno Serrao
	Author URI: http://www.brunoserrao.com
	License: GPL3
*/ 
class bsArticles {

	public function __construct() {
		$this->load_plugin_textdomain();
		add_action( 'init', array($this, 'register_post_type'), 0 );
	}

	/**
	 * Set text domain
	 *
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'bs-articles', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register post type article
	 *
	 */
	public function register_post_type(){
		register_post_type( 'bs_posts_articles',
			apply_filters( 'bs_register_post_type_article',
				array(
					'labels'              => array(
						'name'                  => __( 'Articles', 'bs-articles' ),
						'singular_name'         => __( 'Article', 'bs-articles' ),
						'menu_name'             => __( 'Articles', 'bs-articles' ),
						'add_new'               => __( 'Add article', 'bs-articles' ),
						'add_new_item'          => __( 'Add New article', 'bs-articles' ),
						'edit'                  => __( 'Edit', 'bs-articles' ),
						'edit_item'             => __( 'Edit article', 'bs-articles' ),
						'new_item'              => __( 'New article', 'bs-articles' ),
						'view'                  => __( 'View article', 'bs-articles' ),
						'view_item'             => __( 'View article', 'bs-articles' ),
						'search_items'          => __( 'Search articles', 'bs-articles' ),
						'not_found'             => __( 'No articles found', 'bs-articles' ),
						'not_found_in_trash'    => __( 'No articles found in trash', 'bs-articles' )
					),
					'description'         => __( 'This is where you can add new article.', 'bs-articles' ),
					'public'              => true,
					'show_ui'             => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => true,
					'supports'            => array( 'title', 'editor'),
					'has_archive'         => true,
					'show_in_nav_menus'   => true,
					'menu_icon'           => 'dashicons-paperclip'
				)
			)
		);
	}
}

new bsArticles();