<?php

/**
 * The post type of the plugin.
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Images
 * @subpackage	Images/admin
 */


/**
 * The post type of the plugin.
 *
 * @package		Images
 * @subpackage	Images/admin
 * @author		Jérémy Levron <jeremylevron@19h47.fr>
 */
class Images_Admin_Post_Type {

	/**
	 * The ID of this plugin.
	 *
	 * @since		1.0.0
	 * @access		private
	 * @var			string			$plugin_name		The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since		1.0.0
	 * @access		private
	 * @var			string			$version			The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since		1.0.0
	 * @param		string			$plugin_name		The name of this plugin.
	 * @param		string			$version			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}


	/**
	 * Register Custom Post Type
	 *
	 * @since		1.0.0
	 * @access		public
	 * @uses		register_post_type()
	 */
	public static function register_post_type() {
		$labels = array(
			'name'					=> _x( 'Images', 'Image pluriel', $this->plugin_name ),
			'singular_name'			=> _x( 'Image', 'Image singulier', $this->plugin_name ),
			'menu_name'				=> __( 'Images', $this->plugin_name ),
			'name_admin_bar'		=> __( 'Image', $this->plugin_name ),
			'parent_item_colon'		=> __( '', $this->plugin_name ),
			'all_items'				=> __( 'Toutes les images', $this->plugin_name ),
			'add_new_item'			=> __( 'Ajouter une image', $this->plugin_name ),
			'add_new'				=> __( 'Ajouter', $this->plugin_name ),
			'new_item'				=> __( 'Nouvelle image', $this->plugin_name ),
			'edit_item'				=> __( 'Modifier l\'image', $this->plugin_name ),
			'update_item'			=> __( 'Mettre à jour l\'image', $this->plugin_name ),
			'view_item'				=> __( 'Voir l\'image', $this->plugin_name ),
			'view_items'			=> __( 'Voir les images', $this->plugin_name ),
			'search_items'			=> __( 'Chercher parmi les images', $this->plugin_name ),
			'not_found'				=> __( 'Aucune image trouvée.', $this->plugin_name ),
			'not_found_in_trash'		=> __( 'Aucune image trouvée dans la corbeille.', $this->plugin_name ),
			'featured_image'		=> __( 'Image à la une', $this->plugin_name ),
			'set_featured_image'		=> __( 'Mettre une image à la une', $this->plugin_name ),
			'remove_featured_image' => __( 'Retirer l\'image mise à la une', $this->plugin_name ),
			'use_featured_image'		=> __( 'Mettre une image à la une', $this->plugin_name ),
			'insert_into_item'		=> __( 'Insérer dans l\'image', $this->plugin_name ),
			'uploaded_to_this_item' => __( 'Ajouter à cette image', $this->plugin_name ),
			'items_list'			=> __( 'Liste des images', $this->plugin_name ),
			'items_list_navigation'	=> __( 'Navigation de liste des images', $this->plugin_name ),
			'filter_items_list'		=> __( 'Filtrer la liste des images', $this->plugin_name ),
		);

		$rewrite = array(
			'slug'					=> 'images',
			'with_front'			=> true,
			'pages'					=> true,
			'feeds'					=> true,
		);

		$args = array(
			'label'					=> 'image',
			'description'			=> __( 'Les images', $this->plugin_name ),
			'labels'				=> $labels,
			'supports'				=> array( 'title', 'editor', 'author', 'thumbnail' ),
			'taxonomies'			=> array( 'tag' ),
			'hierarchical'			=> false,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> true,
			'show_in_rest'			=> true,
			'menu_position'			=> 5,
			'menu_icon'				=> 'dashicons-format-image',
			'can_export'			=> true,
			'has_archive'			=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'rewrite'				=> $rewrite,
			'capability_type'		=> 'post',
		);
		register_post_type( 'image', $args );
	}


	/**
	 * css
	 *
	 * @since		1.0.0
	 */
	public function css() {
		?>
		<style>
			#dashboard_right_now .image-count:before { content: "\f128"; }
			.fixed .column-thumbnail {
				width: 62px;
			}
			.column-thumbnail a {
				display: inline-block;
				vertical-align: middle;

				width: 60px;
				height: 60px;
				max-width: 60px;

				border: 1px solid #e5e5e5;
				border: 1px solid rgba( 0, 0, 0, .07 );
			}
			.column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;

				height: auto;
			}
		</style>
	<?php
	}


	/**
	 * Add custom columns
	 *
	 * @since		1.0.0
	 * @param		$columns
	 * @param		$new_columns
	 */
	public function add_custom_columns( $columns ) {
		global $typenow;

		if ( 'image' !== $typenow ) {
			return;
		}

		$new_columns = array();
		$keys = array_keys( $columns );

		foreach( $columns as $key => $value ) {
			if ( $key === 'title' ) {
				$new_columns['thumbnail'] = __( 'Image' );
			}

			$new_columns[$key] = $value;
		}
		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @since		1.0.0
	 * @param		$column_name
	 * @param		$post_id
	 * @return		void
	 */
	public function render_custom_columns( $column_name, $post_id ) {
		global $typenow;

		if ( 'image' !== $typenow ) {
			return;
		}

		switch ( $column_name ) {
			case 'thumbnail' :
				$post_thumbnail = get_the_post_thumbnail(
					$post_id,
					array( 60, 60 ),
					array( 'class' => '' )
				);

				include plugin_dir_path( __FILE__ ) . 'partials/images-admin-post-type-thumbnail-column.php';

				break;
		}
	}


	/**
	 * "At a glance" items (dashboard widget): add the image.
	 *
	 * @since		1.0.0
	 * @param		$items
	 * @return		$items
	 */
	public function at_a_glance( $items ) {
		$post_type = 'image';
		$post_status = 'publish';
		$object = get_post_type_object( $post_type );

		$num_posts = wp_count_posts( $post_type );
		if ( ! $num_posts || ! isset ( $num_posts->{$post_status} ) || 0 === (int) $num_posts->{$post_status} ) {
			return $items;
		}

		$text = sprintf(
			_n( '%1$s %4$s%2$s', '%1$s %4$s%3$s', $num_posts->{$post_status} ),
			number_format_i18n( $num_posts->{$post_status} ),
			strtolower( $object->labels->singular_name ),
			strtolower( $object->labels->name ),
			'pending' === $post_status ? 'Pending ' : ''
		);

		if ( current_user_can( $object->cap->edit_posts ) ) {
			$items[] = sprintf( 
				'<a class="%1$s-count" href="edit.php?post_status=%2$s&post_type=%1$s">%3$s</a>', 
				$post_type, 
				$post_status, 
				$text 
			);
		} else {
			$items[] = sprintf( '<span class="%1$s-count">%s</span>', $text );
		}

		return $items;
	}
}