<?php
/**
 * Created by PhpStorm.
 * User: vishalkakadiya
 * Date: 22/02/17
 * Time: 11:50 PM
 */

class Tests_Meta_Box extends WP_UnitTestCase {

	protected $post_type;

	function setUp() {
		parent::setUp();

		$this->post_type = 'song';
		register_post_type( $this->post_type );
	}

	function tearDown() {
		_unregister_post_type( $this->post_type );

		parent::tearDown();
	}

	public function test_remove_meta_box() {
		global $wp_meta_boxes;

		// Add a meta boxes to remove.
		add_meta_box( 'sm-song-meta', 'Song\'s Detail', '__return_false', $this->post_type );

		// Confirm it's there.
		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes[ $this->post_type ]['advanced']['default'] );

		// Remove the meta box.
		remove_meta_box( 'sm-song-meta', $this->post_type, 'advanced' );

		// Check that it was removed properly (The meta box should be set to false once that it has been removed)
		$this->assertFalse( $wp_meta_boxes[ $this->post_type ]['advanced']['default']['sm-song-meta'] );
	}

	/**
	 * @ticket 15000
	 */
	public function test_add_meta_box_on_multiple_screens() {
		global $wp_meta_boxes;

		// Add a meta box to three different post types
		add_meta_box( 'sm-song-meta', 'Song\'s Detail', '__return_false', array( $this->post_type, 'post', 'comment', 'attachment' ) );

		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes[ $this->post_type ]['advanced']['default'] );
		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes['post']['advanced']['default'] );
		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes['comment']['advanced']['default'] );
		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes['attachment']['advanced']['default'] );
	}

	/**
	 * @ticket 15000
	 */
	public function test_remove_meta_box_from_multiple_screens() {
		global $wp_meta_boxes;

		// Add a meta box to three different screens.
		add_meta_box( 'sm-song-meta', 'Song\'s Detail', '__return_false', array( $this->post_type, 'post', 'comment', 'attachment' ) );

		// Remove meta box from posts.
		remove_meta_box( 'sm-song-meta', $this->post_type, 'advanced' );
		remove_meta_box( 'sm-song-meta', 'comment', 'advanced' );

		// Check that we have removed the meta boxes only from songs and comment
		$this->assertFalse( $wp_meta_boxes[ $this->post_type ]['advanced']['default']['sm-song-meta'] );
		$this->assertFalse( $wp_meta_boxes['comment']['advanced']['default']['sm-song-meta'] );

		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes['post']['advanced']['default'] );
		$this->assertArrayHasKey( 'sm-song-meta', $wp_meta_boxes['attachment']['advanced']['default'] );

		// Remove the meta box from the other screens.
		remove_meta_box( 'sm-song-meta', array( 'post', 'attachment' ), 'advanced' );

		$this->assertFalse( $wp_meta_boxes['post']['advanced']['default']['sm-song-meta'] );
		$this->assertFalse( $wp_meta_boxes['attachment']['advanced']['default']['sm-song-meta'] );
	}
}
