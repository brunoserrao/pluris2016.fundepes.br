<?php
/*
	Plugin Name: Bruno Eventos
	Plugin URI: http://www.brunoserrao.com/bs-events
	Description: Criar eventos para ser exibido no calendário
	Version: 1.0.1
	Author: Bruno Serrao
	Author URI: http://www.brunoserrao.com
	License: GPL3
*/ 
class bsEvents {

	public function __construct() {
		$this->load_plugin_textdomain();
		add_action( 'init', array($this, 'register_post_type'), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_events_metaboxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_plugin_scripts' ),1, 3 );

		add_action( 'admin_menu', array($this, 'bs_events_admin_menu'));
		add_action( 'admin_init', array($this,'register_admin_custom_fields'));
	}

	/**
	 * Register Menu Settings Link
	 *
	 */
	public function bs_events_admin_menu(){
		add_submenu_page(
			'edit.php?post_type=bs_posts_events', 
			__('Email eventos','bs-events'), 
			__('Email eventos','bs-events'), 
			'manage_options', 
			'bseventsemail', 
			array(
				$this,
				'settings_page'
			)
		);
	}

	/**
	 * Register Settings Email field
	 *
	 */
	public function register_admin_custom_fields(){
		register_setting( 'bs_events_post_group', 'bs_events_email');
	}

	/**
	 * Register post type events
	 *
	 */
	public function settings_page(){
		?>
			<div class="wrap">
				<h2><?php echo __('E-mail para receber as perguntas', 'bs-events')?></h2>

				<form method="post" action="options.php">
				<?php settings_fields('bs_events_post_group'); ?>

					<table class="form-table">
						<tr valign="top">
							<th scope="row">E-mail</th>
							<td>
								<input type="text" class="regular-text" name="bs_events_email" value="<?php echo get_option('bs_events_email') ?>"/>
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

	/**
	 * Set text domain
	 *
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'bs-events', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register post type events
	 *
	 */
	public function register_post_type(){
		register_post_type( 'bs_posts_events',
			apply_filters( 'bs_register_post_type_event',
				array(
					'labels'              => array(
						'name'                  => __( 'Events', 'bs-events' ),
						'singular_name'         => __( 'Event', 'bs-events' ),
						'menu_name'             => __( 'Events', 'bs-events' ),
						'add_new'               => __( 'Add event', 'bs-events' ),
						'add_new_item'          => __( 'Add New event', 'bs-events' ),
						'edit'                  => __( 'Edit', 'bs-events' ),
						'edit_item'             => __( 'Edit event', 'bs-events' ),
						'new_item'              => __( 'New event', 'bs-events' ),
						'view'                  => __( 'View event', 'bs-events' ),
						'view_item'             => __( 'View event', 'bs-events' ),
						'search_items'          => __( 'Search events', 'bs-events' ),
						'not_found'             => __( 'No events found', 'bs-events' ),
						'not_found_in_trash'    => __( 'No events found in trash', 'bs-events' ),
						'featured_image'        => __( 'Event Image', 'bs-events' ),
						'set_featured_image'    => __( 'Set event image', 'bs-events' ),
						'remove_featured_image' => __( 'Remove event image', 'bs-events' ),
						'use_featured_image'    => __( 'Use as event image', 'bs-events' ),
					),
					'description'         => __( 'This is where you can add new events.', 'bs-events' ),
					'public'              => true,
					'show_ui'             => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => false,
					'rewrite'             => false,
					'query_var'           => true,
					'supports'            => array( 'title', 'editor', 'thumbnail'),
					'has_archive'         => true,
					'show_in_nav_menus'   => true,
					'menu_icon'           => 'dashicons-tickets-alt',
					'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' )
				)
			)
		);

		register_taxonomy(  
			'events_categories',
			'bs_posts_events',
			array(
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'show_admin_column' => true,
				'hierarchical'		=> true,  
				'label'				=> __('Categories'),
				'query_var'			=> true,
				'rewrite'			=> array(
				'slug'				=> 'events_categories',
				'with_front'		=> true
				)
			)
		);
	}

	/**
	 * Init Meta Box
	 *
	 * @param  int $post_id
	 * @param  object $post
	 */
	public function add_events_metaboxes(){
		add_meta_box( 'bs_events_date_form_html', __( 'Event Date', 'bs-events' ), array($this, 'bs_events_date_form_html'), 'bs_posts_events', 'side', 'default');
	}

	/**
	 * Render Meta Box
	 *
	 */
	public function bs_events_date_form_html() {
		global $post;

		// Noncename needed to verify where the data originated
		echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

		// Get the location data if its already been entered
		$date_start = get_post_meta($post->ID, 'date_start', true);
		$time_start = get_post_meta($post->ID, 'time_start', true);

		$date_end = get_post_meta($post->ID, 'date_end', true);
		$time_end = get_post_meta($post->ID, 'time_end', true);

		// Echo out the field
		echo '<div class="bs-date-time-meta-box">';
			echo '<label>'. __( 'Start Date and Time', 'bs-events' ).'</label>';
			
			echo '<input type="date" name="date_start" value="' . $date_start  . '" class="date" />';
			echo '<input type="time" name="time_start" value="' . $time_start  . '" class="time" />';

			echo '<label>'. __( 'Finish Date and Time', 'bs-events' ).'</label>';

			echo '<input type="date" name="date_end" value="' . $date_end  . '" class="date" />';
			echo '<input type="time" name="time_end" value="' . $time_end  . '" class="time" />';
		echo '</div>';
	}

	/**
	 * Check if we're saving, the trigger an action based on the post type.
	 *
	 * @param  int $post_id
	 * @param  object $post
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if ( !wp_verify_nonce( $_POST['eventmeta_noncename'], plugin_basename(__FILE__) )) {
			return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( !current_user_can( 'edit_post', $post->ID ))
			return $post->ID;

		// OK, we're authenticated: we need to find and save the data
		// We'll put it into an array to make it easier to loop though.
		
		$events_meta['date_start'] = sanitize_text_field($_POST['date_start']);
		$events_meta['time_start'] = sanitize_text_field($_POST['time_start']);
		
		$events_meta['date_end'] = sanitize_text_field($_POST['date_end']);
		$events_meta['time_end'] = sanitize_text_field($_POST['time_end']);

		// Add values of $events_meta as custom fields
		
		foreach ($events_meta as $key => $value) {
			
			if( $post->post_type == 'revision' ){
				return;
			}

			$value = implode(',', (array)$value);

			if(get_post_meta($post->ID, $key, false)) {
				update_post_meta($post->ID, $key, $value);
			} else {
				add_post_meta($post->ID, $key, $value);
			}

			if(!$value){
				delete_post_meta($post->ID, $key); 
			}
		}
	}

	public function enqueue_plugin_scripts(){
		wp_enqueue_script( 'bs-plugin-local-js', plugins_url('js/scripts.js', __FILE__ ), array('jquery'), false, true );
		wp_enqueue_style(  'bs-plugin-local-css',  plugins_url('css/styles.css', __FILE__ ) );
	}
}

new bsEvents();