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
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wollow
 * @subpackage Wollow/admin
 * @author     Alusio <https://alus.io/>
 */
class Wollow_Order_Filter
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
		$this->id_product = 'wfobpp_by_product';
		$this->id_category = 'wfobpp_by_category';
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

	}

	public function dropdown_category()
	{
		$screen = get_current_screen();
		if ($screen->id != 'edit-shop_order') return;

		$fields = $this->dropdown_field_category();

		?>
		<select class="wfobpp-select2" name="<?php esc_attr_e($this->id_category); ?>" id="<?php esc_attr_e($this->id_category); ?>">
			<?php
			$current_v = sanitize_text_field($_GET[$this->id_category]);
			foreach ($fields as $key => $title) {
				printf(
					'<option value="%s"%s>%s</option>',
					$key,
					$key == $current_v ? ' selected="selected"' : '',
					$title
				);
			}
			?>
		</select>
		<?php
	}

	public function dropdown_field_category()
	{

		$terms = get_terms(array('taxonomy' => 'product_cat', 'fields' => 'id=>name'));

		$fields = array();
		$fields[0] = esc_html__('All Categories', 'woocommerce-filter-orders-by-product');

		foreach ($terms as $id => $name) {
			$fields[$id] = $name;
		}

		return $fields;
	}

	public function filter_category($where)
	{
		if (is_search()) {
			if (isset($_GET[$this->id_category]) && !empty($_GET[$this->id_category])) {
				$product = intval($_GET[$this->id_category]);

				$where .= " AND $product IN (";
				$where .= $this->query_by_category();
				$where .= ")";
			}
		}
		return $where;
	}

	private function query_by_category()
	{
		global $wpdb;
		$t_term_relationships = $wpdb->term_relationships;

		$query  = "SELECT $t_term_relationships.term_taxonomy_id FROM $t_term_relationships WHERE $t_term_relationships.object_id IN (";
		$query .= $this->query_by_product();
		$query .= ")";

		return $query;
	}
	// end filter category

	// filter product
	public function dropdown_product()
	{
		$screen = get_current_screen();
		if ($screen->id != 'edit-shop_order') return;

		$fields = $this->dropdown_field_product();

		?>
		<select class="wfobpp-select2" name="<?php esc_attr_e($this->id_product); ?>" id="<?php esc_attr_e($this->id_product); ?>">
			<?php
			$current_v = sanitize_text_field($_GET[$this->id_product]);
			foreach ($fields as $key => $title) {
				printf(
					'<option value="%s"%s>%s</option>',
					$key,
					$key == $current_v ? ' selected="selected"' : '',
					$title
				);
			}
			?>
		</select>
		<?php
	}

	public function dropdown_field_product()
	{
		global $wpdb;

		$status = apply_filters('wfobp_product_status', 'publish');
		$sql    = "SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'product'";
		$sql   .= ($status == 'any') ? '' : " AND post_status = '$status'";
		$all_posts = $wpdb->get_results($sql, ARRAY_A);


		$fields    = array();
		$fields[0] = esc_html__('All Products', 'woocommerce-filter-orders-by-product');
		foreach ($all_posts as $all_post) {
			$fields[$all_post['ID']] = $all_post['post_title'];
		}

		return $fields;
	}

	public function filter_product($where)
	{
		if (is_search()) {
			if (isset($_GET[$this->id_product]) && !empty($_GET[$this->id_product])) {
				$product = intval($_GET[$this->id_product]);

				$where .= " AND $product IN (";
				$where .= $this->query_by_product();
				$where .= ")";
			}
		}
		return $where;
	}

	protected function query_by_product()
	{
		global $wpdb;
		$t_posts = $wpdb->posts;
		$t_order_items = $wpdb->prefix . "woocommerce_order_items";
		$t_order_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

		$query  = "SELECT $t_order_itemmeta.meta_value FROM";
		$query .= " $t_order_items LEFT JOIN $t_order_itemmeta";
		$query .= " on $t_order_itemmeta.order_item_id=$t_order_items.order_item_id";

		$query .= " WHERE $t_order_items.order_item_type='line_item'";
		$query .= " AND $t_order_itemmeta.meta_key='_product_id'";
		$query .= " AND $t_posts.ID=$t_order_items.order_id";

		return $query;
	}
	// end filter product
}
