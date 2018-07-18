<?php

/**
 *
 * Provide the view for the admin notices
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Publications
 * @subpackage	Publications/admin/partials
 *
 */

echo '<div class="error notice">';
echo "<p>{$user->error_message}</p>";
echo '</div>';