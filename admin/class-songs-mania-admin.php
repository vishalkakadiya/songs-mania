<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/vishalkakadiya
 * @since      1.0.0
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks which is
 * used in admin area.
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/admin
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Songs_Mania_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register `song` post_type
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_post_type/
	 *
	 * @since    1.0.0
	 */
	public function register_post_type() {

		$args = array(
			'label'                 => __( 'Songs', 'songs-mania' ),
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'song' ),
			'capability_type'       => 'post',
			'has_archive'           => true,
			'taxonomies'            => array( 'category' ),
			'hierarchical'          => false,
			'menu_position'         => null,
			'supports'              => array( 'title', 'editor', 'category', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);

		register_post_type( 'song', $args );
	}

	/**
	 * Register `Song Manager` role
	 *
	 * @link    https://vip.wordpress.com/functions/wpcom_vip_add_role/
	 *
	 * @since    1.0.0
	 */
	public function register_role() {

		wpcom_vip_add_role( 'song_manager', 'Song Manager', array(
			'read'                 => true,
			'edit_posts'           => true,
			'edit_others_posts'    => true,
			'edit_published_posts' => true,
			'read_private_posts'   => true,
		) );
	}

	/**
	 * Registering metaboxes for `song` posts.
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
	 *
	 * @since    1.0.0
	 */
	function register_meta_boxes() {
		add_meta_box( 'sm-song-meta', __( 'Song\'s Detail', 'songs-mania' ), array( $this, 'render_metabox' ), 'song', 'normal', 'default' );
	}

	/**
	 * Renders the meta box.
	 *
	 * It is callback function of register_meta_boxes()
	 *
	 * @since    1.0.0
	 */
	function render_metabox() {
		// Nonce name needed to verify where the data originated.
		wp_nonce_field( 'sm_song_action', 'sm_nonce' );

		require_once( plugin_dir_path( __FILE__ ) . '/templates/songs-meta-fields.php' );
	}

	/**
	 * Handles saving the meta box.
	 *
	 * @since    1.0.0
	 *
	 * @param int $post_id Post ID.
	 * @return null
	 */
	public function save_meta( $post_id ) {
		// Check if not an autosave request.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Add nonce for security and authentication.
		$nonce_name   = filter_input( INPUT_POST, 'sm_nonce', FILTER_SANITIZE_STRING );
		$nonce_action = 'sm_song_action';

		// Check if nonce is set.
		if ( isset( $nonce_name ) ) {

			// Check if nonce is valid.
			if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
				return;
			}

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			return;
		}

		$singer = '';
		$sm_song_singer = filter_input( INPUT_POST, 'sm_song_singer', FILTER_SANITIZE_STRING );
		if ( isset( $sm_song_singer ) ) {
			$singer = sanitize_text_field( $sm_song_singer );
		}
		update_post_meta( $post_id, 'sm_song_singer', $singer );

		$singer_email = '';
		$sm_song_singer_email = filter_input( INPUT_POST, 'sm_song_singer_email', FILTER_VALIDATE_EMAIL );
		if ( isset( $sm_song_singer_email ) ) {
			$singer_email = sanitize_email( $sm_song_singer_email );
		}
		update_post_meta( $post_id, 'sm_song_singer_email', $singer_email );

		$likes = 0;
		$sm_song_likes = filter_input( INPUT_POST, 'sm_song_likes', FILTER_VALIDATE_INT );
		if ( ! empty( $sm_song_likes ) ) {
			$likes = $sm_song_likes;
		}
		update_post_meta( $post_id, 'sm_song_likes', $likes );

		$viewer = 0;
		$sm_song_viewer = filter_input( INPUT_POST, 'sm_song_viewer', FILTER_VALIDATE_INT );
		if ( ! empty( $sm_song_viewer ) ) {
			$viewer = $sm_song_viewer;
		}
		update_post_meta( $post_id, 'sm_song_viewer', $viewer );
	}

}
