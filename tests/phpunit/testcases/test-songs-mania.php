<?php
/**
 * Created by PhpStorm.
 * User: vishal
 * Date: 5/1/17
 * Time: 11:52 PM
 */


/**
 * An example test case.
 */
class MyPlugin_Test_Example extends WP_UnitTestCase {

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

	public function test_song_is_available() {
		global $wp_post_types;

		foreach ( $wp_post_types as $post_type ) {
			$post_types[] = $post_type->name;
		}

		$this->assertContains( $this->post_type, $post_types );
	}


	public function test_add_supports_custom() {
		$post_type_object = new WP_Post_Type( $this->post_type, array(
			'supports' => array(
				'title',
				'editor',
				'category',
				'author',
				'thumbnail',
				'excerpt',
				'comments',
			),
		) );

		$post_type_object->add_supports();
		$post_type_supports = get_all_post_type_supports( $this->post_type );

		$post_type_object->remove_supports();
		$post_type_supports_after = get_all_post_type_supports( $this->post_type );

		$this->assertEqualSets( array(
			'title'     => true,
			'editor'    => true,
			'category'  => true,
			'author'    => true,
			'thumbnail' => true,
			'excerpt'   => true,
			'comments'  => true,
		), $post_type_supports );
		$this->assertEqualSets( array(), $post_type_supports_after );
	}


	public function test_adds_query_var_if_public() {
		$this->set_permalink_structure( '/%postname%' );

		/* @var WP $wp */
		global $wp;

		$post_type_object = new WP_Post_Type( $this->post_type, array(
			'public'    => true,
			'rewrite'   => false,
			'query_var' => 'listen',
		) );

		$post_type_object->add_rewrite_rules();
		$in_array = in_array( 'listen', $wp->public_query_vars, true );

		$post_type_object->remove_rewrite_rules();
		$in_array_after = in_array( 'listen', $wp->public_query_vars, true );

		$this->assertTrue( $in_array );
		$this->assertFalse( $in_array_after );
	}


	public function test_register_meta_boxes() {
		$post_type_object = new WP_Post_Type( $this->post_type, array( 'register_meta_box_cb' => '__return_false' ) );

		$post_type_object->register_meta_boxes();
		$has_action = has_action( "add_meta_boxes_$this->post_type", '__return_false' );
		$post_type_object->unregister_meta_boxes();
		$has_action_after = has_action( "add_meta_boxes_$this->post_type", '__return_false' );

		$this->assertSame( 10, $has_action );
		$this->assertFalse( $has_action_after );
	}

	public function test_adds_future_post_hook() {
		$post_type_object = new WP_Post_Type( $this->post_type );
		$post_type_object->add_hooks();
		$has_action = has_action( "future_$this->post_type", '_future_post_hook' );
		$post_type_object->remove_hooks();
		$has_action_after = has_action( "future_$this->post_type", '_future_post_hook' );

		$this->assertSame( 5, $has_action );
		$this->assertFalse( $has_action_after );
	}

	public function test_register_taxonomies() {
		global $wp_post_types;

		$post_type_object = new WP_Post_Type( $this->post_type, array( 'taxonomies' => array( 'category' ) ) );

		$wp_post_types[ $this->post_type ] = $post_type_object;

		$post_type_object->register_taxonomies();
		$taxonomies = get_object_taxonomies( $this->post_type );
		$post_type_object->unregister_taxonomies();
		$taxonomies_after = get_object_taxonomies( $this->post_type );

		unset( $wp_post_types[ $this->post_type ] );

		$this->assertEqualSets( array( 'category' ), $taxonomies );
		$this->assertEqualSets( array(), $taxonomies_after );
	}


//	function test_create_song() {
//		include 'class-test-song-post.php';
//
//		$factory = new WP_UnitTest_Factory_For_Song_Post;
//
//		$post = $factory->create_and_get( array(
//			'post_title' => 'This is song post ?',
//		) );
//
//		echo '\n\n\n dasdsads : <pre>';
//		print_r( $post );
//		echo '</pre>';
//	}

//	public function test_register_meta_boxes() {
//		$post_type        = $this->post_type;
//		$post_type_object = new WP_Post_Type( $post_type, array( 'register_meta_box_cb' => '__return_false' ) );
//
//		$post_type_object->register_meta_boxes();
//		$has_action = has_action( "add_meta_boxes_$post_type", '__return_false' );
//		$post_type_object->unregister_meta_boxes();
//		$has_action_after = has_action( "add_meta_boxes_$post_type", '__return_false' );
//
//		$this->assertSame( 10, $has_action );
//		$this->assertFalse( $has_action_after );
//	}


	/**
	 * An example test.
	 *
	 * We just want to make sure that false is still false.
	 */
	function test_false_is_false() {

		$factory = parent::__get( 'factory' );

//		$factory->post->create( array( 'post_title' => 'New Test Plugin' ) );

		$post = $factory->post->create_and_get(
			array( 'post_title' => 'New Test Plugin' )
		);

	    echo '<pre>';
	    print_r( $post );
	    echo '</pre>';

//		include 'class-test-song-post.php';
//
//		$factory = new WP_UnitTest_Factory_For_Song_Post;
//
//		$post = $factory->create_and_get( array(
//			'post_title' => 'This is song post ?'
//		) );
//		$user = $this->factory->user->create_and_get( array(
//			'user_email' => 'test@testing.com'
//		) );
//
		echo '<pre>';
		print_r( $post );
		echo '</pre>';


//		$post_ids = $this->factory->post->create_many( 10 );
//		$initial_counts = wp_count_posts();
//
//		$key = array_rand( $post_ids );
//		$_post = get_post( $post_ids[$key], ARRAY_A );
//		$_post['post_status'] = 'draft';
//		wp_insert_post( $_post );
//		$post = get_post( $post_ids[$key] );
//		$this->assertEquals( 'draft', $post->post_status );
//		$this->assertNotEquals( 'publish', $post->post_status );
//
//		$after_draft_counts = wp_count_posts();
//		$this->assertEquals( 1, $after_draft_counts->draft );
//		$this->assertEquals( 9, $after_draft_counts->publish );
//		$this->assertNotEquals( $initial_counts->publish, $after_draft_counts->publish );


		$this->assertFalse( false );
	}

}