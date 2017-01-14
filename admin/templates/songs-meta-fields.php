<?php
/**
 * Created by PhpStorm.
 * User: vishalkakadiya
 * Date: 14/01/17
 * Time: 3:49 PM
 */

/**
 * Template contains meta fields for song post type.
 *
 * Template variables and inclusion available at admin/class-songs-mania-admin.php
 *
 * @since 1.0.0
 *
 * @package    Songs_Mania
 * @subpackage Songs_Mania/admin/templates
 */

?>

<table>
    <tr>
        <td>
            <label class="sm-meta-label" for="sm-singer"><?php esc_html_e( 'Singer : ', 'songs-mania' );?></label>
        </td>
        <td>
            <input type="text" id="sm-singer" name="sm_song_singer" value="<?php echo esc_attr( $singer );?>" class="sm-meta-field" />
        </td>
        </tr>
        <tr>
            <td>
                <label class="sm-meta-label" for="sm-singer-email"><?php esc_html_e( 'Singer email : ', 'songs-mania' );?></label>
            </td>
            <td>
                <input type="email" id="sm-singer-email" name="sm_song_singer_email" value="<?php echo esc_attr( $singer_email );?>" class="sm-meta-field" />
            </td>
        </tr>
        <tr>
            <td>
                <label class="sm-meta-label" for="sm-song-likes"><?php esc_html_e( 'Song Likes : ', 'songs-mania' );?></label>
            </td>
            <td>
                <input type="number" id="sm-song-likes" name="sm_song_likes" value="<?php echo esc_attr( $likes );?>" class="sm-meta-field" />
            </td>
        </tr>
        <tr>
            <td>
                <label class="sm-meta-label" for="sm-song-viewer"><?php esc_html_e( 'Song Viewer', 'songs-mania' );?></label>
            </td>
            <td>
                <input type="number" id="sm-song-viewer" name="sm_song_viewer" value="<?php echo esc_attr( $viewers );?>" class="sm-meta-field" />
            </td>
        </tr>
</table>