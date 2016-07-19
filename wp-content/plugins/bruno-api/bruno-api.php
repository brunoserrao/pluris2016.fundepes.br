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
class BrunoApi{
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

		register_rest_route( $this->namespace, '/galeria/upload',
			array(
				'methods'   => 'POST',
				'callback'  => array($this, 'upload')
			)
		);

		register_rest_route( $this->namespace, '/validar',
			array(
				'methods'   => 'POST',
				'callback'  => array($this, 'validar')
			)
		);

		register_rest_route( $this->namespace, '/login',
			array(
				'methods'   => 'POST',
				'callback'  => array($this, 'login')
			)
		);

		register_rest_route( $this->namespace, '/logout',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'logout')
			)
		);

		register_rest_route( $this->namespace, '/cadastro',
			array(
				'methods'   => 'POST',
				'callback'  => array($this, 'cadastro')
			)
		);

		register_rest_route( $this->namespace,'/artigos',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'artigos')
			)
		);

		register_rest_route( $this->namespace, '/artigos/(?P<id>[\d]+)',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'artigos')
			)
		);

		register_rest_route( $this->namespace,'/foruns',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'foruns')
			)
		);

		register_rest_route( $this->namespace, '/foruns/(?P<id>[\d]+)',
			array(
				'methods'   => 'GET',
				'callback'  => array($this, 'foruns')
			)
		);

		register_rest_route( $this->namespace, '/foruns/comentar',
			array(
				'methods'   => 'POST',
				'callback'  => array($this, 'comentar')
			)
		);
	}

	/**
	* Verificar se o usuário existe
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function validar(WP_REST_Request $request){
		$username = $request['username'];
		$password = $request['password'];

		$user = get_user_by('user_email', $username);

		$wp_authenticate = wp_authenticate_email_password( $user, $username, $password );
		
		if (is_wp_error($wp_authenticate)) {
			return new WP_Error( 'rest_type_invalid', __( 'Login Validate Fail.' ), array( 'status' => 404 ) );
		}

		$avatar = get_avatar_data($wp_authenticate->data->ID);

		$usuario = array(
			'ID' 			=> $wp_authenticate->data->ID,
			'user_login' 	=> $wp_authenticate->data->user_login,
			'user_nicename' => $wp_authenticate->data->user_nicename,
			'user_email' 	=> $wp_authenticate->data->user_email,
			'display_name' 	=> $wp_authenticate->data->display_name,
			'hash'			=> base64_encode($wp_authenticate->data->user_login.':'.$password),
			'avatar'		=> array(
				'url' => $avatar['url'],
				'found_avatar' => $avatar['found_avatar']
			)
		);

		$result = array(
			'data' => array(
				'usuario'  => $usuario
			)
		);

		return $result;
	}

	/**
	* Faz o login do usuario
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	private function login(WP_REST_Request $request){
		if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
			return new WP_Error( 'rest_type_invalid', __( 'Empty Auth User' ), array( 'status' => 404 ) );
		}

		$username = sanitize_text_field($_SERVER['PHP_AUTH_USER']);
		$password = sanitize_text_field($_SERVER['PHP_AUTH_PW']);

		$wp_authenticate = wp_authenticate( $username, $password );

		if (is_wp_error($wp_authenticate)) {
			return false;
		}

		return true;
	}

	/**
	* Criar conta do usuário
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function cadastro(WP_REST_Request $request){
		$email = sanitize_text_field($request['email']);
		$username = explode('@', $email)[0];
		$name = explode(' ', sanitize_text_field($request['name']));
		$last_name = sizeof($name) > 0 ? $name[1] : '';
		$password = sanitize_text_field($request['password']);
	
		$data = array(
			'user_login' => $username,
			'user_email' => $email,
			'first_name' => $name[0],
			'last_name' =>  $last_name,
			'user_pass' => $password,
			'role' => 'customer'
		);

		$user_id = wp_insert_user($data);

		if (is_wp_error($user_id)) {
			return new WP_Error( 'rest_type_invalid', __( 'Register new user fail.' ), array( 'status' => 401 ) );
		}

		$request['username'] = $email;
		$request['password'] = $password;

		return $this->validar($request);
	}

	/**
	* Faz o logout do usuario
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function logout(WP_REST_Request $request){
		wp_logout();
		return true;
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

		$role_ids = get_users(
			array(
				'fields' => 'ID',
				'orderby' => 'registered'
			)
		);

		$media_query = new WP_Query(
			array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'post_mime_type' => 'image',
				'author__in' =>  $role_ids,
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => 'image_from_gallery',
						'value' => 1,
						'compare' => '='
					)
				)
			)
		);

		$fotos = array();
		
		foreach ($media_query->posts as $post) {
			$attachment = wp_prepare_attachment_for_js($post->ID);			

			$foto = array(
				'thumb' => wp_get_attachment_image_src($post->ID, 'thumb_galeria' )[0],
				'src' => $attachment['url'],
				'sub' => $attachment['title']
			);

			array_push($fotos, $foto);
		}

		$galeria = new WP_Query(array(
			'post_type' => 'page',
			'page_id' => $pagina_id
		));

		$result = array(
			'data' => array(
				'fotos' => $fotos,
				'titulo' => $galeria->posts[0]->post_title,
				'texto' => strip_shortcodes($galeria->posts[0]->post_content)
			)
		);

		return $result;
	}

	/**
	* Upload de fotos para a galeria
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function upload(WP_REST_Request $request){
		if (!$this->login($request)) {
			return new WP_Error( 'rest_type_invalid', __( 'Login Fail.' ), array( 'status' => 404 ) );
		}

		if (empty($request['image'])) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid image.' ), array( 'status' => 401 ) );
		}

		$upload_dir = wp_upload_dir();
		$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
		$img = $request['image'];
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$decoded = base64_decode($img);
		$filename = 'galeria.jpg';
		$hashed_filename  = md5( $filename . microtime() ) . '_' . $filename;
		$image_upload = file_put_contents( $upload_path . $hashed_filename, $decoded );

		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = 'image/jpg';
		$file['size']     = filesize( $upload_path . $hashed_filename );

		$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );

		$filename = $file_return['file'];
		$post_title = !empty($request['title']) ? $request['title'] : preg_replace('/\.[^.]+$/', '', basename($filename));

		$attachment = array(
			'post_mime_type' => $file_return['type'],
			'post_title' => $post_title,
			'post_content' => '',
			'post_status' => 'inherit',
			'guid' => $file_return['url']
		);

		$attach_id = wp_insert_attachment( $attachment, $filename );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		update_post_meta( $attach_id, 'image_from_gallery', true );

		$result = array(
			'data' => $attach_id
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
	* Requisitar posts do tipo Fórum
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function foruns(WP_REST_Request $request) {
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

		$query_args['post_type'] = 'dwqa-question';
		$query_args['posts_per_page'] = -1;

		$posts_query = new WP_Query();
		$query_result = $posts_query->query( $query_args );

		if (empty($query_result)) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid resource.' ), array( 'status' => 404 ) );
		}

		$parse_result = $this->__parse_result($query_result);
		$comentarios = get_comments(array('post_id' => $id));
		
		$result = array(
			'data' => $parse_result,
			'comentarios' => $comentarios
		);

		if (empty($id)) {
			$query_args = array();
			$query_args['post_type'] = 'page';
			$query_args['page_id'] = 1011;

			$posts_query = new WP_Query();
			$query_result = $posts_query->query( $query_args );

			$result['descricao'] = $query_result[0]->post_content;
		}
		
		return $result;
	}

	/**
	* Salvar comentário no Forum
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function comentar(WP_REST_Request $request) {
		if (!$this->login($request)) {
			return new WP_Error( 'rest_type_invalid', __( 'Login Fail.' ), array( 'status' => 404 ) );
		}

		if (empty($request['id']) or empty($request['comentario'])) {
			return new WP_Error( 'rest_type_invalid', __( 'Invalid post Id or Comment.' ), array( 'status' => 401 ) );
		}

		$id = sanitize_text_field($request['id']);
		$user_id = get_current_user_id();
		$comentario = ($request['comentario']);
		$user = get_user_meta($user_id);

		$data = array(
		    'comment_post_ID' => $id,
		    'comment_content' => $comentario,
		    'comment_type' => 'dwqa-comment',
		    'comment_approved' => 1,
		    'comment_author' => $user['first_name'][0] . ' ' . $user['last_name'][0],
			'user_id' => $user_id
		);

		$comentario_id = wp_insert_comment($data);

		if (is_wp_error($comentario_id)) {
			return new WP_Error( 'rest_type_invalid', __( 'Erro ao criar comentário.' ), array( 'status' => 401 ) );
		}

		return array(
			'data' => $comentario_id
		);
	}

	/**
	* Requisitar posts do tipo Eventos
	*
	* @param WP_REST_Request $request
	* @return array $result
	*/
	public function artigos(WP_REST_Request $request) {
		$id = !empty($request['id']) ? $request['id'] : false;
		$thumb_size = !empty($id) ? 'full' : 'thumbnail';
		
		$paged = !empty($request['paged']) ? $request['paged'] : 1;
		
		if (!empty($request['s'])) {
			$query_args['s'] = $request['s'];
		}

		if (!empty($id)) {
			$query_args = array();
			array_push($this->default_fields,'post_content');
			$query_args['p'] = $id;
		}

		$query_args['post_type'] = 'bs_posts_articles';
		$query_args['paged'] = $paged;
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

		if (empty($id)) {
			$total_posts = $posts_query->found_posts;

			$query_args = array();
			$query_args['post_type'] = 'page';
			$query_args['page_id'] = 261;

			$posts_query = new WP_Query();
			$query_result = $posts_query->query( $query_args );

			$result['descricao'] = $query_result[0]->post_content;

			$paging = array(
				'actual_page' => (int) $paged,
				'total_pages' => (int) ceil( $total_posts / $this->posts_per_page ),
				'total_posts' => (int) $total_posts
			);

			$result['paging'] = $paging;
		}
		
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