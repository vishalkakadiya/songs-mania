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
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Songs_Mania_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Songs_Mania_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/songs-mania-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Songs_Mania_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Songs_Mania_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/songs-mania-admin.js', array( 'jquery' ), $this->version, false );

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
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     *
     * @since    1.0.0
     */
    public function register_role() {

        wpcom_vip_add_role( 'song_manager', 'Song Manager', array(
            'read' => true,
            'edit_posts' => true,
            'edit_others_posts' => true,
            'edit_published_posts' => true,
            'read_private_posts' => true,
        ) );
    }

    /**
     * Add
     *
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     *
     * @since    1.0.0
     */
    function register_meta_boxes() {
        add_meta_box( 'vk-song-meta', __( 'Song\'s Detail', 'songs-mania' ), array( $this, 'render_metabox' ), 'song', 'normal', 'default' );
    }


    /**
     * Renders the meta box.
     *
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     *
     * @since    1.0.0
     */
    function render_metabox() {
        global $post;

        // Noncename needed to verify where the data originated
        wp_nonce_field( 'sm_song_action', 'sm_nonce' );
        $singer = get_post_meta( $post->ID, 'sm_song_singer', true );
        $singer_email = get_post_meta( $post->ID, 'sm_song_singer_email', true );
        $likes = get_post_meta( $post->ID, 'sm_song_likes', true );
        $viewers = get_post_meta( $post->ID, 'sm_song_viewer', true );

        $file = 'templates/songs-meta-fields.php';
        include_once( $file ); // Check with validate_file();
//        if ( validate_file( $file ) ) {
//            include_once( $file );
//        }
    }

    /**
     * Handles saving the meta box.
     *
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     *
     * @since    1.0.0
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     * @return null
     */
    public function save_meta( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['sm_nonce'] ) ? $_POST['sm_nonce'] : '';
        $nonce_action = 'sm_song_action';

        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }

        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }

        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        $singer = '';
        if ( isset( $_POST['sm_song_singer'] ) && ! empty( $_POST['sm_song_singer'] ) ) {
            $singer = sanitize_text_field( $_POST['sm_song_singer'] );
        }
        update_post_meta( $post_id, 'sm_song_singer', $singer );

        $singer_email = '';
        if ( isset( $_POST['sm_song_singer_email'] ) && ! empty( $_POST['sm_song_singer_email'] ) ) {
            if ( is_email( $_POST['sm_song_singer_email'] ) ) {
                $singer_email = sanitize_email( $_POST['sm_song_singer_email'] );
            }
        }
        update_post_meta( $post_id, 'sm_song_singer_email', $singer_email );

        $likes = 0;
        if ( isset( $_POST['sm_song_likes'] ) && ! empty( $_POST['sm_song_likes'] ) ) {
            $safe_song_likes = intval( $_POST['sm_song_likes'] );
            if ( $safe_song_likes ) {
                $likes = $_POST['sm_song_likes'];
            }
        }
        update_post_meta( $post_id, 'sm_song_likes', $likes );

        $viewer = 0;
        if ( isset( $_POST['sm_song_viewer'] ) && ! empty( $_POST['sm_song_viewer'] ) ) {
            $safe_song_likes = intval( $_POST['sm_song_viewer'] );
            if ( $safe_song_likes ) {
                $viewer = $_POST['sm_song_viewer'];
            }
        }
        update_post_meta( $post_id, 'sm_song_viewer', $viewer );

    }

}
