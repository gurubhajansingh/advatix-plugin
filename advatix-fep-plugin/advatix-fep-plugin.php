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
		public static function create_admin_page() { ?>

            <div class="wrap">
    
                <h1><?php esc_html_e( 'Advatix Fep API Settings', 'advatix-fep-plugin' ); ?></h1><hr>
    
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


add_action('woocommerce_new_order', function ($order_id) {
    global $wpdb;
    //echo '<script>alert('.$order_id.')</script>';

    $order = wc_get_order( $order_id );
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
        "referenceId" => $refID,
        "orderNumber" => $order_id,
        "orderType" => "6",
        "addressType" => "Residential",
        "shipToName" => $shipping_first_name.' '.$shipping_last_name,
        "shipToAddress" => $shipping_address_1.' '.$shipping_address_2,
        "shipToCity" => $shipping_city,
        "shipToCountry" => "USA",
        "shipToEmail" => $billing_email,
        "shipToMobile" => $billing_phone,
        "shipToState" => $shipping_state,
        "postalCode" => $shipping_postcode,
        "billToName" => $billing_first_name.' '.$billing_last_name,
        "billToAddress" => $billing_address_1.' '.$billing_address_2,
        "billToCity" => $billing_city,
        "billToState" => $billing_state,
        "billToPostal" => $billing_postcode,
        "billToCountry" => "USA",
        "billToMobile" => $billing_phone,
        "billToEmail" => $billing_email,
        "addtionalCharges" => 0,
        "paymentMode" => 1,
        "paymentStatus" => 0,
        "deliveryTargetDate" => "09-01-2021",
        "companyName" => "Amazon",
        "cxPhone" => $billing_phone,
        "cxEmail" => $user->user_email,
        "beginDate" => $date_created,
        "totalWeight" => "0.26235009178",
        "totalAmount" => $order_total,
        "notification" => false,
        "lob" => "3",
        "d2cOrder" => false,
        "orderItems" => [array("sku" => "20ml Discovery (x3)","quantity" => 1,"price" => 25)],
        "tags" => "113-9960202-4769850, Amazon, Amazon USA, Amazon.com"
    );

    // $postdata = json_encode($arrdata);
    // $data = array(
	// 		"accountId" => $accountID,
	// 		"referenceId" => $refID,
	// 		"orderNumber" => "3979580080187",
	// 		"orderType" => "6",
	// 		"addressType" => "Residential",
	// 		"shipToName" => "Beau  Tattersall",
	// 		"shipToAddress" => "2500 TURK BLVD ROOM # 1403",
	// 		"shipToCity" => "SAN FRANCISCO",
	// 		"shipToCountry" => "USA",
	// 		"shipToEmail" => "chj8n4yhh9508bk@trash.mp.common-services.com",
	// 		"shipToMobile" => "000-000-0000",
	// 		"shipToState" => "CA",
	// 		"postalCode" => "94118-4392",
	// 		"billToName" => "Beau  Tattersall",
	// 		"billToAddress" => "2500 TURK BLVD ROOM # 1403",
	// 		"billToCity" => "SAN FRANCISCO",
	// 		"billToState" => "CA",
	// 		"billToPostal" => "94118-4392",
	// 		"billToCountry" => "USA",
	// 		"billToMobile" => "000-000-0000",
	// 		"billToEmail" => "chj8n4yhh9508bk@trash.mp.common-services.com",
	// 		"addtionalCharges" => 0,
	// 		"paymentMode" => 1,
	// 		"paymentStatus" => 0,
	// 		"deliveryTargetDate" => "09-01-2021",
	// 		"companyName" => "Amazon",
	// 		"cxPhone" => "516-530-9111",
	// 		"cxEmail" => "info@cxEmail.com",
	// 		"beginDate" => "2021-08-31T20:32:26-04:00",
	// 		"totalWeight" => "0.26235009178",
	// 		"totalAmount" => "27.16",
	// 		"notification" => false,
	// 		"lob" => "3",
	// 		"d2cOrder" => false,
	// 		"orderItems" => [array("sku" => "20ml Discovery (x3)","quantity" => 1,"price" => 25)],
	// 		"tags" => "#113-9960202-4769850, Amazon, Amazon USA, Amazon.com"
	// 	);

    $postdata = json_encode($data);
    // echo '<script>alert('.$postdata.')</script><br/>';
    // die;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, '{"accountId":"'.$accountID.'","referenceId":"'.$refID.'","orderNumber":"'.$order_id.'","orderType":"6","addressType":"Residential","shipToName":"'.$shipping_first_name.' '.$shipping_last_name.'","shipToAddress":"'.$shipping_address_1.' '.$shipping_address_2.'","shipToCity":"'.$shipping_city.'","shipToCountry":"'.$shipping_country.'","shipToEmail":"'.$billing_email.'","shipToMobile":"'.$billing_phone.'","shipToState":"'.$shipping_state.'","postalCode":"'.$shipping_postcode.'","billToName":"'.$billing_first_name.' '.$billing_last_name.'","billToAddress":"'.$billing_address_1.' '.$billing_address_2.'","billToCity":"'.$billing_city.'","billToState":"'.$billing_state.'","billToPostal":"'.$billing_postcode.'","billToCountry":"'.$billing_country.'","billToMobile":"'.$billing_phone.'","billToEmail":"'.$billing_email.'","addtionalCharges":0,"paymentMode":1,"paymentStatus":0,"deliveryTargetDate":"09-01-2021","companyName":"'.$billing_company .'","cxPhone":"'.$billing_phone.'","cxEmail":"'.$user->user_email.'","beginDate":"'.$date_created.'","totalWeight":"0.26235009178","totalAmount":"'.$order_total.'","notification":false,"lob":"3","d2cOrder":false,"orderItems":[{"sku":"20ml Discovery (x3)","quantity":1,"price":25}],"tags":"#113-9960202-4769850, Amazon, Amazon USA, Amazon.com"}');
    // curl_setopt($ch, CURLOPT_POSTFIELDS, '\{"accountId":"Noshinku","referenceId":"15249","orderNumber":"3979580080196","orderType":"6","addressType":"Residential","shipToName":"Beau  Tattersall","shipToAddress":"2500 TURK BLVD ROOM # 1403","shipToCity":"SAN FRANCISCO","shipToCountry":"USA","shipToEmail":"chj8n4yhh9508bk@trash.mp.common-services.com","shipToMobile":"000-000-0000","shipToState":"CA","postalCode":"94118-4392","billToName":"Beau  Tattersall","billToAddress":"2500 TURK BLVD ROOM # 1403","billToCity":"SAN FRANCISCO","billToState":"CA","billToPostal":"94118-4392","billToCountry":"USA","billToMobile":"000-000-0000","billToEmail":"chj8n4yhh9508bk@trash.mp.common-services.com","addtionalCharges":0,"paymentMode":1,"paymentStatus":0,"deliveryTargetDate":"09-01-2021","companyName":"Amazon","cxPhone":"516-530-9111","cxEmail":"info@cxEmail.com","beginDate":"2021-08-31T20:32:26-04:00","totalWeight":"0.26235009178","totalAmount":"27.16","notification":false,"lob":"3","d2cOrder":false,"orderItems":[{"sku":"20ml Discovery (x3)","quantity":1,"price":25}],"tags":"#113-9960202-4769850, Amazon, Amazon USA, Amazon.com"}');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('ApiKey: '.$api_key.'','Ver: 1.0','Device-Type: Web','Content-Type: application/json'));
    $result = curl_exec($ch);

    echo '<script>alert('.$result.')</script><br/>';
    $result_jd = json_decode($result);
    
    echo '<script>alert('.$result_jd->responseStatusCode.')</script><br/>';
    
    // echo '<pre>'.print_r('<script>alert('.$result_jd.')</script>').'</pre>';
    
    // echo '<script>alert('.curl_error($ch).')</script>';
    
	$table_name = $wpdb->prefix . 'fep_api_order_resp';
	$wpdb->insert( 
		$table_name, 
		array( 
            'responseMessage' => $result_jd->responseMessage,
            'responseStatus' => $result_jd->responseStatus,
            'responseStatusCode' => $result_jd->responseStatusCode,
            'responseObject' => $result_jd->responseObject,
			'time' => current_time( 'mysql' ), 
		) 
	);

    curl_close($ch);

}, 10, 1);


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
            responseMessage varchar(200) DEFAULT NULL,
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
?>
