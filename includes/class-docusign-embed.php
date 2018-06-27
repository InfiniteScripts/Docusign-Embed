<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.infinitescripts.com/docusign-embed
 * @since      1.0.0
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
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
 * @since      1.0.0
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
 * @author     Kevin Greene <kevin@infinitescipts.com
 */

/**
* This plugin requires WP_List_Table. This ensures it is included.
*/

if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class docusign_embed{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      docusign_embed_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $docusign_embed    The string used to uniquely identify this plugin.
	 */
	protected $docusign_embed;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->docusign_embed = 'docusign-embed';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - docusign_embed_Loader. Orchestrates the hooks of the plugin.
	 * - docusign_embed_i18n. Defines internationalization functionality.
	 * - docusign_embed_Admin. Defines all hooks for the admin area.
	 * - docusign_embed_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-docusign-embed-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-docusign-embed-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-docusign-embed-admin.php';

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/docusign-embed-admin-display.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-docusign-embed-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/docusign-embed-public-display.php';

    	require_once plugin_dir_path( dirname( __FILE__ ) ) . 'docusign-php-client/autoload.php';

		/**
		 * The class responsible for rendering admin-style tables
		 */

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-docusign-embed-table.php';

		/**
		 * The class responsible for rendering admin-style tables
		 */

		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-docusign-embed-export-csv.php';

		$this->loader = new docusign_embed_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the docusign_embed_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new docusign_embed_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new docusign_embed_Admin( $this->get_docusign_embed(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'docusign_embed_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'docusign_embed_admin_enqueue_scripts' );
		$this->loader->add_action( 'wp_login', $plugin_admin, 'docusign_embed_admin', 10, 2);
		$this->loader->add_action( 'wp_ajax_docusign_embed_export_button', $plugin_admin, 'docusign_embed_export_button');
		$this->loader->add_action( 'wp_ajax_docusign_embed_delete_file', $plugin_admin, 'docusign_embed_delete_file');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new docusign_embed_Public( $this->get_docusign_embed(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'docusign_embed_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'docusign_embed_public_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_public, 'docusign_embed_reports');
		$this->loader->add_action( 'admin_init', $plugin_public, 'docusign_embed_settings_init' );

    $this->loader->add_action( "wp_ajax_hit_docusign_embed_api", $plugin_public, "hit_docusign_embed_api" );
    $this->loader->add_action( "wp_ajax_nopriv_hit_docusign_embed_api", $plugin_public, "hit_docusign_embed_api" );


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_docusign_embed() {
		return $this->docusign_embed;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    docusign_embed_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
