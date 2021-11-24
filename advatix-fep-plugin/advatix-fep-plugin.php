<?php 
/** 
 *
 * @author Advatix
 * @copyright 2021 Advatix 
 * @license GPL-2.0-or-later 
 * 
 * Plugin Name: Advatix FEP API Connection 
 * Description: This plugin works with advatix fep API and it allows to send order details and receive order response.
 * Version: 1.2.1
 * Author: Advatix
 * Author URI: https://advatix.com/ 
 * Text Domain: advatix-fep
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt 
 * 
 * */

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// Start Class
	if ( ! class_exists( 'ADVATIX_FEP_PLUGIN' ) ) {

		class ADVATIX_FEP_PLUGIN {

			/**
			 * Start things up
			 *
			 * @since 1.2.1
			 */
			public function __construct() {

				// We only need to register the admin panel on the back-end
				if ( is_admin() ) {
					add_action( 'admin_menu', array( 'ADVATIX_FEP_PLUGIN', 'add_admin_menu' ) );
					add_action( 'admin_init', array( 'ADVATIX_FEP_PLUGIN', 'register_settings' ) );
				}

			}

			/**
			 * Returns all theme options
			 *
			 * @since 1.2.1
			 */
			public static function get_theme_options() {
				return get_option( 'theme_options' );
			}

			/**
			 * Returns single theme option
			 *
			 * @since 1.2.1
			 */
			public static function get_theme_option( $id ) {
				$options = self::get_theme_options();
				if ( isset( $options[$id] ) ) {
					return $options[$id];
				}
			}

			/**
			 * Returns order array data for fep
			 *
			 * @since 1.2.1
			 */
			public static function get_fep_order_data( $order_id ) {
				$order = wc_get_order( $order_id );
				$user      = $order->get_user(); // Get the WP_User object
				$order_total = $order->get_total();
				$date_created  = date('Y-m-d H:i:s'); // Get date created (WC_DateTime object)
				$date_created = "$date_created";
				
				foreach($order->get_items() as $k=>$v){
					$product = wc_get_product( $v->get_product_id() );
					$orderItems[] = array(
										'sku' => $product->get_sku(),
										'quantity' => $v->get_quantity(),
										'price' => $v->get_total(),
									);
				}

				if(!empty($order->get_shipping_first_name())){
					$shipping_first_name  = $order->get_shipping_first_name();
				} else {
					$shipping_first_name  = $order->get_billing_first_name();
				}
				if(!empty($order->get_shipping_last_name())){
					$shipping_last_name  = $order->get_shipping_last_name();
				} else {
					$shipping_last_name  = $order->get_billing_last_name();
				}
				if(!empty($order->get_shipping_company())){
					$shipping_company  = $order->get_shipping_company();
				} else {
					$shipping_company  = $order->get_billing_company();
				}
				if(!empty($order->get_shipping_address_1())){
					$shipping_address_1  = $order->get_shipping_address_1();
				} else {
					$shipping_address_1  = $order->get_billing_address_1();
				}
				if(!empty($order->get_shipping_address_2())){
					$shipping_address_2  = $order->get_shipping_address_2();
				} else {
					$shipping_address_2  = $order->get_billing_address_2();
				}
				if(!empty($order->get_shipping_city())){
					$shipping_city  = $order->get_shipping_city();
				} else {
					$shipping_city  = $order->get_billing_city();
				}
				if(!empty($order->get_shipping_state())){
					$shipping_state  = $order->get_shipping_state();
				} else {
					$shipping_state  = $order->get_billing_state();
				}
				if(!empty($order->get_shipping_postcode())){
					$shipping_postcode  = $order->get_shipping_postcode();
				} else {
					$shipping_postcode  = $order->get_billing_postcode();
				}
				if(!empty($order->get_shipping_country())){
					$shipping_country  = $order->get_shipping_country();
				} else {
					$shipping_country  = $order->get_billing_country();
				}

				$billing_email     = $order->get_billing_email();
				$billing_phone     = $order->get_billing_phone();

				$billing_first_name  = $order->get_billing_first_name();
				$billing_last_name   = $order->get_billing_last_name();
				$billing_company     = $order->get_billing_company();
				$billing_address_1   = $order->get_billing_address_1();
				$billing_address_2   = $order->get_billing_address_2();
				$billing_city        = $order->get_billing_city();
				$billing_state       = $order->get_billing_state();
				$billing_postcode    = $order->get_billing_postcode();
				$billing_country     = $order->get_billing_country();

				
				$company_name = self::get_theme_option('company_name');
				$lob = self::get_theme_option('input_lob');
				
				if($lob == ''){
					$lob = 3;
				}
				
				$base_url = self::get_theme_option('input_api_url');
				$url = $base_url.'/order/createOrder';

				$accountID = self::get_theme_option('account_id');
				$api_key = self::get_theme_option('input_api_key');
				
				$order_id = "$order_id";
				$order_total = "$order_total";

				$url1 = explode('.',$_SERVER['SERVER_NAME']);
					
				if(count($url1) == 2){
					$prefix = $url1[0];
				}else if(count($url1) == 3){
					$prefix = $url1[1];
				}
				
				$prefix = substr($prefix, 0, 3);

				$data = array(
					"accountId" => $accountID,
					"referenceId" => $order_id,
					"orderNumber" => $prefix.'-'.$order_id,
					"orderType" => "6",
					"addressType" => "Residential",
					"shipToName" => $shipping_first_name.' '.$shipping_last_name,
					"shipToAddress" => $shipping_address_1.' '.$shipping_address_2,
					"shipToCity" => $shipping_city,
					"shipToCountry" => $shipping_country,
					"shipToEmail" => $billing_email,
					"shipToMobile" => $billing_phone,
					"shipToState" => $shipping_state,
					"postalCode" => $shipping_postcode,
					"billToName" => $billing_first_name.' '.$billing_last_name,
					"billToAddress" => $billing_address_1.' '.$billing_address_2,
					"billToCity" => $billing_city,
					"billToState" => $billing_state,
					"billToPostal" => $billing_postcode,
					"billToCountry" => $billing_country,
					"billToMobile" => $billing_phone,
					"billToEmail" => $billing_email,
					"addtionalCharges" => 0,
					"paymentMode" => 1,
					"paymentStatus" => 0,
					"deliveryTargetDate" => "09-01-2021",
					"shipByDate" => date('m-d-Y'),
					"pickupDate" => date('m-d-Y'),
					"companyName" => $company_name,
					"cxPhone" => $billing_phone,
					"cxEmail" => $user->user_email,
					"beginDate" => $date_created,
					"totalWeight" => "0.26235009178",
					"totalAmount" => $order_total,
					"notification" => true,
					"lob" => "$lob",
					"d2cOrder" => false,
					"orderItems" => $orderItems
				);
				
				return $data;
			}

			/**
			 * Add sub menu page
			 *
			 * @since 1.2.1
			 */
			public static function add_admin_menu() {
				add_menu_page(
					esc_html__( 'Advatix', 'advatix-fep-plugin' ),
					esc_html__( 'Advatix', 'advatix-fep-plugin' ),
					'manage_options',
					'theme-settings',
					array( 'ADVATIX_FEP_PLUGIN', 'create_admin_page' )
				);
				
				add_submenu_page ("theme-settings", 
					esc_html__( 'Settings', 'textdomain' ),
					esc_html__( 'Settings', 'textdomain' ),
					'manage_options',
					'theme-settings',
					array( 'ADVATIX_FEP_PLUGIN', 'create_admin_page' ),
					10
				);
				
				add_submenu_page ("theme-settings", 
					esc_html__( 'Orders', 'textdomain' ),
					esc_html__( 'Orders', 'textdomain' ),
					'manage_options',
					'order-settings',
					array( 'ADVATIX_FEP_PLUGIN', 'fep_orders' ),
					10
				);
			}

			/**
			 * Register a setting and its sanitization callback.
			 *
			 * We are only registering 1 setting so we can store all options in a single option as
			 * an array. You could, however, register a new setting for each option
			 *
			 * @since 1.2.1
			 */
			public static function register_settings() {
				register_setting( 'theme_options', 'theme_options', array( 'ADVATIX_FEP_PLUGIN', 'sanitize' ) );
			}

			/**
			 * Sanitization callback
			 *
			 * @since 1.2.1
			 */
			public static function sanitize( $options ) {

				// If we have options lets sanitize them
				if ( $options ) {

					if ( ! empty( $options['input_api_key'] ) ) {
						$options['input_api_key'] = sanitize_text_field( $options['input_api_key'] );
					} else {
						unset( $options['input_api_key'] ); // Remove from options if empty
					}
		
					if ( ! empty( $options['input_api_url'] ) ) {
						$options['input_api_url'] = sanitize_text_field( $options['input_api_url'] );
					} else {
						unset( $options['input_api_url'] ); // Remove from options if empty
					}
		
					if ( ! empty( $options['account_id'] ) ) {
						$options['account_id'] = sanitize_text_field( $options['account_id'] );
					} else {
						unset( $options['account_id'] ); // Remove from options if empty
					}
					
					if ( ! empty( $options['company_name'] ) ) {
						$options['company_name'] = sanitize_text_field( $options['company_name'] );
					} else {
						unset( $options['company_name'] ); // Remove from options if empty
					}
					
					if ( ! empty( $options['input_lob'] ) ) {
						$options['input_lob'] = sanitize_text_field( $options['input_lob'] );
					} else {
						unset( $options['input_lob'] ); // Remove from options if empty
					}
					
					if ( ! empty( $options['sync_inventory'] ) ) {
						$options['sync_inventory'] = sanitize_text_field( $options['sync_inventory'] );
					} else {
						unset( $options['sync_inventory'] ); // Remove from options if empty
					}
				}

				// Return sanitized options
				return $options;

			}

			/**
			 * Settings page output
			 *
			 * @since 1.2.1
			 */
			public static function create_admin_page() {
				include(WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/includes/admin-settings.php');
			}
			
			
			/**
			 * Orders page output
			 *
			 * @since 1.2.1
			 */
			public static function fep_orders() {
				include(WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/includes/admin-orders.php');
			}
		}
	}
	new ADVATIX_FEP_PLUGIN();

	// Helper function to use in your theme to return a theme option value
	function advatix_api_option( $id = '' ) {
		return ADVATIX_FEP_PLUGIN::get_theme_option( $id );
	}

	// Helper function to return order data for fep api
	function advatix_fep_order_data( $order_id = '' ) {
		return ADVATIX_FEP_PLUGIN::get_fep_order_data( $order_id );
	}


	/**
	 * Sync Bulk Orders Ajax
	 */
	add_action('wp_ajax_adv_sync_fep_all_order_details', 'adv_sync_fep_all_order_details_callback');
	add_action('wp_ajax_nopriv_adv_sync_fep_all_order_details', 'adv_sync_fep_all_order_details_callback');
	function adv_sync_fep_all_order_details_callback()
	{
		global $wpdb;
		
		$orders = sanitize_text_field($_POST['order_ids']);
		
		$base_url = advatix_api_option('input_api_url');
		$api_url = $base_url.'/order/createOrder';
		$api_key = advatix_api_option('input_api_key');
		
		
		foreach($orders as $order_id){
			$data = advatix_fep_order_data( $order_id );

			$headers = array(
				'Content-Type' => 'application/json',
				'Device-Type' => 'Web',
				'Ver' => '1.0',
				'ApiKey' => $api_key
			);
			
			$args = array(
				'headers' => $headers,
				'timeout' => 300000,
				'body' => wp_json_encode($data)
			);

			$res = wp_remote_post($api_url, $args );

			$result_jd = json_decode($res['body']);
			
			$table_name = $wpdb->prefix . 'fep_api_order_resp';
			$wpdb->insert(
				$table_name,
				array(
					'requestJson' => wp_json_encode($data),
					'order_id' => $order_id,
					'responseMessage' => $result_jd->responseMessage,
					'responseStatus' => $result_jd->responseStatus,
					'responseStatusCode' => $result_jd->responseStatusCode,
					'responseObject' => $result_jd->responseObject,
					'time' => current_time( 'mysql' ),
				)
			);

		}
		
		die();
	}


	/**
	 * Sync Single Order Ajax
	 */
	add_action('wp_ajax_adv_sync_fep_order_details', 'adv_sync_fep_order_details_callback');
	add_action('wp_ajax_nopriv_adv_sync_fep_order_details', 'adv_sync_fep_order_details_callback');
	function adv_sync_fep_order_details_callback()
	{
		global $wpdb;
		
		$order_id = sanitize_text_field($_POST['order_id']);
		
		$data = advatix_fep_order_data( $order_id );
		
		$base_url = advatix_api_option('input_api_url');
		$api_url = $base_url.'/order/createOrder';

		$api_key = advatix_api_option('input_api_key');
		
		
		$headers = array(
			'Content-Type' => 'application/json',
			'Device-Type' => 'Web',
			'Ver' => '1.0',
			'ApiKey' => $api_key
		);
		
		$args = array(
			'headers' => $headers,
			'timeout' => 300000,
			'body' => wp_json_encode($data)
		);

		$res = wp_remote_post($api_url, $args );

		$result_jd = json_decode($res['body']);
		
		$table_name = $wpdb->prefix . 'fep_api_order_resp';
		$wpdb->insert(
			$table_name,
			array(
				'requestJson' => wp_json_encode($data),
				'order_id' => $order_id,
				'responseMessage' => $result_jd->responseMessage,
				'responseStatus' => $result_jd->responseStatus,
				'responseStatusCode' => $result_jd->responseStatusCode,
				'responseObject' => $result_jd->responseObject,
				'time' => current_time( 'mysql' ),
			)
		);

		
		$array = array(
					'responseCode' => $result_jd->responseStatusCode,
					'responseMessage' => $result_jd->responseMessage
				);
		
		echo wp_json_encode($array);
		
		die();
	}


	/**
	 * Fetch Single Order FEP API response Ajax
	 */
	add_action('wp_ajax_adv_get_fep_api_details', 'adv_get_fep_api_details_callback');
	add_action('wp_ajax_nopriv_adv_get_fep_api_details', 'adv_get_fep_api_details_callback');
	function adv_get_fep_api_details_callback()
	{
		global $wpdb;
		
		$table_name = $wpdb->prefix . 'fep_api_order_resp';
		$q = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE order_id="'.sanitize_text_field($_POST['order_id']).'" ORDER BY id DESC LIMIT 10');
		
		
		if(!empty($q)){
			foreach($q as $k=>$v){
				echo "<tr>";
				echo "<td>".date('M d, Y - H:iA', strtotime($v->time))."</td>";
				echo "<td>".$v->responseStatusCode."</td>";
				if($v->responseMessage==''){
					echo "<td>-</td>";
				}else{
					echo "<td>".$v->responseMessage."</td>";
				}
				echo "</tr>";
			}
		}else{
			echo "<tr>";
			echo "<td colspan='3'>No data found.</td>";
			echo "</tr>";
		}
		
		
		die();
	}

	/**
	 * New order create hook
	 */
	add_action('woocommerce_new_order', function ($order_id, $order) {
		global $wpdb;
		
		$parent_id = $order->get_parent_id(); // Get the parent order ID (for subscriptions…)

		$user_id   = $order->get_user_id(); // Get the costumer ID
		$user      = $order->get_user(); // Get the WP_User object

		$order_total = $order->get_total();
		$order_status  = $order->get_status(); // Get the order status (see the conditional method has_status() below)
		$currency      = $order->get_currency(); // Get the currency used  
		$payment_method = $order->get_payment_method(); // Get the payment method ID
		$payment_title = $order->get_payment_method_title(); // Get the payment method title
		$date_created  = date('Y-m-d H:i:s'); // Get date created (WC_DateTime object)
		$date_created = "$date_created";
		$date_modified = $order->get_date_modified(); // Get date modified (WC_DateTime object)
		
		foreach($order->get_items() as $k=>$v){
			$product = wc_get_product( $v->get_product_id() );
			$orderItems[] = array(
								'sku' => $product->get_sku(),
								'quantity' => $v->get_quantity(),
								'price' => $v->get_total(),
							);
		}

		if(!empty($order->get_shipping_first_name())){
			$shipping_first_name  = $order->get_shipping_first_name();
		} else {
			$shipping_first_name  = $order->get_billing_first_name();
		}
		if(!empty($order->get_shipping_last_name())){
			$shipping_last_name  = $order->get_shipping_last_name();
		} else {
			$shipping_last_name  = $order->get_billing_last_name();
		}
		if(!empty($order->get_shipping_company())){
			$shipping_company  = $order->get_shipping_company();
		} else {
			$shipping_company  = $order->get_billing_company();
		}
		if(!empty($order->get_shipping_address_1())){
			$shipping_address_1  = $order->get_shipping_address_1();
		} else {
			$shipping_address_1  = $order->get_billing_address_1();
		}
		if(!empty($order->get_shipping_address_2())){
			$shipping_address_2  = $order->get_shipping_address_2();
		} else {
			$shipping_address_2  = $order->get_billing_address_2();
		}
		if(!empty($order->get_shipping_city())){
			$shipping_city  = $order->get_shipping_city();
		} else {
			$shipping_city  = $order->get_billing_city();
		}
		if(!empty($order->get_shipping_state())){
			$shipping_state  = $order->get_shipping_state();
		} else {
			$shipping_state  = $order->get_billing_state();
		}
		if(!empty($order->get_shipping_postcode())){
			$shipping_postcode  = $order->get_shipping_postcode();
		} else {
			$shipping_postcode  = $order->get_billing_postcode();
		}
		if(!empty($order->get_shipping_country())){
			$shipping_country  = $order->get_shipping_country();
		} else {
			$shipping_country  = $order->get_billing_country();
		}

		$billing_email     = $order->get_billing_email();
		$billing_phone     = $order->get_billing_phone();

		$billing_first_name  = $order->get_billing_first_name();
		$billing_last_name   = $order->get_billing_last_name();
		$billing_company     = $order->get_billing_company();
		$billing_address_1   = $order->get_billing_address_1();
		$billing_address_2   = $order->get_billing_address_2();
		$billing_city        = $order->get_billing_city();
		$billing_state       = $order->get_billing_state();
		$billing_postcode    = $order->get_billing_postcode();
		$billing_country     = $order->get_billing_country();

		
		$company_name = advatix_api_option('company_name');
		$lob = advatix_api_option('input_lob');
		if($lob == ''){
			$lob = 3;
		}
		
		$base_url = advatix_api_option('input_api_url');
		$api_url = $base_url.'/order/createOrder';

		$accountID = advatix_api_option('account_id');
		$api_key = advatix_api_option('input_api_key');
		
		$order_id = "$order_id";
		$order_total = "$order_total";

		$url1 = explode('.',$_SERVER['SERVER_NAME']);
			
		if(count($url1) == 2){
			$prefix = $url1[0];
		}else if(count($url1) == 3){
			$prefix = $url1[1];
		}
		
		$prefix = substr($prefix, 0, 3);

		$data = array(
			"accountId" => $accountID,
			"referenceId" => $order_id,
			"orderNumber" => $prefix.'-'.$order_id,
			"orderType" => "6",
			"addressType" => "Residential",
			"shipToName" => $shipping_first_name.' '.$shipping_last_name,
			"shipToAddress" => $shipping_address_1.' '.$shipping_address_2,
			"shipToCity" => $shipping_city,
			"shipToCountry" => $shipping_country,
			"shipToEmail" => $billing_email,
			"shipToMobile" => $billing_phone,
			"shipToState" => $shipping_state,
			"postalCode" => $shipping_postcode,
			"billToName" => $billing_first_name.' '.$billing_last_name,
			"billToAddress" => $billing_address_1.' '.$billing_address_2,
			"billToCity" => $billing_city,
			"billToState" => $billing_state,
			"billToPostal" => $billing_postcode,
			"billToCountry" => $billing_country,
			"billToMobile" => $billing_phone,
			"billToEmail" => $billing_email,
			"addtionalCharges" => 0,
			"paymentMode" => 1,
			"paymentStatus" => 0,
			"deliveryTargetDate" => "09-01-2021",
			"shipByDate" => date('m-d-Y'),
			"pickupDate" => date('m-d-Y'),
			"companyName" => $company_name,
			"cxPhone" => $billing_phone,
			"cxEmail" => $user->user_email,
			"beginDate" => $date_created,
			"totalWeight" => "0.26235009178",
			"totalAmount" => $order_total,
			"notification" => true,
			"lob" => "$lob",
			"d2cOrder" => false,
			"orderItems" => $orderItems
		);


		$headers = array(
			'Content-Type' => 'application/json',
			'Device-Type' => 'Web',
			'Ver' => '1.0',
			'ApiKey' => $api_key
		);
		
		$args = array(
			'headers' => $headers,
			'timeout' => 300000,
			'body' => wp_json_encode($data)
		);

		$res = wp_remote_post($api_url, $args );

		$result_jd = json_decode($res['body']);
		
		$table_name = $wpdb->prefix . 'fep_api_order_resp';
		$wpdb->insert(
			$table_name,
			array(
				'requestJson' => wp_json_encode($data),
				'order_id' => $order_id,
				'responseMessage' => $result_jd->responseMessage,
				'responseStatus' => $result_jd->responseStatus,
				'responseStatusCode' => $result_jd->responseStatusCode,
				'responseObject' => $result_jd->responseObject,
				'time' => current_time( 'mysql' ),
			)
		);

	}, 1, 2);

	
	/**
	 * Plugin activation hook
	 */
	function adv_fep_install() {
		global $wpdb;
		$table_name = $wpdb->base_prefix.'fep_api_order_resp';
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			order_id varchar(100) DEFAULT NULL,
			requestJson longtext DEFAULT NULL,
			responseMessage longtext DEFAULT NULL,
			responseStatus varchar(200) DEFAULT NULL,
			responseStatusCode varchar(200) DEFAULT NULL,
			responseObject longtext DEFAULT NULL,
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		// Schedule an action if it's not already scheduled
		if ( ! wp_next_scheduled( 'adv_fep_isa_add_every_thirty_minute' ) ) {
			wp_schedule_event( time(), 'every_thirty_minute', 'adv_fep_isa_add_every_thirty_minute' );
		}
		
		if ( ! wp_next_scheduled( 'adv_fep_daily_at_midnight_actions' ) ) {
			$local_time_to_run = 'midnight';
			$timestamp = strtotime( $local_time_to_run ) - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
			wp_schedule_event(
				$timestamp,
				'every_day_midnight',
				'adv_fep_daily_at_midnight_actions'
			);
		}
		
	}
	register_activation_hook( __FILE__, 'adv_fep_install' );

	/**
	 * Plugin Deactivation hook
	 */
	function adv_fep_my_deactivation() {
		wp_clear_scheduled_hook( 'adv_fep_isa_add_every_thirty_minute' );
		wp_clear_scheduled_hook( 'adv_fep_daily_at_midnight_actions' );
	}
	register_deactivation_hook( __FILE__, 'adv_fep_my_deactivation' );
	

	/**
	 * Custom Cron Schedules
	 */
	add_filter( 'cron_schedules', 'adv_fep_isa_add_every_thirty_minute' );
	function adv_fep_isa_add_every_thirty_minute( $schedules ) {
		$schedules['every_thirty_minute'] = array(
				'interval'  => 1800,
				'display'   => __( 'Advatix - Every Thirty Minutes', 'textdomain' )
		);
		
		$schedules['every_day_midnight'] = array(
				'interval'  => 86400,
				'display'   => __( 'Advatix - Daily Midnight', 'textdomain' )
		);
		return $schedules;
	}

	/**
	 * Sync mis-match inventory - Every Midnight Cron
	 */
	add_action( 'adv_fep_daily_at_midnight_actions', 'adv_fep_daily_at_midnight_actions_func' );
	function adv_fep_daily_at_midnight_actions_func() {
		$sync_inventory = advatix_api_option('sync_inventory');
		
		if($sync_inventory == 1){
			global $wpdb;
		
			$base_url = advatix_api_option('input_api_url');
			$api_key = advatix_api_option('input_api_key');
			$accountID = advatix_api_option('account_id');
			
			$headers = array(
				'Content-Type' => 'application/json',
				'Device-Type' => 'Web',
				'Ver' => '1.0',
				'ApiKey' => $api_key
			);
			
			$query = new WP_query(array(
						'post_type' => 'product',
						'posts_per_page' => -1)
					);
					
			while($query->have_posts()){
				$query->the_post();
				$product = wc_get_product( get_the_ID() );
				$sku = $product->get_sku();
				
				if($sku!=''){
					
					$api_url = $base_url.'/inventory/getAvailableQuantityForProduct';
					
					$cont = array(
						array( 'key' => 'SKU', 'value' => $sku ),
						array( 'key' => 'ACCOUNT_ID', 'value' => $accountID ),
					);
					
					$args = array(
						'headers' => $headers,
						'timeout' => 300000,
						'body' => wp_json_encode($cont)
					);
					
					$res = wp_remote_post($api_url, $args);
					
					$return = json_decode($res['body']);
					$inv = $return->responseObject;
					
					if(!empty($inv->content)){
						update_post_meta( $product->get_id(), '_manage_stock', 'yes' );
						update_post_meta( $product->get_id(), '_stock', $inv->content[0]->availableToPromise );
					}
				}
			}
		}
	}
	
	/**
	 * Retry Sync last 100 orders - Every Thirty Minutes Cron
	 */
	add_action( 'adv_fep_isa_add_every_thirty_minute', 'adv_fep_every_thirty_minute_event_func' );
	function adv_fep_every_thirty_minute_event_func() {
		global $wpdb, $woocommerce;
		
		$orders = get_posts(array(
			'post_type' => wc_get_order_types('view-orders'),
			'posts_per_page' => 100,
			'post_status' => array_keys(wc_get_order_statuses())
		));
		
		foreach($orders as $k=>$v){
			$order = wc_get_order( $v->ID );
			if ( is_a( $order, 'WC_Order_Refund' ) ) {
				$order = wc_get_order( $order->get_parent_id() );
			}
			
			$table_name = $wpdb->prefix . 'fep_api_order_resp';
			$q = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE order_id="'.$order->get_id().'" ORDER BY id DESC');
			
			$synced = false;
			$warning = false;
			
			foreach($q as $kk=>$vv){
				if($vv->responseStatusCode == 200){
					$synced = true;
				}else{
					if ( (strpos(strtolower($vv->responseMessage), 'order already exists with order number') !== false)
						||(strpos(strtolower($vv->responseMessage), 'products are not available at fc') !== false)
						||(strpos(strtolower($vv->responseMessage), 'ordered products are not available') !== false)
						||(strpos(strtolower($vv->responseMessage), '{error.message.order.items.sku.not.null}') !== false)
						||(strpos(strtolower($vv->responseMessage), 'order items empty cannot continue') !== false)
						||(strpos(strtolower($vv->responseMessage), 'incorrect zip code') !== false)
						||(strpos(strtolower($vv->responseMessage), 'Shipping Address not found for') !== false) )
					{
						$synced = true;
						$warning = true;
					}
				}
			}
			
			if($synced == false && $warning == false){
				
				$data = advatix_fep_order_data( $order->get_id() );
				
				$base_url = advatix_api_option('input_api_url');
				$api_url = $base_url.'/order/createOrder';

				$api_key = advatix_api_option('input_api_key');
				
				$headers = array(
					'Content-Type' => 'application/json',
					'Device-Type' => 'Web',
					'Ver' => '1.0',
					'ApiKey' => $api_key
				);
				
				$args = array(
					'headers' => $headers,
					'timeout' => 300000,
					'body' => wp_json_encode($data)
				);

				$res = wp_remote_post($api_url, $args );

				$result_jd = json_decode($res['body']);
				
				$table_name = $wpdb->prefix . 'fep_api_order_resp';
				$wpdb->insert(
					$table_name,
					array(
						'requestJson' => wp_json_encode($data),
						'order_id' => $order->get_id(),
						'responseMessage' => $result_jd->responseMessage,
						'responseStatus' => $result_jd->responseStatus,
						'responseStatusCode' => $result_jd->responseStatusCode,
						'responseObject' => $result_jd->responseObject,
						'time' => current_time( 'mysql' ),
					)
				);
			}
		}
	}


	/**
	 * Register new woocommerce status
	 */
	function adv_fep_register_created_order_status() {
		register_post_status( 'wc-created', array(
			'label'                     => 'Created',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Created (%s)', 'Created (%s)' )
		) );
		
		register_post_status( 'wc-assigned', array(
			'label'                     => 'Assigned',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Assigned (%s)', 'Assigned (%s)' )
		) );
		
		register_post_status( 'wc-picked', array(
			'label'                     => 'Picked',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Picked (%s)', 'Picked (%s)' )
		) );
		
		register_post_status( 'wc-packaging', array(
			'label'                     => 'Packaging',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Packaging (%s)', 'Packaging (%s)' )
		) );
		
		register_post_status( 'wc-shipped', array(
			'label'                     => 'Shipped',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Shipped (%s)', 'Shipped (%s)' )
		) );
		
		register_post_status( 'wc-delivered', array(
			'label'                     => 'Delivered',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Delivered (%s)', 'Delivered (%s)' )
		) );
		
		register_post_status( 'wc-processing', array(
			'label'                     => 'Processing',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Processing (%s)', 'Processing (%s)' )
		) );
		
		register_post_status( 'wc-pending', array(
			'label'                     => 'Pending',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Pending (%s)', 'Pending (%s)' )
		) );
		
		register_post_status( 'wc-on-hold', array(
			'label'                     => 'On hold',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'On hold (%s)', 'On hold (%s)' )
		) );
		
		register_post_status( 'wc-completed', array(
			'label'                     => 'Completed',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Completed (%s)', 'Completed (%s)' )
		) );
		
		register_post_status( 'wc-refunded', array(
			'label'                     => 'Refunded',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Refunded (%s)', 'Refunded (%s)' )
		) );
	}
	add_action( 'init', 'adv_fep_register_created_order_status' );


	/**
	 * Add to list of WC Order statuses
	 */
	function adv_fep_add_created_to_order_statuses( $order_statuses ) {
	 
		$new_order_statuses = array();
	 
		$new_order_statuses['wc-created'] = 'Created';
		$new_order_statuses['wc-assigned'] = 'Assigned';
		$new_order_statuses['wc-picked'] = 'Picked';
		$new_order_statuses['wc-packaging'] = 'Packaging';
		$new_order_statuses['wc-shipped'] = 'Shipped';
		$new_order_statuses['wc-delivered'] = 'Delivered';
		$new_order_statuses['wc-cancelled'] = 'Cancelled';
		$new_order_statuses['wc-processing'] = 'Processing';
		$new_order_statuses['wc-pending'] = 'Pending';
		$new_order_statuses['wc-on-hold'] = 'On hold';
		$new_order_statuses['wc-completed'] = 'Completed';
		$new_order_statuses['wc-refunded'] = 'Refunded';
			
		return $new_order_statuses;
	}
	add_filter( 'wc_order_statuses', 'adv_fep_add_created_to_order_statuses' );


	/**
	 * Register Advatix Rest API URLs
	 */
	add_action( 'rest_api_init', function () {
	  register_rest_route( 'advatix-fep-plugin/v1', '/updateOrder', array(
		'methods' => 'POST',
		'callback' => 'adv_update_order',
	  ) );
	} );

	/**
	 * Advatix Rest API Hook for Order status update
	 */
	function adv_update_order( $request ) {
		$parameters = $request->get_json_params();
		
		if(empty($parameters)){
			return new WP_Error( 'invalid_data', 'Invalid json body', array( 'status' => 404 ) );
		}
		
		if(empty($parameters['orderReferenceNumber'])){
			return new WP_Error( 'invalid_order', 'Invalid order id', array( 'status' => 404 ) );
		}
		
		$order = new WC_Order($parameters['orderReferenceNumber']);
		
		if(empty($order->get_data())){
			return new WP_Error( 'invalid_order', 'Invalid order id', array( 'status' => 404 ) );
		}
		
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Created'){
			$order->update_status('created');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Assigned'){
			$order->update_status('assigned');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Picked'){
			$order->update_status('picked');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Packaging'){
			$order->update_status('packaging');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Cancelled'){
			$order->update_status('cancelled');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Shipped'){
			$order->update_status('shipped');
		}
		if($parameters['subOrdersList'][0]['orderStatusDesc']=='Delivered'){
			$order->update_status('delivered');
		}
		
		return $order->get_data();
	}

}
?>
