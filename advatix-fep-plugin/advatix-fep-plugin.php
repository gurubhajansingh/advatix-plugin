<?php 
/** 
 *
 * @author Guru Bhajan Singh
 * @copyright 2021 Guru Bhajan Singh 
 * @license GPL-2.0-or-later 
 * 
 * Plugin Name: Advatix FEP API Connection 
 * Plugin URI: https://gurubhajansingh.github.io/ 
 * Description: This plugin works with advatix fep API and it allows to send order details and receive order response.
 * Version: 1.0.0
 * Author: Guru Bhajan Singh
 * Author URI: https://gurubhajansingh.github.io/ 
 * Text Domain: advatix-fep
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt 
 * 
 * */


 // Start Class
if ( ! class_exists( 'ADVATIX_FEP_PLUGIN' ) ) {

	class ADVATIX_FEP_PLUGIN {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
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
		 * @since 1.0.0
		 */
		public static function get_theme_options() {
			return get_option( 'theme_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_theme_option( $id ) {
			$options = self::get_theme_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			// add_submenu_page ("themes.php", 
			// 	esc_html__( 'Payoneer API Settings', 'textdomain' ),
			// 	esc_html__( 'Payoneer API Settings', 'textdomain' ),
			// 	'manage_options',
			// 	'payoneer-setting',
			// 	array( 'ADVATIX_FEP_PLUGIN', 'create_admin_page' ),
			// 	10
			// );
			add_menu_page(
                esc_html__( 'Advatix Fep API Setting', 'advatix-fep-plugin' ),
                esc_html__( 'Advatix Fep API Setting', 'advatix-fep-plugin' ),
                'manage_options',
                'theme-settings',
                array( 'ADVATIX_FEP_PLUGIN', 'create_admin_page' )
            );
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'theme_options', 'theme_options', array( 'ADVATIX_FEP_PLUGIN', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Checkbox
				// if ( ! empty( $options['checkbox_example'] ) ) {
				// 	$options['checkbox_example'] = 'on';
				// } else {
				// 	unset( $options['checkbox_example'] ); // Remove from options if not checked
				// }

				// Input

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
    
                // if ( ! empty( $options['program_id'] ) ) {
                // 	$options['program_id'] = sanitize_text_field( $options['program_id'] );
                // } else {
                // 	unset( $options['program_id'] ); // Remove from options if empty
                // }
				// Select
				// if ( ! empty( $options['select_example'] ) ) {
				// 	$options['select_example'] = sanitize_text_field( $options['select_example'] );
				// }

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() {
			global $wpdb;
			// $table_name = $wpdb->prefix . 'fep_api_order_resp';
			// $q = $wpdb->get_results('SELECT * FROM '.$table_name);
			
			// echo "<pre>";
			// print_r($q);
			// echo "</pre>";
			
			// $order = wc_get_order( 866 );
			
			// foreach($order->get_items() as $k=>$v){
				// $product = wc_get_product( $v->get_product_id() );
				// $orderItems[] = array(
									// 'sku' => $product->get_sku(),
									// 'quantity' => $v->get_quantity(),
									// 'price' => $v->get_total(),
								// );
			// }
			
			// echo "<pre>";
			// print_r($orderItems);
			// echo "</pre>";
			?>

            <div class="wrap">
    
                <h1><?php esc_html_e( 'Advatix Fep API Settings', 'advatix-fep-plugin' ); ?></h1><hr>
				<?php
					// $order = new WC_Order(13);
					// echo "<pre>";
					// print_r($order->get_data());
					// echo "</pre>";
				?>
                <form method="post" action="options.php">
    
                    <?php settings_fields( 'theme_options' ); ?>
    
                    <table class="form-table wpex-custom-admin-login-table">
    
                        <?php // Checkbox example ?>
                        <!--tr valign="top">
                            <th scope="row"><?php// esc_html_e( 'Checkbox Example', 'advatix-fep-plugin' ); ?></th>
                            <td>
                                <?php// $value = self::get_theme_option( 'checkbox_example' ); ?>
                                <input type="checkbox" name="theme_options[checkbox_example]" <?php// checked( $value, 'on' ); ?>> <?php// esc_html_e( 'Checkbox example description.', 'advatix-fep-plugin' ); ?>
                            </td>
                        </tr-->
    
                        <?php // Text input example ?>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Enter API Key', 'advatix-fep-plugin' ); ?></th>
                            <td>
                                <?php $value = self::get_theme_option( 'input_api_key' ); ?>
                                <input class="large-text" type="text" name="theme_options[input_api_key]" value="<?php echo esc_attr( $value ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Enter API Base Url', 'advatix-fep-plugin' ); ?></th>
                            <td>
                                <?php $value = self::get_theme_option( 'input_api_url' ); ?>
                                <input class="regular-text" type="text" placeholder="https://xyz.xpdel.com" name="theme_options[input_api_url]" value="<?php echo esc_attr( $value ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Enter Account ID', 'advatix-fep-plugin' ); ?></th>
                            <td>
                                <?php $value = self::get_theme_option( 'account_id' ); ?>
                                <input class="regular-text" type="text" name="theme_options[account_id]" value="<?php echo esc_attr( $value ); ?>">
                            </td>
                        </tr>
                        <!-- <tr valign="top">
                            <th scope="row"><?php esc_html_e( 'Enter Program ID', 'advatix-fep-plugin' ); ?></th>
                            <td>
                                <?php $value = self::get_theme_option( 'program_id' ); ?>
                                <input class="regular-text" type="text" name="theme_options[program_id]" value="<?php echo esc_attr( $value ); ?>">
                            </td>
                        </tr> -->
    
                    </table><hr>
    
                    <?php submit_button(); ?>
    
                </form>
    
            </div><!-- .wrap -->
        <?php }
	}
}
new ADVATIX_FEP_PLUGIN();

// Helper function to use in your theme to return a theme option value
function advatix_api_option( $id = '' ) {
	return ADVATIX_FEP_PLUGIN::get_theme_option( $id );
}


add_action('woocommerce_new_order', function ($order_id, $order) {
    global $wpdb;
    //echo '<script>alert('.$order_id.')</script>';

    // $order = wc_get_order( $order_id );
    // $order_id  = "3979580080188"; // Get the order ID
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

    $refID = rand('10000','99999');
    $base_url = advatix_api_option('input_api_url');
    $url = $base_url.'/fep/api/v1/order/createOrder';

    $accountID = advatix_api_option('account_id');
    $api_key = advatix_api_option('input_api_key');
    $refID = "$refID";
    $order_id = "$order_id";
    $order_total = "$order_total";
    
    $data = array(
        "accountId" => $accountID,
        "referenceId" => $order_id,
        "orderNumber" => $order_id,
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
        "companyName" => "",
        "cxPhone" => $billing_phone,
        "cxEmail" => $user->user_email,
        "beginDate" => $date_created,
        "totalWeight" => "0.26235009178",
        "totalAmount" => $order_total,
        "notification" => false,
        "lob" => "9",
        "d2cOrder" => false,
        // "orderItems" => [array("sku" => "SIP-OTH-TIKITUB","quantity" => 1,"price" => 25), array("sku" => "MIR-GLA-SANTA16-12","quantity" => 1,"price" => 25), array("sku" => "MIR-DNCG-SNT","quantity" => 1,"price" => 25)]
        "orderItems" => $orderItems
    );


    $postdata = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('ApiKey: '.$api_key.'','Ver: 1.0','Device-Type: Web','Content-Type: application/json'));
    $result = curl_exec($ch);

    $result_jd = json_decode($result);
    
	$table_name = $wpdb->prefix . 'fep_api_order_resp';
	$wpdb->insert( 
		$table_name, 
		array( 
            'requestJson' => $postdata,
			'responseMessage' => $result_jd->responseMessage,
            'responseStatus' => $result_jd->responseStatus,
            'responseStatusCode' => $result_jd->responseStatusCode,
            'responseObject' => $result_jd->responseObject,
			'time' => current_time( 'mysql' ), 
		) 
	);

    curl_close($ch);

}, 1, 2);


function fep_install() {
	global $wpdb;
    $table_name = $wpdb->base_prefix.'fep_api_order_resp';
    $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );

    if ( ! $wpdb->get_var( $query ) == $table_name ) {
        
        // global $jal_db_version;
        // $table_name = $wpdb->prefix . 'liveshoutbox';
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            requestJson varchar(5000) DEFAULT NULL,
            responseMessage varchar(5000) DEFAULT NULL,
            responseStatus varchar(200) DEFAULT NULL,
            responseStatusCode varchar(200) DEFAULT NULL,
            responseObject varchar(200) DEFAULT NULL,
            time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
	//add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'fep_install' );


function adv_remove__status( $statuses ){
	if( isset( $statuses['wc-processing'] ) ){
		unset( $statuses['wc-processing'] );
	}
	if( isset( $statuses['wc-pending'] ) ){
		unset( $statuses['wc-pending'] );
	}
	if( isset( $statuses['wc-on-hold'] ) ){
		unset( $statuses['wc-on-hold'] );
	}
	if( isset( $statuses['wc-completed'] ) ){
		unset( $statuses['wc-completed'] );
	}
	if( isset( $statuses['wc-refunded'] ) ){
		unset( $statuses['wc-refunded'] );
	}
	return $statuses;
}
add_filter( 'wc_order_statuses', 'adv_remove__status' );


// Register new status
function adv_register_created_order_status() {
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
}
add_action( 'init', 'adv_register_created_order_status' );

// Add to list of WC Order statuses
function adv_add_created_to_order_statuses( $order_statuses ) {
 
	$new_order_statuses = array();
 
	// add new order status after processing
	// foreach ( $order_statuses as $key => $status ) {
 
		// $new_order_statuses[ $key ] = $status;
 
		$new_order_statuses['wc-created'] = 'Created';
		$new_order_statuses['wc-assigned'] = 'Assigned';
		$new_order_statuses['wc-picked'] = 'Picked';
		$new_order_statuses['wc-packaging'] = 'Packaging';
		$new_order_statuses['wc-shipped'] = 'Shipped';
		$new_order_statuses['wc-delivered'] = 'Delivered';
		$new_order_statuses['wc-cancelled'] = 'Cancelled';
		$new_order_statuses['wc-failed'] = 'Failed';
		
	// }
 
	return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'adv_add_created_to_order_statuses' );

add_action( 'woocommerce_checkout_order_processed', 'adv_changing_order_status_before_payment', 10, 3 );
function adv_changing_order_status_before_payment( $order_id, $posted_data, $order ){
	$order->update_status( 'created' );
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'advatix-fep-plugin/v1', '/updateOrder', array(
    'methods' => 'POST',
    'callback' => 'adv_update_order',
  ) );
} );

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
?>
