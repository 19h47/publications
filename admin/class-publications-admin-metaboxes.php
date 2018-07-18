<?php

/**
 * The metabox-specific functionality of the plugin.
 *
 * @link			http://19h47.fr
 * @since			1.0.0
 *
 * @package			Images
 * @subpackage		Images/admin
 */


/**
 * The metabox-specific functionality of the plugin.
 *
 * @package			Images
 * @subpackage		Images/admin
 * @author			Jérémy Levron	<jeremylevron@19h47.fr>
 */
class Publications_Admin_Metaboxes {

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
	 * Registers metaboxes with WordPress
	 *
	 * @since		1.0.0
	 * @access		public
	 */
	public function add_metaboxes() {

		add_meta_box(
			'image_additional_information',
			apply_filters(
				$this->plugin_name . '-metabox-publication-additional-information',
				esc_html__( 'Additional Information', 'publications' )
			),
			array( $this, 'metabox' ),
			'publication',
			'side',
			'default',
			array(
				'file' => 'publication-additional-information'
			)
		);
	}


	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since		1.0.0
	 * @access		public
	 * @return		void
	 */
	public function metabox( $post, $params ) {

		if ( ! is_admin() ) {
			return;
		}

		if ( $post->post_type !== 'publication' ) {
			return;
		}


		include plugin_dir_path( __FILE__ ) . 'partials/publications-admin-metabox-' . $params['args']['file'] . '.php';
	}
}
