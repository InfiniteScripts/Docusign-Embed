<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.infinitescripts.com
 * @since      1.0.0
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/admin
 * @author     Kevin Greene <kevin@infinitescripts.com>
 */
class docusign_embed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $docusign_embed   The ID of this plugin.
	 */
	private $docusign_embed = "Docusign Embed";

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version = "1.0.0";

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $docusign_embed       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $docusign_embed, $version ) {

		$this->docusign_embed = $docusign_embed;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function docusign_embed_admin_enqueue_styles() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in docusign_embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The docusign_embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->docusign_embed, plugin_dir_url( __FILE__ ) . 'css/docusign-embed-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function docusign_embed_admin_enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in docusign_embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The docusign_embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media(); 
		wp_enqueue_script( $this->docusign_embed, plugin_dir_url( __FILE__ ) . 'js/docusign-embed-admin.js', array( 'jquery' ), $this->version, false );

	}
}
