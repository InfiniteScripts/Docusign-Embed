<?php

/**
 * @link       http://www.infinitescripts.com/docusign-embed
 * @since      1.0.0
 *
 * @package    docusign_embed
 *
 *
 * Plugin Name:       Docusign Embed
 * Plugin URI:        http://www.infinitescripts.com/docusign-embed/
 * Description:       Allows for the embed and signing of documents using docusign api
 * Version:           1.0.0
 * Author:            Kevin Greene
 * Author URI:        http://www.infinitescripts.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       docusign-embed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-docusign-embed-activator.php
 */
function activate_docusign_embed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-docusign-embed-activator.php';
	docusign_embed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-docusign-embed-deactivator.php
 */
function deactivate_docusign_embed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-docusign-embed-deactivator.php';
	docusign_embed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_docusign_embed' );
register_deactivation_hook( __FILE__, 'deactivate_docusign_embed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-docusign-embed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_docusign_embed() {

	$plugin = new docusign_embed();
	$plugin->run();

}
run_docusign_embed();
