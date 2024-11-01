<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alus.io/wollow
 * @since      1.0.1
 *
 * @package    Wollow
 * @subpackage Wollow/includes
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
 * @since      1.0.1
 * @package    Wollow
 * @subpackage Wollow/includes
 * @author     Alusio <https://alus.io/>
 */
class Wollow
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      Wollow_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.1
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
	 * @since    1.0.1
	 */
	public function __construct()
	{
		if (defined('WOLLOW_VERSION')) {
			$this->version = WOLLOW_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'wollow';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_orders_hooks();
		$this->define_order_filter_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wollow_Loader. Orchestrates the hooks of the plugin.
	 * - Wollow_i18n. Defines internationalization functionality.
	 * - Wollow_Admin. Defines all hooks for the admin area.
	 * - Wollow_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wollow-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wollow-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wollow-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wollow-orders.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wollow-order-filter.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wollow-public.php';

		$this->loader = new Wollow_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wollow_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Wollow_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Wollow_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		// submenu wollow pro
		$this->loader->add_action('admin_menu', $plugin_admin, 'register_wollow_submenu');

		$this->loader->add_action('admin_init', $plugin_admin, 'register_general_settings');

		// $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'dropdown_product', 50);

		// $this->loader->add_action('posts_where', $plugin_admin, 'filter_product');

		// $this->loader->add_action('restrict_manage_posts', $plugin_admin, 'dropdown_category', 50);

		// $this->loader->add_action('posts_where', $plugin_admin, 'filter_category');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_orders_hooks()
	{

		$plugin_admin = new Wollow_Order($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_filter('manage_edit-shop_order_columns', $plugin_admin, 'custom_shop_order_column');

		$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'custom_orders_list_column_content', 20, 2);

		// add export
		$this->loader->add_action('manage_posts_extra_tablenav', $plugin_admin, 'admin_order_list_top_bar_button');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_order_filter_hooks()
	{

		$plugin_admin = new Wollow_Order_Filter($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('restrict_manage_posts', $plugin_admin, 'dropdown_product', 50);
		$this->loader->add_action('posts_where', $plugin_admin, 'filter_product');
		$this->loader->add_action('restrict_manage_posts', $plugin_admin, 'dropdown_category', 50);
		$this->loader->add_action('posts_where', $plugin_admin, 'filter_category');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Wollow_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.1
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.1
	 * @return    Wollow_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
