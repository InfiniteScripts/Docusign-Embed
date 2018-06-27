<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.infinitescripts.com/docusign-embed
 * @since      1.0.0
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
 * @author     Kevin Greene <kevin@infinitescripts.com>
 */
class docusign_embed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;

		/* Lets add the table to store login data */
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . 'docusign_embed';

		$sql = "CREATE TABLE $table_name (

		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
