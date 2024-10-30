<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://microlink.io/
 * @since      1.0.0
 *
 * @package    Microlink
 * @subpackage Microlink/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Microlink
 * @subpackage Microlink/includes
 * @author     Jon Torrado <jontorrado@gmail.com>
 */
class Microlink_Deactivator {

	/**
     * Deactivation stuff.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        unregister_setting( 'microlink-settings', 'microlink_api_key' );
	}

}
