<?php
/*
	Plugin Name: Bruno API
	Plugin URI: http://www.brunoserrao.com/bruno-api
	Description: Implmements custom endpoints API.
	Version: 1.0.0
	Author: Bruno Serrao
	Author URI: http://www.brunoserrao.com
	License: GPL3
*/ 
class BrunoApi {
	private $version;
	private $namespace;

	public function __construct() {
		$this->version = 1;
		add_action('rest_api_init', array($this, 'register_routes'));
	}

	public function register_routes() {
		$this->namespace = 'api' .'/v'. $this->version ;

		register_rest_route( $this->namespace,'/noticias',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'noticias')
			)
		);

		register_rest_route( $this->namespace, '/noticias/(?P<id>[\d]+)',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'noticias')
			)
		);
	}

	public function noticias($request) {
		$id = !empty($request['id']) ? $request['id'] : false;
		$thumb_size = !empty($id) ? 'full' : 'thumbnail';
		$paged = !empty($request['paged']) ? $request['paged'] : 1;
		$posts_per_page = !empty($request['posts_per_page']) ? $request['posts_per_page'] : get_option('posts_per_page');

		$default_fields = array('ID','post_date','post_title','post_excerpt');
		$query_args = array();
		
		if (!empty($request['fields'])) {
			$default_fields = array_merge($default_fields, explode(',',$request['fields']));
		}	
	
		if (!empty($request['s'])) {
			$query_args['s'] = $request['s'];
		}

		$query_args['posts_per_page'] = $posts_per_page;
		$query_args['paged'] = $paged;

		if (!empty($id)) {
			$query_args = array();
			array_push($default_fields,'post_content');
			$query_args['p'] = $id;
		}

		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );

		if (empty($query_result)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		foreach ($query_result as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (!in_array($key2, $default_fields)) {
					unset($query_result[$key]->$key2);
				}
			}

			if (isset($query_result[$key]->post_content)) {
				 $query_result[$key]->post_content = wpautop( $query_result[$key]->post_content );
			}

			$thumbnail_id = get_post_thumbnail_id( $query_result[$key]->ID );
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id, $thumb_size);

			$query_result[$key]->thumbnail = $thumbnail[0];
			$query_result[$key]->post_date = get_the_date('', $query_result[$key]->ID );
		}

		$total_posts = $posts_query->found_posts;
		$max_pages = ceil( $total_posts / $posts_per_page );


		$result = array(
			'data' => $query_result
		);

		if (empty($id)) {
			$paging = array(
				'actual_page' => $paged,
				'total_pages' => ceil( $total_posts / $posts_per_page ),
				'total_posts' => $total_posts
			);

			$result['paging'] = $paging;
		}
		
		return $result;
	}
}

new BrunoApi();