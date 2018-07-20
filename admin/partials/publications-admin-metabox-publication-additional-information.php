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

$html = '';
$html .= '<p>' . get_post_meta( $post->ID, '_publication_id', true ) . '</p>';
$html .= '<p><a';
$html .= ' class="button-link"';
$html .= ' href="' . get_post_meta( $post->ID, '_publication_url', true ) . '"';
$html .= ' target="_blank">Instagram\'s post URL</a></p>';
// echo '<p>' . get_the_ID() . '<p>';

echo $html;
