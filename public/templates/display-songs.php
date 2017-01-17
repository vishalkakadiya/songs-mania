<?php
/**
 * Created by PhpStorm.
 * User: vishalkakadiya
 * Date: 14/01/17
 * Time: 11:24 PM
 */

/**
 * Template for the displaying post list.
 *
 * Template variables and inclusion available at plugin/class-songs-mania-public.php
 *
 * @link       https://github.com/vishalkakadiya
 * @since      1.0.0
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/public/templates
 */

?>

<article id="song-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h2 class="entry-title">
			<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
				<?php echo get_the_title();?>
			</a>
		</h2>
	</header><!-- .entry-header -->

	<div class="entry-content clearfix">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			$post_id = get_the_ID();
			$singer = get_post_meta( $post_id, 'sm_song_singer', true );
			$singer_email = get_post_meta( $post_id, 'sm_song_singer_email', true );
			$likes = get_post_meta( $post_id, 'sm_song_likes', true );
			$viewers = get_post_meta( $post_id, 'sm_song_viewer', true );
		?>
		<?php if ( isset( $singer ) ) : ?>
			<p>
				<span><?php esc_html_e( 'Singer : ', 'songs-mania' ); ?></span>
				<span><?php echo esc_html( $singer ); ?></span>
			</p>
		<?php endif; ?>
		<?php if ( isset( $singer_email ) ) : ?>
			<p>
				<span><?php esc_html_e( 'Email : ', 'songs-mania' ); ?></span>
				<span><?php echo esc_html( $singer_email ); ?></span>
			</p>
		<?php endif; ?>
		<?php if ( isset( $likes ) ) : ?>
			<p>
				<span><?php esc_html_e( 'Likes : ', 'songs-mania' ); ?></span>
				<span class="sm-likes-<?php echo esc_attr( $post_id ); ?>"><?php echo esc_html( $likes ); ?></span>
				<button data-id="<?php echo esc_attr( $post_id ); ?>" class="sm-like">
					<?php esc_html_e( 'Like Now!', 'songs-mania' ); ?>
				</button>
			</p>
		<?php endif; ?>
		<?php if ( isset( $viewers ) ) : ?>
			<p>
				<span><?php esc_html_e( 'Viewers : ', 'songs-mania' ); ?></span>
				<span><?php echo esc_html( $viewers ); ?></span>
			</p>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
