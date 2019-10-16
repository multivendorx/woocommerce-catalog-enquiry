<?php
/**
 * Plugin Name: WC Catalog Enquiry
 * Plugin URI: https://wc-marketplace.com/
 * Description: Convert your WooCommerce store into a catalog website in a click
 * Author: WC Marketplace, The Grey Parrots 
 * Version: 3.2.1
 * Author URI: https://wc-marketplace.com/
 * WC requires at least: 3.0
 * WC tested up to: 3.6.4
 * Text Domain: woocommerce-catalog-enquiry
 * Domain Path: /languages/
*/

if ( ! class_exists( 'WC_Dependencies_woocommerce_catalog_enquiry' ) )
	require_once trailingslashit(dirname(__FILE__)).'includes/class-dc-dependencies.php';
require_once trailingslashit(dirname(__FILE__)).'includes/wc-Woocommerce-Catalog-Enquiry-core-functions.php';
require_once trailingslashit(dirname(__FILE__)).'catalog_config.php';
if(!defined('ABSPATH')) exit; // Exit if accessed directly
if(!defined('WC_WOOCOMMERCE_CATALOG_ENQUIRY_PLUGIN_TOKEN')) exit;
if(!defined('WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN')) exit;
if(!WC_Dependencies_woocommerce_catalog_enquiry::woocommerce_active_check()) {
  add_action( 'admin_notices', 'woocommerce_catalog_enquiry_alert_notice' );
}
/**
* Plugin page links
*/
function woocommerce_catalog_enquiry_plugin_links( $links ) {	
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-Woocommerce-Catalog-Enquiry-setting-admin' ) . '">' . __( 'Settings', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>',
		'<a href="http://dualcube.com/">' . __( 'Support', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>',			
	);	
	$links = array_merge( $plugin_links, $links );
        $links[] = '<a href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank">' . __( 'Upgrade to Pro', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>';
        return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'woocommerce_catalog_enquiry_plugin_links' );

add_filter( 'plugin_row_meta', 'plugin_row_meta', 10, 2 );

function plugin_row_meta( $links, $file ) {
    if($file == 'woocommerce-catalog-enquiry/Woocommerce_Catalog_Enquiry.php'){
        $row_meta = array(
            'pro'    => '<a href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" title="' . esc_attr( __( 'Upgrade to Pro', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) ) . '">' . __( 'Upgrade to Pro', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ) . '</a>'
        );
        return array_merge( $links, $row_meta );
    }else{
        return $links;
    }
}

if(!WC_Dependencies_woocommerce_catalog_enquiry::woocommerce_catalog_enquiry_pro_active_check()) {
	if(!class_exists('WC_Woocommerce_Catalog_Enquiry')) {
		require_once( trailingslashit(dirname(__FILE__)).'classes/class-wc-Woocommerce-Catalog-Enquiry.php' );
		global $WC_Woocommerce_Catalog_Enquiry;
		$WC_Woocommerce_Catalog_Enquiry = new WC_Woocommerce_Catalog_Enquiry( __FILE__ );
		$GLOBALS['WC_Woocommerce_Catalog_Enquiry'] = $WC_Woocommerce_Catalog_Enquiry;
	}
}

