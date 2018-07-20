<?php

/**
 * Insert post
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Publications
 * @subpackage	Publications/admin
 */


/**
 * Insert post
 *
 * @package		Publications
 * @subpackage	Publications/admin
 * @author		Jérémy Levron	<jeremylevron@19h47.fr>
 */
class Publications_Admin_Insert_Post {

	/**
	 * The ID of this plugin.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		string			$plugin_name		The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		string			$version			The current version of this plugin.
	 */
	private $version;


	/**
	 * Publications
	 *
	 * @since	1.0.0
	 * @access	private
	 */
	private $publications;


	/**
	 * Config
	 *
	 * @since		1.0.0
	 * @access		protected
	 */
	protected $config;


	/**
	 * Connection
	 *
	 * @since		1.0.0
	 * @access		protected
	 */
	protected $connection;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	string			$plugin_name		The name of this plugin.
	 * @param	string			$version			The version of this plugin.
	 * @param	string			$config
	 */
	public function __construct( $plugin_name, $version, $config ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->config = $config;

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-publications-admin-connection.php';

		$this->connection = new Publications_Admin_Connection(
			$this->plugin_name,
			$this->version
		);
	}


	/**
	 * Insert post
	 *
	 * @param $publications
	 */
	function insert_post() {
		$this->publications = $this->connection::connection( $this->config );

		foreach ( $this->publications as $publication ) {

			$post_exist = get_posts(
				array(
					'post_type' 	=> 'publication',
					'post_status' 	=> 'any',
					'meta_key' 		=> '_publication_id',
					'meta_value' 	=> (int) $publication->id,
				)
			);

			if ( $post_exist ) continue; // Do Nothing

			// Post content
			$post_content = $this->text( $publication->caption->text );
			$post_content = $this->follow( $post_content );
			$post_title = $this->title( $publication->caption->text );

			// Tag
			foreach ( $publication->tags as $tag ) {
				$tagFindPattern = "/#{$tag}/";
				$tagUrl = "https://www.instagram.com/explore/tags/{$tag}";
				$tagReplace = "<a href=\"{$tagUrl}\" target=\"_blank\">#{$tag}</a>";
				$post_content = preg_replace( $tagFindPattern, $tagReplace, $post_content );
			}

			// Post date
			$post_date = date_i18n(
				'Y-m-d H:i:s',
				(int) $publication->created_time
			);


			// postarr
			$postarr = array(
				'post_author'		=> 1,
				'post_content'		=> $post_content,
				'post_date'			=> $post_date,
				'post_date_gmt'		=> $post_date,
				'post_modified'		=> $post_date,
				'post_modified_gmt'	=> $post_date,
				'post_title'		=> $post_title,
				'post_type'			=> 'publication',
			);
			$post_id = wp_insert_post( $postarr, true );


			// Tags
			foreach ( $this->tags( $publication ) as $tag ) {
				wp_set_object_terms( $post_id, $tag, 'tag', true );
			}

			$this->insert_image_media( $publication, $post_id );
			
			update_post_meta( $post_id, '_publication_id', (int) $publication->id );
			update_post_meta( $post_id, '_publication_url', $publication->link );
		}
	}


	/**
	 * Text
	 *
	 * @param	str				$post_content
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function text( $post_content ) {

		// Convert url to HTML link
		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
		$link_replace = '<a href="${0}" target="_blank">${0}</a>';

		return preg_replace( $link_pattern, $link_replace, $post_content );
	}


	/**
	 * Follow
	 *
	 * @param	str				$post_content
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function follow( $post_content ) {

		// Convert @ to follow
		$follow_pattern = '/(@([_a-z0-9\-]+))/i';
		$follow_replace = '<a href="https://www.instagram.com/19h47/${0}" target="_blank">${0}</a>';

		return preg_replace( $follow_pattern, $follow_replace, $post_content );
	}


	/**
	 * Post title
	 *
	 * @param	str $post_content
	 * @author	Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function title( $post_content ) {

		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
		$post_title = preg_replace( $link_pattern, '', $post_content );

		if ( strlen( $post_title ) >= 60 ) {
			substr( $post_title, 0, 60 ) . '...';
		}

		return $post_title;
	}


	/**
	 * Tags
	 *
	 * Construct an array of tag
	 *
	 * @param	obj				$publication Instagram's post
	 * @return	arr				$tags
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function tags( $publication ) {

		$tags = array();

		if ( ! isset( $publication->tags ) ) {
			return;
		}

		foreach ( $publication->tags as $tag ) {
			array_push( $tags, $tag );
		}

		return $tags;
	}


	/**
	 * Insert media
	 *
	 * @param 	object			$publication Instagram's post object
	 * @param 	int				$post_id
	 * @author Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function insert_image_media( $publication, $post_id ) {

		if ( ! isset( $publication->images ) ) {
			return;
		}

		if ( $publication->type === 'video' ) return;

		if ( $publication->type === 'carousel' ) return;

		$thumbnail_id = insert_attachment_from_url( 
			$publication->images->standard_resolution->url, 
			$post_id 
		);

		set_post_thumbnail( $post_id, $thumbnail_id );
	}
}
