<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.infinitescripts.com/docusign-embed
 * @since      1.0.0
 *
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    docusign_embed
 * @subpackage docusign_embed/includes
 * @author     Kevin Greene <kevin@infinitescripts.com>
 */
class docusign_embed_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;

		//We need to remove the database table we added
		$table_name = $wpdb->prefix . 'docusign_embed';
    	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

	}

}
