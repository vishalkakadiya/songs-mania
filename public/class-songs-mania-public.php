<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/vishalkakadiya
 * @since      1.0.0
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/public
 * @author     Vishal Kakadiya <vishalkakadiya123@gmail.com>
 */
class Songs_Mania_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/songs-mania-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/songs-mania-public.js', array( 'wp-util' ), $this->version, false );

        wp_localize_script( $this->plugin_name, "smSongLikeNonce", wp_create_nonce( "sm-song-is-great" ) );

	}

    /**
     * Handles saving the meta box.
     *
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     *
     * @since    1.0.0
     *
     * @param int     $post_id Post ID.
     */
	public function refresh_songs_cache( $post_id ) {

        if ( 'song' === get_post_type( $post_id ) ) {
            $this->get_songs( $force_refresh = true );
        }
    }

    /**
     * Retrieve latest 4 songs and cache the results.
     *
     * @param bool $force_refresh Optional. Whether to force the cache to be refreshed.
     *                            Default false.
     *
     * @return array|WP_Error Array of 'song' post type WP_Post objects,
     *                        WP_Error object otherwise.
     */
    public function get_songs( $force_refresh = false ) {
        // Check for the existing cache is available.
        $sm_latest_songs = wp_cache_get( 'sm_latest_songs', 'latest_songs' );

        // If nothing is found, build the object.
        if ( true === $force_refresh || false === $sm_latest_songs ) {
            // latest songs.
            $sm_latest_songs = new WP_Query(
                array(
                    'post_type'                 => 'song',
                    'posts_per_page'            => 4,
                    'no_found_rows'             => true,
                    'update_post_meta_cache'    => false,
                    'update_post_term_cache'    => false,
                )
            );

            if ( ! is_wp_error( $sm_latest_songs ) && $sm_latest_songs->have_posts() ) {
                // Cache the whole WP_Query object in the cache and store it for 5 minutes (300 secs).
                wp_cache_set( 'sm_latest_songs', $sm_latest_songs, 'latest_songs', 60 * MINUTE_IN_SECONDS );
            }
        }
        return $sm_latest_songs;
    }

	public function display_songs( $atts ) {

        $songs_query = $this->get_songs();

        // The Loop
        if ( $songs_query->have_posts() ) {
            echo '<ul>';
            while ( $songs_query->have_posts() ) {
                $songs_query->the_post();

                /*
                 * Include the Post-Format-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                 */
                include( 'templates/display-songs.php' );

            }
            echo '</ul>';
            /* Restore original Post Data */
            wp_reset_postdata();
        } else {
            esc_html_e( 'Sorry, no posts matched your criteria.', 'songs-mania' );
        }
    }

}
