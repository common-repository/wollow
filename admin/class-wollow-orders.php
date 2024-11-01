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
class Wollow_Order
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

		$shop_order = ["shop_order"];
		$post_type = (isset($_REQUEST['post_type'])) ? isset($_REQUEST['post_type']) : "";
		$styles = ['order.css'];
		if (in_array($post_type, $shop_order)) {
			foreach ($styles as $style) {
				wp_enqueue_style($this->plugin_name . "-$style", WOLLOW_URL . "admin/css/$style", array(), $this->version, 'all');
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

		$shop_order = ["shop_order"];
		$post_type = (isset($_REQUEST['post_type'])) ? isset($_REQUEST['post_type']) : "";
		$scripts = ['order.js'];
		if (in_array($post_type, $shop_order)) {
			foreach ($scripts as $script) {
				wp_enqueue_script($this->plugin_name . "-$script", WOLLOW_URL . "admin/js/$script", array(), $this->version, 'all');
			}
		}
	}

	public function custom_shop_order_column($columns)
	{
		$reordered_columns = array();

		foreach ($columns as $key => $column) {
			$reordered_columns[$key] = $column;
			if ($key ==  'order_status') {
				$reordered_columns['order-whatsapp'] = __('Whatsapp', 'theme_domain');
			}
		}
		return $reordered_columns;
	}

	public function custom_orders_list_column_content($column,  $order_id)
	{
		$order = wc_get_order($order_id);

		$text_wa = Wollow_Order::get_order_woocommerce($order, null);

		$dataCss = "cursor: pointer;background-color: #31D39F;border-color: #31D39F;color : white;fill:white;";

		$numbers = ['satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh'];


		foreach ($numbers as $number) :
			$value = $number . '' . $order_id;
			if (!empty($_POST["wa"]) and $_POST["wa"] == "$value") {
				update_option("wa_$value", $dataCss);
			}
		endforeach;

		$billing_phone = Wollow_Order::phone_validation(true, $order->get_billing_phone());
		$status    = $order->get_status();

		switch ($column) {
			case 'order-whatsapp':
				if ($order->get_billing_phone() == null) {
					echo '-';
				} elseif ($status === 'completed') {
					$this->icon_wa_d_none();
					$this->icon_wa($billing_phone, $text_wa[0], $value, "wa_$numbers[0]$order_id");
				} elseif ($status === 'processing') {
					$this->icon_wa_d_none();
					$this->icon_wa($billing_phone, $text_wa[0], $value, "wa_$numbers[0]$order_id");
				} elseif ($billing_phone != null) {

					$this->icon_wa($billing_phone, $text_wa[0], $value, "wa_$numbers[0]$order_id");
				}
				break;
		}
	}

	public static function phone_validation($first, $billing_phone)
	{
		$nom = trim($billing_phone);
		$nom = filter_var($nom, FILTER_SANITIZE_NUMBER_INT);
		$nom = str_replace("-", "", $nom);
		$nom = str_replace("+", "", $nom);
		$nom = str_replace("(", "", $nom);
		$nom = str_replace(")", "", $nom);
		$nom = str_replace(" ", "", $nom);

		if ($first == true) {
			if (substr($nom, 0, 2) == "62") {
				$no_wa_tujuan = '+' . $nom;
			} elseif (substr($nom, 0, 1) == "8") {
				$no_wa_tujuan = '+62' . $nom;
			} elseif (substr($nom, 0, 1) == "0") {
				$no_wa_tujuan = '+62' . $nom;
				$no_wa_tujuan = str_replace("+620", "+62", $no_wa_tujuan);
			} else {
				$no_wa_tujuan = '+' . $nom;
			}
		} else {
			$nom = str_replace("62", "", $nom);

			if (substr($nom, 0, 2) == "62") {
				$no_wa_tujuan = '0' . $nom;
			} elseif (substr($nom, 0, 1) == "0") {
				$no_wa_tujuan = '' . $nom;
				$no_wa_tujuan = str_replace("+620", "", $no_wa_tujuan);
			} else {
				$no_wa_tujuan = '0' . $nom;
			}
		}

		return $no_wa_tujuan;
	}

	public static function get_order_woocommerce($order, $type_of_integration)
	{
		foreach ($order->get_items() as $item_key => $item_values) :
			$product           = $item_values->get_data();
		endforeach;

		$site_url = get_site_url();
		$order_ID = $order->get_id();
		$billing_last_name  = $order->get_billing_last_name();
		$billing_first_name = $order->get_billing_first_name();
		$customer_name = "$billing_first_name $billing_last_name";
		$customer_email      = $order->get_billing_email();
		$billing_phone      = Wollow_Order::phone_validation(true, $order->get_billing_phone());
		$order_detail    = $order->get_customer_note();
		$order_date    = date('d-m-Y', $order->get_date_created()->getOffsetTimestamp());
		$billing_total    = $order->get_total();
		$status    = $order->get_status();

		$numbers = [1, 2, 3, 4, 5, 6, 7];

		$shorcodes = [
			'{site_name}',
			'{order_id}',
			'{customer_name}',
			'{product_name}',
			'{order_date}',
			'{customer_email}',
			'{order_details}',
			'{billing_total}',
			'<div>',
			'</div>',
			'<strike>',
			'</strike>',
			'<span>',
			'</span>',
			'<b>',
			'</b>',
			'<strong> ',
			'<strong>',
			'</strong>',
			'<i>',
			'</i>',
			'<em>',
			'</em>',
			'<span style="',
			'text-decoration: line-through;">',
			'&nbsp;',
			'<br />',
			'<p>',
			'</p>',
		];

		$item_shortcode = [
			$site_url,
			$order_ID,
			$customer_name,
			$product['name'],
			$order_date,
			$customer_email,
			$order_detail,
			$billing_total,
			'',
			'%0A',
			'~',
			'~',
			'~',
			'~',
			'*',
			'*',
			' *',
			'*',
			'*',
			'_',
			'_',
			'_',
			'_',
			'~',
			'',
			'',
			'%0A',
			'',
			'%0A',
		];

		$shorcode_woowa = [
			'{site_name}',
			'{order_id}',
			'{customer_name}',
			'{product_name}',
			'{order_date}',
			'{customer_email}',
			'{order_details}',
			'{billing_total}',
			'<strong>',
			'</strong>',
			'<span>',
			'</span>',
			'<em>',
			'</em>',
			'<span style="',
			'text-decoration: line-through;">',
			'&nbsp;',
			'<br />',
			'<p>',
			'</p>',
			'<div>',
			'</div>',
		];

		$item_shortcode_woowa = [
			$site_url,
			$order_ID,
			$customer_name,
			$product['name'],
			$order_date,
			$customer_email,
			$order_detail,
			$billing_total,
			'*',
			'*',
			'~',
			'~',
			'_',
			'_',
			'~',
			'',
			'',
			'',
			'',
			'',
			'',
			'',
		];

		$send_wa = [];
		foreach ($numbers as $key => $number) {
			if ($type_of_integration == 'woowa') {
				$wollow[] = strip_tags(get_option("wollow$number"), "<br><br /><p><span><strong><em><div><strike><b><i>");
				$send_wa = str_replace($shorcode_woowa, $item_shortcode_woowa, $wollow);
			} else {
				$wollow[] = strip_tags(get_option("wollow$number"), "<br><br /><p><span><strong><em><div><strike><b><i>");
				$send_wa = str_replace($shorcodes, $item_shortcode, $wollow);
			}
		}
		return $send_wa;
	}

	public function icon_wa($billing_phone, $text_wa, $value, $get_css)
	{
		$html = '
			<a target="_blank" class="nav-link" href="https://web.whatsapp.com/send?phone=' . $billing_phone . '&text=' . $text_wa . '">
				<form method="POST">
					<input type="hidden" name="wa" value="' . $value . '">
					<button type="submit" class="button_icon" style="' . get_option($get_css) . '">
					<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" style="' . get_option($get_css) . '"/></svg>
					</button>
				</form>
			</a>
        ';
		echo $html;
	}


	public function icon_wa_d_none()
	{
		$html = '
			<a target="_blank" class="nav-link" href="#" style="display:none;">
				<form method="POST">
					<input type="hidden" name="wa" value="#">
					<button type="submit" class="button_icon">
						<i class="fa fa-phone" aria-hidden="true"></i>
					</button>
				</form>
			</a>
        ';
		echo $html;
	}

	// export csv
	public function admin_order_list_top_bar_button($which)
	{
		global $typenow;

		$processing = $this->count_status('wc-processing');
		$completed = $this->count_status('wc-completed');
		$on_hold = $this->count_status('wc-on-hold');
		$pending = $this->count_status('wc-pending');
		$cancelled = $this->count_status('wc-cancelled');
		$refunded = $this->count_status('wc-refunded');
		$failed = $this->count_status('wc-failed');

		global $wpdb;
		$shop_order = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_order_product_lookup");
		$shop_order = ceil(count($shop_order) / 2);


		$statuses = ['wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending', 'wc-cancelled', 'wc-refunded', 'wc-failed'];
		$limited = [$processing, $completed, $on_hold, $pending, $cancelled, $refunded, $failed];

		if (isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order') {
			$this->button_export($typenow, $which, 'shop_order');
		}

		// send free 08
		foreach ($statuses as $key => $status) {
			if (isset($_GET['post_status']) && $_GET['post_status'] == $status) {
				$this->button_export($typenow, $which, $status);
			}

			if (isset($_GET['laporan_08']) && $_GET['laporan_08'] == (string)$status) {
				$this->export_csv(false, $limited[$key], (string)$status);
			}
		}
		if (isset($_GET['laporan_08']) && $_GET['laporan_08'] == 'shop_order') {
			$this->export_all_csv(false, $shop_order, 'shop_order');
		}
	}

	public function button_export($typenow, $which, $status)
	{
		if ('shop_order' === $typenow && 'top' === $which) :
			require_once 'partials/export-csv.php';
		endif;
	}

	public function export_all_csv($boolean, $limit)
	{
		$wp_filename = "order_data_" . date("d-m-y") . ".csv";

		ob_end_clean();

		$wp_file = fopen($wp_filename, "w");

		$this->get_validation($boolean, $limit, null, $wp_file);

		fclose($wp_file);

		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=" . $wp_filename);
		header("Content-Type: application/csv;");
		readfile($wp_filename);
		exit;
	}

	public function export_csv($boolean, $limit, $status)
	{
		$wp_filename = "order_data_" . date("d-m-y") . ".csv";

		ob_end_clean();

		$wp_file = fopen($wp_filename, "w");

		$this->get_validation($boolean, $limit, $status, $wp_file);

		fclose($wp_file);

		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=" . $wp_filename);
		header("Content-Type: application/csv;");
		readfile($wp_filename);
		exit;
	}

	public function get_validation($boolean, $limit, $status, $wp_file)
	{
		foreach ($this->get_orders($limit, $status) as $order) {
			$first_name = $order->get_billing_first_name();
			$last_name  = $order->get_billing_last_name();
			$all_data = array(
				"name" => $first_name . ' ' . $last_name,
				"phone" => $this->phone_validation($boolean, $order->get_billing_phone()) . "\n",
			);
			fputs($wp_file, implode(",", $all_data));
		}
	}

	public function get_orders($limit = null, $status = null)
	{
		return wc_get_orders(
			array(
				'limit' => $limit,
				'type' => 'shop_order',
				'post_status' => $status,
			)
		);
	}

	public function get_all_orders($limit)
	{
		return wc_get_orders(
			array(
				'limit' => $limit,
				'type' => 'shop_order',
				'status' => array('wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending', 'wc-cancelled', 'wc-refunded', 'wc-failed'),
				// 'status'=> 'all',
			)
		);
	}


	// get order status 
	public function post_status($status)
	{
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_status = '$status'";
		$data = $wpdb->get_results($sql);
		return $data;
	}

	public function count_status($status)
	{
		$count = ceil(count($this->post_status($status)) / 2);
		return $count;
	}
}
