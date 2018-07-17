<?php

/**
 * Insert post
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Images
 * @subpackage	Images/admin
 */


/**
 * Insert post
 *
 * @package		Images
 * @subpackage	Images/admin
 * @author		Jérémy Levron	<jeremylevron@19h47.fr>
 */
class Images_Admin_Insert_Post {

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
	 * Images
	 *
	 * @since	1.0.0
	 * @access	private
	 */
	private $images;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	string			$plugin_name		The name of this plugin.
	 * @param	string			$version			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $images ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->images = $images;

		add_action( 'admin_init', array( $this, 'insert_post' ) );
	}


	/**
	 * Insert post
	 *
	 * @param $images
	 */
	function insert_post() {

		if ( ! isset( $this->images->data ) ) return false;

		foreach ( $this->images->data as $data ) {

			$post_exist = get_posts(
				array(
					'post_type' 	=> 'image',
					'post_status' 	=> 'any',
					'meta_key' 		=> '_image_id',
					'meta_value' 	=> (int) $data->id,
				)
			);

			if ( $post_exist ) continue; // Do Nothing

			// return false;

			$image_text = $this->text( $data->caption->text );
			$image_text = $this->follow( $image_text );
			$post_title = $this->title( $data->caption->text );

			foreach ( $data->tags as $tag ) {
				$tagFindPattern = "/#{$tag}/";
				$tagUrl = "https://www.instagram.com/explore/tags/{$tag}";
				$tagReplace = "<a href=\"{$tagUrl}\" target=\"_blank\">#{$tag}</a>";
				$image_text = preg_replace( $tagFindPattern, $tagReplace, $image_text );
			}


			$date = date_i18n(
				'Y-m-d H:i:s',
				(int) $data->created_time
			);


			// postarr
			$postarr = array(
				'post_author'		=> 1,
				'post_content'		=> $image_text,
				'post_date'			=> $date,
				'post_date_gmt'		=> $date,
				'post_modified'		=> $date,
				'post_modified_gmt'	=> $date,
				'post_title'		=> $post_title,
				'post_type'			=> 'image',
			);
			$post_id = wp_insert_post( $postarr, true );


			// Tags
			foreach ( $this->tags( $data ) as $tag ) {
				wp_set_object_terms( $post_id, $tag, 'tag', true );
			}

			$this->insert_image_media( $data, $post_id );

			// Instagram's post Original link
			$image_url = $data->link;

			update_post_meta( $post_id, '_image_id', (int) $data->id );
			update_post_meta( $post_id, '_image_url', $image_url );
		}
	}


	/**
	 * Text
	 *
	 * @param	str				$image_text
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function text( $image_text ) {

		// Convert url to HTML link
		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
		$link_replace = '<a href="${0}" target="_blank">${0}</a>';

		return preg_replace( $link_pattern, $link_replace, $image_text );
	}


	/**
	 * Follow
	 *
	 * @param	str				$image_text
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function follow( $image_text ) {

		// Convert @ to follow
		$follow_pattern = '/(@([_a-z0-9\-]+))/i';
		$follow_replace = '<a href="https://www.instagram.com/19h47/${0}" target="_blank">${0}</a>';

		return preg_replace( $follow_pattern, $follow_replace, $image_text );
	}


	/**
	 * Title
	 *
	 * @param	str $image_text
	 * @author	Jérémy Levron <jeremylevron@19h47.fr>
	 */
	function title( $image_text ) {

		$link_pattern = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/[^\s\…\.]*)?/";
		$post_title = preg_replace( $link_pattern, '', $image_text );

			if ( strlen( $post_title ) >= 60 ) {
			substr( $post_title, 0, 60 ) . '...';
		}

		return $post_title;
	}


	/**
	 * Hashtags
	 *
	 * @param	obj				$data Instagram's post
	 * @return	arr				$tags
	 * @author	Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function tags( $data ) {

		$tags = array();

		if ( ! isset( $data->tags ) ) {
			return;
		}

		foreach ( $data->tags as $tag ) {
			array_push( $tags, $tag );
		}

		return $tags;
	}


	/**
	 * Insert media
	 *
	 * @param 	object			$data Instagram's post object
	 * @param 	int				$post_id
	 * @author Jérémy Levron	<jeremylevron@19h47.fr>
	 */
	function insert_image_media( $data, $post_id ) {

		if ( ! isset( $data->images ) ) {
			return;
		}

		if ( $data->type === 'video' ) return;

		$i = 0;
		foreach ( $data->images as $media ) {

			$thumbnail_id = insert_attachment_from_url( 
				$data->images->standard_resolution->url, 
				$post_id 
			);

			if ( $i === 0 ) {
				set_post_thumbnail( $post_id, $thumbnail_id );
			}
			$i++;
		}
	}
}
