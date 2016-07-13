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
	private $default_fields;
	private $posts_per_page;
	private $paged;

	public function __construct() {
		$this->version = 1;
		$this->posts_per_page = 10;
		$this->__default_fields();

		add_action('rest_api_init', array($this, 'register_routes'));
	}

	/**
	* Registrar rotas para o App Pluris
	*
	* @param nulll
	* @return null
	*/
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

		register_rest_route( $this->namespace, '/pagina/(?P<id>[\d]+)',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'pagina')
			)
		);

		register_rest_route( $this->namespace,'/home',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'home')
			)
		);

		register_rest_route( $this->namespace,'/eventos',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'eventos')
			)
		);

		register_rest_route( $this->namespace, '/eventos/(?P<id>[\d]+)',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'eventos')
			)
		);

		register_rest_route( $this->namespace, '/galeria',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'galeria')
			)
		);
	}

	/**
	* Requisitar conteúdo da Home
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function home(WP_REST_Request $request){
		$noticias = $this->noticias($request);

		$request['id'] = 2;
		$pagina = $this->pagina($request);
		
		$result = array(
			'data' => array(
				'noticias' 		=> $noticias,
				'pagina'	=> $pagina
			)
		);

		return $result;
	}

	/**
	* Requisitar fotos da galeria
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function galeria(WP_REST_Request $request){
		$pagina_id = 259;
		$ids = get_post_gallery($pagina_id, false);

		if (empty($ids)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		$ids = explode(",", $ids['ids']);

		$fotos = array();

		foreach ($ids as $id) {
			$attachment = wp_prepare_attachment_for_js($id);

			$sub = !empty($attachment['title']) ? $attachment['title'] : '';

			$foto = array(
				'thumb' => wp_get_attachment_image_src($id, 'thumb_galeria' )[0],
				'src' => wp_get_attachment_thumb_url($id),
				'sub' => $sub
			);

			array_push($fotos, $foto);
		}

		$wp_query = new WP_Query(array(
			'post_type' => 'page',
			'page_id' => $pagina_id
		));

		// echo "<pre>";
		// die(print_r($wp_query));
		// echo "</pre>";
		
		$result = array(
			'data' => array(
				'fotos' => $fotos,
				'titulo' => $wp_query->posts[0]->post_title,
				'texto' => strip_shortcodes($wp_query->posts[0]->post_content)
			)
		);

		return $result;
	}

	/**
	* Requisitar uma página por ID
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function pagina(WP_REST_Request $request){
		$id = $request['id'];
		$query_args = array();
		
		$this->__merge_fields(array('post_content','post_excerpt'));
		
		$query_args['post_type'] = 'page';
		$query_args['page_id'] = $id;

		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );

		if (empty($query_result)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		$parse_result = $this->__parse_result($query_result);

		$result = array(
			'data' => $parse_result[0]
		);

		return $result;
	}

	/**
	* Requisitar posts ou post por ID
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function noticias(WP_REST_Request $request) {
		$id = !empty($request['id']) ? $request['id'] : false;
		$thumb_size = !empty($id) ? 'full' : 'thumbnail';
		
		$this->posts_per_page = !empty($request['posts_per_page']) ? $request['posts_per_page'] : get_option('posts_per_page');
		$this->paged = !empty($request['paged']) ? $request['paged'] : 1;
		
		if (!empty($request['fields'])) {
			$this->__merge_fields(explode(',',$request['fields']));
		}	
	
		if (!empty($request['s'])) {
			$query_args['s'] = $request['s'];
		}

		if (!empty($id)) {
			$query_args = array();
			array_push($this->default_fields,'post_content');
			$query_args['p'] = $id;
		}

		$query_args['post_type'] = 'post';
		$query_args['paged'] = $this->paged;

		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );

		if (empty($query_result)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		$parse_result = $this->__parse_result($query_result);

		$result = array(
			'data' => $parse_result
		);

		if (empty($id)) {
			$total_posts = $posts_query->found_posts;

			$paging = array(
				'actual_page' => (int) $this->paged,
				'total_pages' => (int) ceil( $total_posts / $this->posts_per_page ),
				'total_posts' => (int) $total_posts
			);

			$result['paging'] = $paging;
		}
		
		return $result;
	}

	/**
	* Requisitar posts do tipo Eventos
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function eventos(WP_REST_Request $request) {
		$id = !empty($request['id']) ? $request['id'] : false;
		$thumb_size = !empty($id) ? 'full' : 'thumbnail';
		
		$this->paged = !empty($request['paged']) ? $request['paged'] : 1;
		
		if (!empty($request['fields'])) {
			$this->__merge_fields(explode(',',$request['fields']));
		}	
	
		if (!empty($request['s'])) {
			$query_args['s'] = $request['s'];
		}

		if (!empty($id)) {
			$query_args = array();
			array_push($this->default_fields,'post_content');
			$query_args['p'] = $id;
		}

		$query_args['post_type'] = 'bs_posts_events';
		$query_args['posts_per_page'] = -1;

		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );

		if (empty($query_result)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		$parse_result = $this->__parse_result($query_result);

		$result = array(
			'data' => $parse_result
		);
		
		return $result;
	}

	/**
	* Requisitar posts ou post por ID
	*
	* @param null
	* @return null
	*/
	private function __default_fields(){
		$this->default_fields = array('ID','post_date','post_title','post_excerpt');
	}

	/**
	* Requisitar posts ou post por ID
	*
	* @param array $fields
	* @return none
	*/
	private function __merge_fields(array $fields){
		$this->default_fields = array_merge($this->default_fields, $fields);
	}

	/**
	* Remove campos desnecessários do $post
	*
	* @param WP_Object $query_result
	* @return WP_Object $query_result
	*/
	private function __parse_result(array $query_result){
		foreach ($query_result as $key => $value) {
			foreach ($value as $key2 => $value2) {
				if (!in_array($key2, $this->default_fields)) {
					unset($query_result[$key]->$key2);
				}
			}

			if (isset($query_result[$key]->post_content)) {
				$query_result[$key]->post_content = wpautop( $query_result[$key]->post_content );
			}

			$thumbnail_id = get_post_thumbnail_id( $query_result[$key]->ID );
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id, $thumb_size);

			$query_result[$key]->link = get_permalink( $query_result[$key]->ID );
			$query_result[$key]->thumbnail = $thumbnail[0];
			$query_result[$key]->post_date = get_the_date('', $query_result[$key]->ID );
			$query_result[$key]->metas = get_post_meta( $query_result[$key]->ID );
		}

		return $query_result;
	}
}

new BrunoApi();