<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alus.io/wollow
 * @since      1.0.1
 *
 * @package    Wollow
 * @subpackage Wollow/admin
 */

/**
 * The admin-specific functionality of the plugin.e
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wollow
 * @subpackage Wollow/admin
 * @author     Alusio <https://alus.io/>
 */
class Wollow_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	public $numbers = [2, 3, 4, 5, 6, 7];

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wollow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wollow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$valid_page = ["wollow"];
		$page = (isset($_REQUEST['page'])) ? isset($_REQUEST['page']) : "";
		$name_styles = ['bootstrap-min', 'styles', 'iziToast-min'];
		$styles = ['bootstrap.min.css', 'styles.css', 'iziToast.min.css'];
		if (in_array($page, $valid_page)) {
			foreach ($styles as $key => $style) {
				wp_enqueue_style($this->plugin_name . "-$name_styles[$key]", WOLLOW_URL . "admin/css/$style", array(), $this->version, 'all');
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wollow_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wollow_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$valid_page = ["wollow"];

		$page = (isset($_REQUEST['page'])) ? isset($_REQUEST['page']) : "";
		$namescripts = ['tinymce-min', 'bootstrap-min', 'main', 'main', 'iziToast-min'];
		$scripts = ['tinymce.min.js', 'bootstrap.min.js', 'main.js', 'main.js', 'iziToast.min.js'];
		if (in_array($page, $valid_page)) {
			wp_enqueue_script('jquery-ui-tabs');
			foreach ($scripts as $key => $script) {
				wp_enqueue_script($this->plugin_name . "-$namescripts[$key]", WOLLOW_URL . "admin/js/$script", array('jquery'), $this->version, 'all');
			}
		}
	}

	public function register_wollow_submenu()
	{

		$wollow = 'Wollow';

		add_submenu_page('woocommerce', $wollow, $wollow, 'manage_options', 'wollow', [$this, 'wollow_pro_submenu']);
	}

	public function wollow_pro_submenu()
	{
		$data = "<p>Hi {customer_name},<br>thanks for adding {product_name} on your cart.<br>Please let me know if you have any questions about the {order_details}.</p>";

		$data = add_option('wollow1', $data);

		$result = (get_option('wollow1') != '') ? get_option('wollow1') : $data;

		require_once 'partials/submenu/wollow-free.php';
	}

	public function register_general_settings()
	{
		register_setting('submenuWollow', 'wollow1');
	}
}
