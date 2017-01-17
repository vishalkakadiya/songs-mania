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
 * Defines the plugin name, version, and hooks which is
 * used in public area.
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
	 * @param    string $plugin_name       The name of the plugin.
	 * @param    string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Add plugin js with wp-util dependency.
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/songs-mania-public.js', array( 'wp-util' ), $this->version, true );

		wp_localize_script( $this->plugin_name, 'smSongLikeNonce', array( wp_create_nonce( 'sm-song-likes-great' ) ) );

		wp_enqueue_script( $this->plugin_name );

	}

	/**
	 * Handles saving the meta box.
	 *
	 * @link    https://10up.github.io/Engineering-Best-Practices/php/
	 *
	 * @since   1.0.0
	 * @param   int $post_id Post ID.
	 */
	public function refresh_songs_cache( $post_id ) {

		if ( 'song' === get_post_type( $post_id ) ) {
			$this->get_songs( $force_refresh = true );
		}
	}

	/**
	 * Retrieve latest 4 songs and cache the results.
	 *
	 * @link    https://10up.github.io/Engineering-Best-Practices/php/
	 *          https://developer.wordpress.org/reference/functions/wp_cache_get/
	 *
	 * @since   1.0.0
	 *
	 * @param   bool $force_refresh Optional. Whether to force the cache to be refreshed.
	 *                         Default false.
	 *
	 * @return  array|WP_Error Array of 'song' post type WP_Post objects,
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

	/**
	 * Display songs when '[sm_display_songs]' shortcode is added to any post or page.
	 *
	 * @since 1.0.0
	 *
	 * @param   array $atts array of attributes that used in shortcode.
	 */
	public function display_songs( $atts ) {

		$songs_query = $this->get_songs();

		// The Loop.
		if ( $songs_query->have_posts() ) {
			while ( $songs_query->have_posts() ) {
				$songs_query->the_post();

				// Include song display template.
				require( plugin_dir_path( __FILE__ ) . '/templates/display-songs.php' );

			}
			/* Restore original Post Data */
			wp_reset_postdata();
		} else {
			esc_html_e( 'Sorry, no posts matched your criteria.', 'songs-mania' );
		}
	}

	/**
	 * Increasing song likes count for particular song.
	 *
	 * @link    http://php.net/manual/en/function.filter-input.php
	 *          https://developer.wordpress.org/reference/functions/wp_send_json_success/
	 *          https://developer.wordpress.org/reference/functions/wp_send_json_error/
	 *
	 * @since   1.0.0
	 */
	public function sm_like_song() {
		// Check if nonce is set.
		$sm_nonce = filter_input( INPUT_POST, 'sm_nonce', FILTER_SANITIZE_STRING );
		$nonce = isset( $sm_nonce ) ? $sm_nonce : '';

		$post_id = filter_input( INPUT_POST, 'post_id', FILTER_VALIDATE_INT );

		// Check if nonce is valid.
		if ( wp_verify_nonce( $nonce, 'sm-song-likes-great' ) && isset( $post_id ) && ! empty( $post_id ) ) {
			$old_likes_count = get_post_meta( $post_id, 'sm_song_likes', true );
			$new_likes_count = $old_likes_count + 1;
			update_post_meta( $post_id, 'sm_song_likes', $new_likes_count );

			$return = array(
				'likes_count'	=> $new_likes_count,
			);

			wp_send_json_success( $return );
		} else {
			wp_send_json_error();
		}
	}

}
