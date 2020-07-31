<?php
if(!function_exists('get_Woocommerce_Catalog_Enquiry_settings')) {
  function get_Woocommerce_Catalog_Enquiry_settings($name = '', $tab = '') {
    if(empty($tab) && empty($name)) return '';
    if(empty($tab)) return get_option($name);
    if(empty($name)) return get_option("dc_{$tab}_settings_name");
    $settings = get_option("dc_{$tab}_settings_name");
    if(!isset($settings[$name])) return '';
    return $settings[$name];
  }
}


if(!function_exists('woocommerce_catalog_enquiry_alert_notice')) {
	 function woocommerce_catalog_enquiry_alert_notice() {
    ?>
    <div id="message" class="error">
      <p><?php printf( __( '%sWoocommerce Catalog Enquiry is inactive.%s The %sWooCommerce plugin%s must be active for the Woocommerce Catalog Enquiry to work. Please %sinstall & activate WooCommerce%s', WC_WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
    </div>
		<?php
  }
}


if(!function_exists('wce_validate_color_hex_code')) {
    function wce_validate_color_hex_code($code) {
        $color = str_replace( '#', '', $code );
        return '#'.$color;
    }
}

if(!function_exists('woo_catalog_option_migration')) {
    function woo_catalog_option_migration(){
        global $WC_Woocommerce_Catalog_Enquiry;
        $woo_catalog_old_button = get_option( 'dc_wc_Woocommerce_Catalog_Enquiry_button_settings_name', true );
        $woo_catalog_old_options = get_option('dc_wc_Woocommerce_Catalog_Enquiry_general_settings_name', true ); 

        // from settings
        foreach ( array_slice($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options(),55,19) as $key_from => $value_from) {
            if( $value_from['type'] != 'section' ){
                $general_setting_from['ids'][] = $value_from['id'];
                $general_setting_from['setting'] = 'dc_wc_Woocommerce_Catalog_from_settings_name';

            }
        }

        $from_enquiry_data = array();
        foreach ($woo_catalog_old_options as $key_from => $value_from) {
            if( in_array($key_from, $general_setting_from['ids']) ){
                $from_enquiry_data[$key_from] = $value_from;
                update_option( $general_setting_from['setting'], $from_enquiry_data );
            }
        }


        // general setting  
        foreach (array_slice($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options(),4,20) as $key => $value) {
            if( $value['type'] != 'section' ){
                $general_setting_ids['ids'][] = $value['id'];
                $general_setting_ids['setting'] = 'dc_wc_Woocommerce_Catalog_general';

            }
        }

        $general_apperarence_data = array();
        foreach ($woo_catalog_old_options as $key_button => $value_button) {
            if( in_array($key_button, $general_setting_ids['ids']) ){
                $general_apperarence_data[$key_button] = $value_button;
                update_option( $general_setting_ids['setting'], $general_apperarence_data );
            }
        }

        // Button appearence  
        foreach (array_slice($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options(),24,21) as $key => $value) {
            if( $value['type'] != 'section' ){
                $button_appeares_ids['ids'][] = $value['id'];
                $button_appeares_ids['setting'] = 'dc_wc_Woocommerce_Catalog_button_appear';

            }
        }

        $button_apperarence_data = array();
        foreach ($woo_catalog_old_button as $key_button => $value_button) {
            if( in_array($key_button, $button_appeares_ids['ids']) ){
                $button_apperarence_data[$key_button] = $value_button;
                update_option( 'dc_wc_Woocommerce_Catalog_button_appear', $button_apperarence_data );

            }
        }
    }
}



  // find all users
if(!function_exists('wp_all_users')) {

  function wp_all_users(){
    $users = get_users();
    $all_users = array();
    foreach($users as $user) {                  
      $all_users[$user->data->ID] = $user->data->display_name;
    }
    return $all_users;
  }
}
 
if(!function_exists('get_all_products')) {
  // find all product
  function get_all_products() {
    $args = array( 'posts_per_page' => -1, 'post_type' => 'product', 'orderby' => 'title', 'order' => 'ASC' );
    $woo_product = get_posts( $args );
    $all_products = array();
    foreach ( $woo_product as $post => $value ){
      $all_products[$value->ID] = $value->post_title;     
    }
    return $all_products;
  }
}

if(!function_exists('get_all_product_category')) {

  // find all product caegory
  function get_all_product_category() { 
    $all_product_cat = array();
    $args = array( 'orderby' => 'name', 'order' => 'ASC' );
    $terms = get_terms( 'product_cat', $args );
    foreach ( $terms as $term) {
      $all_product_cat[$term->term_id] = $term->name;
    }
    return $all_product_cat;
  }
}

if(!function_exists('get_all_pages')) {
  // gte all pages
  function get_all_pages() {
    $args = array( 'posts_per_page' => -1, 'post_type' => 'page', 'orderby' => 'title', 'order' => 'ASC' );
        $wp_posts = get_posts( $args );
        foreach ( $wp_posts as $post ) : setup_postdata( $post );    
        $page_array[$post->ID] = $post->post_title;       
        endforeach; 
        wp_reset_postdata();
    return $page_array;
  }
}