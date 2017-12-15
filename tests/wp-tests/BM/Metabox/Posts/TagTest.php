<?php

/**
 * Test BM_Metabox_Posts_Tag class.
 */
class TagTest extends \WP_UnitTestCase {

	/**
	 * @var \BM_Metabox_Posts_Tag
	 */
	protected $tag_metabox;

	public function setUp() {
		parent::setUp();

		$this->tag_metabox = new \BM_Metabox_Posts_Tag();
	}

	/**
	 * Test basic case of moving categories.
	 */
	public function test_move_posts_from_one_tag_to_another() {
		// Create two tags.
		$tag1 = $this->factory->tag->create( array( 'name' => 'tag1' ) );
		$tag2 = $this->factory->tag->create( array( 'name' => 'tag2' ) );

		// Create one post in each tag.
		$post1 = $this->factory->post->create( array( 'post_title' => 'post1' ) );
		wp_set_post_tags( $post1, 'tag1' );

		$post2 = $this->factory->post->create( array( 'post_title' => 'post2' ) );
		wp_set_post_tags( $post2, 'tag2' );

		// Assert that each tag has one post.
		$posts_in_tag1 = $this->get_posts_by_tag( $tag1 );
		$posts_in_tag2 = $this->get_posts_by_tag( $tag2 );

		$this->assertEquals( count( $posts_in_tag1 ), 1 );
		$this->assertEquals( count( $posts_in_tag2 ), 1 );

		// call our method.
		$options = array(
			'old_tag'   => $tag1,
			'new_tag'   => $tag2,
			'overwrite' => true,
		);
		$this->tag_metabox->move( $options );

		// Assert that tag 1 has no posts.
		$posts_in_tag1 = $this->get_posts_by_tag( $tag1 );
		$this->assertEquals( count( $posts_in_tag1 ), 0 );

		// Assert that tag 2 has two posts.
		$posts_in_tag2 = $this->get_posts_by_tag( $tag2 );
		$this->assertEquals( count( $posts_in_tag2 ), 2 );
	}

	/**
	 * Test moving posts from one tag to another with overwrite.
	 */
	public function test_move_posts_from_one_tag_to_another_with_overwrite() {

	}

	/**
	 * Test Moving tag without overwrite.
	 */
	public function test_move_posts_from_one_tag_to_another_without_overwrite() {

	}

	/**
	 * Helper method to get posts by tag.
	 *
	 * @param string $tag Tag name.
	 *
	 * @return array Posts that belong to that tag.
	 */
	protected function get_posts_by_tag( $tag ) {
		$args = array(
			'tag__in' => array( $tag ),
			'post_type'    => 'post',
			'nopaging'     => 'true',
			'post_status'  => 'publish',
		);

		$wp_query = new \WP_Query();

		return $wp_query->query( $args );
	}
}