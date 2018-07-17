<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link		http://19h47.fr
 * @since		1.0.0
 *
 * @package		Images
 * @subpackage	Images/includes
 */


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since		1.0.0
 * @package		Images
 * @subpackage	Images/includes
 * @author		Jérémy Levron <jeremylevron@19h47.fr>
 */
class Images {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		Images_Loader		$loader		Maintains and registers all hooks for the plugin.
	 */
	protected $loader;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		string		$plugin_name		The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;


	/**
	 * The current version of the plugin.
	 *
	 * @since	1.0.0
	 * @access	protected
	 * @var		string		$version		The current version of the plugin.
	 */
	protected $version;


	/**
	 * Config
	 *
	 * @since	1.0.0
	 * @access	protected
	 */
	protected $config;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since	1.0.0
	 */
	public function __construct() {
		if ( defined( 'IMAGES' ) ) {
			$this->version = IMAGES;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'images';
		$this->config = json_decode(
			file_get_contents( __DIR__ . '/../config.json' ),
			true
		);

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

		$this->define_metabox_hooks();
		$this->define_post_type_hooks();
		$this->define_taxonomy_hooks();
		$this->define_connection();
		$this->define_insert_post();
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Images_Loader. Orchestrates the hooks of the plugin.
	 * - Images_i18n. Defines internationalization functionality.
	 * - Images_Admin. Defines all hooks for the admin area.
	 * - Images_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since		1.0.0
	 * @access	private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for all global functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/images-global-functions.php';


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-images-loader.php';


		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-images-i18n.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin.php';


		/**
		 * The class responsible for defining all actions relating to metaboxes.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin-metaboxes.php';


		/**
		 * The class responsible for defining all actions relating to post type.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin-post-type.php';


		/**
		 * The class responsible for defining all actions relating to taxonomy.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin-taxonomy.php';


		/**
		 * The class responsible for defining connection
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin-connection.php';


		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-images-admin-insert-post.php';


		$this->loader = new Images_Loader();
	}


	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Images_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since		1.0.0
	 * @access	private
	 */
	private function set_locale() {

		$plugin_i18n = new Images_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since		1.0.0
	 * @access	private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Images_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}


	/**
	 * Register all of the hooks related to post type
	 *
	 * @since		1.0.0
	 * @access	private
	 */
	private function define_post_type_hooks() {

		$plugin_post_type = new Images_Admin_Post_Type( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'init', $plugin_post_type, 'register_post_type' );
			$this->loader->add_action( 'admin_head', $plugin_post_type, 'css' );

			$this->loader->add_filter( 'dashboard_glance_items', $plugin_post_type, 'at_a_glance' );
			$this->loader->add_filter(
				'manage_image_posts_columns',
				$plugin_post_type,
				'add_custom_columns'
			);

			$this->loader->add_action(
				'manage_image_posts_custom_column',
				$plugin_post_type,
				'render_custom_columns',
				10,
				2
			);
	}


	/**
	 * Register all of the hooks related to taxonomy
	 *
	 * @since		1.0.0
	 * @access	private
	 */
	private function define_taxonomy_hooks() {

		$plugin_taxonomy = new Images_Admin_Taxonomy( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_taxonomy, 'register_taxonomy', 0 );
	}


	/**
	 * @since		1.0.0
	 * @access	private
	 */
	private function define_connection() {

		$plugin_connection = new Images_Admin_Connection(
			$this->get_plugin_name(),
			$this->get_version()
		);

		$this->images = $plugin_connection::connection( $this->config );
	}


	/**
	 * @since  1.0.0
	 * @access private
	 */
	private function define_insert_post() {

		$plugin_insert_post = new Images_Admin_Insert_Post(
			$this->get_plugin_name(),
			$this->get_version(),
			$this->images
		);

		// $this->loader->add_action(
		// 	'import_instagrams_post_as_posts',
		// 	$plugin_insert_post,
		// 	'insert_post'
		// );
	}


	/**
	 * Register all of the hooks related to metaboxes
	 *
	 * @since 		1.0.0
	 * @access 		private
	 */
	private function define_metabox_hooks() {

		$plugin_metaboxes = new Images_Admin_Metaboxes(
			$this->get_plugin_name(),
			$this->get_version()
		);

		$this->loader->add_action( 'add_meta_boxes', $plugin_metaboxes, 'add_metaboxes' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since		1.0.0
	 */
	public function run() {
		$this->loader->run();
	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since		1.0.0
	 * @return		string		The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since		1.0.0
	 * @return		Images_Loader		Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}


	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since		1.0.0
	 * @return		string		The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
