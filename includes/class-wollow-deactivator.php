<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://alus.io/wollow
 * @since      1.0.1
 *
 * @package    Wollow
 * @subpackage Wollow/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.1
 * @package    Wollow
 * @subpackage Wollow/includes
 * @author     Alusio <https://alus.io/>
 */
class Wollow_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.1
	 */
	public static function deactivate() {
		delete_option( 'token' );
		delete_option( 'integrasi_token' );
	}

}
