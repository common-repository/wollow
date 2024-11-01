<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://alus.io/wollow
 * @since             1.0.1
 * @package           Wollow
 *
 * @wordpress-plugin
 * Plugin Name:       Wollow
 * Plugin URI:        https://alus.io/wollow
 * Description:       Grow businnes with integration woocommerce with whatsapp.
 * Version:           1.0.1
 * Author:            Alusio
 * Author URI:        https://alus.io/wollow
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wollow
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define("WOLLOW_URL", plugin_dir_url(__FILE__));
define("WOLLOW_PATH", plugin_dir_path(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WOLLOW_VERSION', '1.0.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wollow-activator.php
 */
function activate_wollow()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-wollow-activator.php';
	Wollow_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wollow-deactivator.php
 */
function deactivate_wollow()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-wollow-deactivator.php';
	Wollow_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_wollow');
register_deactivation_hook(__FILE__, 'deactivate_wollow');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-wollow.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function run_wollow()
{

	$plugin = new Wollow();
	$plugin->run();
}
run_wollow();
