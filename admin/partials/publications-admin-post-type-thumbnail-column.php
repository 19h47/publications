<?php

/**
 *
 * Provide the view for the thumbnail column
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Publications
 * @subpackage	Publications/admin/partials
 *
 */
echo '<a href="' . get_edit_post_link( $post_id ) . '">';
if ( $post_thumbnail ) {
	echo $post_thumbnail;
}
echo '</a>';