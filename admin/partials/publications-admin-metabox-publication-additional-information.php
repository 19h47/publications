<?php

/**
 *
 * Provide the view for a metabox
 *
 * @link			http://19h47.fr
 * @since			1.0.0
 *
 * @package			Publications
 * @subpackage		Publications/admin/partials
 *
 */

echo '<p>' . get_post_meta( $post->ID, '_publication_id', true ) . '</p>';
echo '<p><a';
echo ' class="button-link"';
echo ' href="' . get_post_meta( $post->ID, '_publication_url', true ) . '"';
echo ' target="_blank">Instagram\'s post URL</a></p>';
echo '<p>' . get_the_ID() . '<p>';

$attachments = get_posts(
	array(
		'post_type'			=> 'attachment',
		'posts_per_page'	=> -1,
		'post_parent'		=> $post->ID,
		'exclude'			=> get_post_thumbnail_id( $post->ID )
	)
);

if ( $attachments ) {
	foreach ( $attachments as $attachment ) {
		$class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
		$thumbimg = wp_get_attachment_link( $attachment->ID, array( 266, 266 ), true );
		
		echo "<p>{$thumbimg}</p>";
	}
}
