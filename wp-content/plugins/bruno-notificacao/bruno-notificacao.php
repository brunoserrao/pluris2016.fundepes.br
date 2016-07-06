<?php
/*
	Plugin Name: Bruno Notifications
	Plugin URI: http://www.brunoserrao.com/bs-notifications
	Description: Habilitar o botão de notificação nos posts
	Version: 1.0.0
	Author: Bruno Serrao
	Author URI: http://www.brunoserrao.com
	License: GPL3
*/ 
class brunoNotificacao {

	public function __construct() {
		$this->load_plugin_textdomain();

		add_action( 'admin_menu', array($this, 'bs_notifications_admin_menu'));
		add_action( 'admin_init', array($this,'register_admin_custom_fields'));

		add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'register_link_permission'), 10, 4);

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_plugin_scripts' ),1, 3 );

		add_filter('manage_posts_columns', array($this, 'add_new_bs_posts_events'), 10);
		add_action('manage_posts_custom_column', array($this, 'add_bs_posts_button_notify'), 10, 2);

		add_action('wp_ajax_bs_send_notification', array($this, 'bs_notifications_send_ajax_notification'));
		add_action('wp_ajax_nopriv_bs_send_notification', array($this, 'bs_notifications_send_ajax_notification'));
	}

	public function bs_notifications_send_ajax_notification(){
		$url = 'https://onesignal.com/api/v1/notifications';

		$id = sanitize_text_field($_REQUEST['id']);

		$post_query = new WP_Query();

		$post = $post_query->query(array(
			'p' => $id
		));

		$redirect = '';

		switch ($post[0]->post_type) {
			case 'bs_posts_events':
				$redirect = 'app.programacao/id';
				break;
			case 'bs_posts_articles':
				$redirect = 'app.artigo/id';
				break;
			case 'post':
				$redirect = 'app.noticias/id';
				break;
			default:
				break;
		}

		$qtx = new QTX_Translator();

		$args = array(
			'method' => 'POST',
			'sslverify' => false,
			'headers' => array(
				'Authorization'  => 'Basic ' . get_option('bs_notifications_one_signal_api_key'),
				'Content-Type' => 'application/json'
			),
			'body' => json_encode(array(
				'app_id' => get_option('bs_notifications_one_signal_app_id'),
				'included_segments' => array('All'),
				'data' => array(
					'redirect' => $redirect,
					'id' => $id
				),
				'contents' => array(
					'en' => $qtx->translate_text($post[0]->post_title, 'en'),
					'pt' => $qtx->translate_text($post[0]->post_title, 'pb'),
					'es' => $qtx->translate_text($post[0]->post_title, 'es')
				),
				'large_icon' => 'pushicon',
				'ios_badgeType' => 'Increase',
				'ios_badgeCount' => 1
			))
		);

		// $response = wp_remote_request( $url, $args );
		
		if ( is_wp_error( $response ) ) {
			echo $response->get_error_message();
		}

		wp_die();
	}

	/**
	 * Set text domain
	 *
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'bs-notifications', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register post type events
	 *
	 */
	public function register_link_permission( $actions, $plugin_file, $plugin_data, $context ) {
		array_unshift($actions, '<a href="'.menu_page_url('bspostnotifications', false).'">'.__('Settings').'</a>');
		return $actions;
	}

	/**
	 * Register Menu Settings Link
	 *
	 */
	public function bs_notifications_admin_menu(){
		add_options_page(
			__('Notification Settings', 'bs-notifications'),
			__('Notification Settings', 'bs-notifications'),
			'manage_options',
			'bspostnotifications',
			array(
				$this,
				'settings_page'
			)
		);
	}

	public function register_admin_custom_fields(){
		register_setting( 'bs_notifications_post_group', 'bs_notifications_one_signal_app_id');
		register_setting( 'bs_notifications_post_group', 'bs_notifications_one_signal_api_key');
	}

	/**
	 * Add Column Notification
	 *
	 * @param  array $defaults
	 * @return  array $defaults
	 */
	public function add_new_bs_posts_events($defaults) {
		 $defaults['btn_notificar'] = __( 'Notification', 'bs-notifications' );
		 return $defaults;
	}

	/**
	 * Add button Send notification
	 *
	 * @param  string $column_name
	 * @return null
	 */
	public function add_bs_posts_button_notify($column_name, $post_ID) {
		$nonce = wp_create_nonce( 'bs_events_nonce_' . $post_ID );
		switch ($column_name) {
			case 'btn_notificar':
				echo  '<input class="page-title-action button-notify" type="button" data-id="'.$post_ID.'" data-nonce="'.$nonce.'" value="'. __( 'Send', 'bs-notifications' ) .'" />';
				break;
			default:
				break;
		}
	}

	public function enqueue_plugin_scripts(){
		wp_enqueue_script( 'bs-notifications-plugin-local-js', plugins_url('js/scripts.js', __FILE__ ), array('jquery'), false, true );
		wp_enqueue_style(  'bs-notifications-plugin-local-css',  plugins_url('css/styles.css', __FILE__ ) );
	}

	/**
	 * Register post type events
	 *
	 */
	public function settings_page(){
		?>
			<div class="wrap">
				<h2><?php echo __('Notifications', 'bs-notifications')?></h2>

				<form method="post" action="options.php">
				<?php settings_fields('bs_notifications_post_group'); ?>

					<table class="form-table">
						<tr valign="top">
							<th scope="row">OneSignal App ID</th>
							<td>
								<input type="text" class="regular-text" name="bs_notifications_one_signal_app_id" value="<?php echo get_option('bs_notifications_one_signal_app_id') ?>"/>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">REST API Key</th>
							<td>
								<input type="text" class="regular-text" name="bs_notifications_one_signal_api_key" value="<?php echo get_option('bs_notifications_one_signal_api_key') ?>"/>
							</td>
						</tr>
					</table>

					<p class="submit">
						<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Save Changes');?>">
					</p>
				</form>
			</div>
		<?php	
	}
}

new brunoNotificacao();