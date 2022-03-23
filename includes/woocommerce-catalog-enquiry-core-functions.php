<?php

if(!function_exists('woocommerce_catalog_enquiry_alert_notice')) {
	 function woocommerce_catalog_enquiry_alert_notice() {
    ?>
    <div id="message" class="error">
      <p><?php printf( __( '%sWoocommerce Catalog Enquiry is inactive.%s The %sWooCommerce plugin%s must be active for the Woocommerce Catalog Enquiry to work. Please %sinstall & activate WooCommerce%s', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
    </div>
		<?php
  }
}


if(!function_exists('woocommerce_catalog_enquiry_validate_color_hex_code')) {
    function woocommerce_catalog_enquiry_validate_color_hex_code($code) {
        $color = str_replace( '#', '', $code );
        return '#'.$color;
    }
}

// Old version to new migration
if(!function_exists('woocommerce_catalog_enquiry_option_migration_3_to_4')) {

  function woocommerce_catalog_enquiry_option_migration_3_to_4() {
    global $Woocommerce_Catalog_Enquiry;
    
    if( !get_option( 'woocommerce_catalog_migration_completed' ) ) :

    // Old catalog button data
    $woocommerce_catalog_old_button = get_option( 'dc_wc_Woocommerce_Catalog_Enquiry_button_settings_name', true );
    
    // Old catalog general data
    $woocommerce_catalog_old_options = get_option('dc_wc_Woocommerce_Catalog_Enquiry_general_settings_name', true );

    // Old catalog exclusion data
    $woocommerce_catalog_old_exclusion = get_option('dc_wc_Woocommerce_Catalog_Enquiry_exclusion_settings_name', true ); 
    if ( !empty( $woocommerce_catalog_old_exclusion ) ) {

      $update_new_exclution = array();
      foreach ($woocommerce_catalog_old_exclusion as $key => $value) {
        if ( $key == 'myuserroles_list' ) {
          $update_new_exclution['woocommerce_userroles_list'] = $value;
        }
        if ( $key == 'myuser_list' ) {
          $update_new_exclution['woocommerce_user_list'] = $value;
        }
        if ( $key == 'myproduct_list' ) {
          $update_new_exclution['woocommerce_product_list'] = $value;
        }
        if ( $key == 'mycategory_list' ) {
          $update_new_exclution['woocommerce_category_list'] = $value;
        }
      }
      update_option( 'woocommerce_catalog_enquiry_exclusion_settings', $update_new_exclution );
    }

    // New catalog button data
    if( !empty( $woocommerce_catalog_old_button ) ) {
      update_option( 'woocommerce_catalog_enquiry_button_appearence_settings', $woocommerce_catalog_old_button );
    }

    if( !empty( $woocommerce_catalog_old_options ) ) {
      
      // Old catalog general data
      update_option( 'woocommerce_catalog_enquiry_general_settings', $woocommerce_catalog_old_options );

      // name
      if( isset( $woocommerce_catalog_old_options['name_label'] ) && $woocommerce_catalog_old_options['name_label'] != ''  ){

        $woocommerce_catalog_old_options['form_name'] = array( 'label' => $woocommerce_catalog_old_options['form_name'] );
      }
      // Email
      if( isset( $woocommerce_catalog_old_options['email_label'] ) && $woocommerce_catalog_old_options['email_label'] != ''  ){

        $woocommerce_catalog_old_options['form_email'] = array( 'label' => $woocommerce_catalog_old_options['form_email'] );
      }
      // File upload limit
      if( isset( $woocommerce_catalog_old_options['filesize_limit'] ) && $woocommerce_catalog_old_options['filesize_limit'] != ''  ){

        $woocommerce_catalog_old_options['filesize_limit'] = array( 'label' => $woocommerce_catalog_old_options['filesize_limit'] );
      }

      // Subject
      if( isset($woocommerce_catalog_old_options['is_subject']) &&  $woocommerce_catalog_old_options['is_subject'] == 'Enable' && isset( $woocommerce_catalog_old_options['subject_label'] ) && $woocommerce_catalog_old_options['subject_label'] != ''  ){

        $woocommerce_catalog_old_options['form_subject'] = array( 'label' => $woocommerce_catalog_old_options['subject_label'], 'is_enable' => 'Enable' );
      }

      // phone
      if( isset($woocommerce_catalog_old_options['is_phone']) &&  $woocommerce_catalog_old_options['is_phone'] == 'Enable' && isset( $woocommerce_catalog_old_options['phone_label'] ) && $woocommerce_catalog_old_options['phone_label'] != ''  ){

        $woocommerce_catalog_old_options['form_phone'] = array( 'label' => $woocommerce_catalog_old_options['phone_label'], 'is_enable' => 'Enable' );
      }

      // Address
      if( isset($woocommerce_catalog_old_options['is_address']) &&  $woocommerce_catalog_old_options['is_address'] == 'Enable' && isset( $woocommerce_catalog_old_options['address_label'] ) && $woocommerce_catalog_old_options['address_label'] != ''  ){
        $woocommerce_catalog_old_options['form_address'] = array( 'label' => $woocommerce_catalog_old_options['address_label'], 'is_enable' => 'Enable' );
      }

      // comment
      if( isset($woocommerce_catalog_old_options['is_comment']) &&  $woocommerce_catalog_old_options['is_comment'] == 'Enable' && isset( $woocommerce_catalog_old_options['comment_label'] ) && $woocommerce_catalog_old_options['comment_label'] != ''  ){
        $woocommerce_catalog_old_options['form_comment'] = array( 'label' => $woocommerce_catalog_old_options['comment_label'], 'is_enable' => 'Enable' );
      }

      // file upload
      if( isset($woocommerce_catalog_old_options['is_fileupload']) &&  $woocommerce_catalog_old_options['is_fileupload'] == 'Enable' && isset( $woocommerce_catalog_old_options['fileupload_label'] ) && $woocommerce_catalog_old_options['fileupload_label'] != ''  ){
        $woocommerce_catalog_old_options['form_fileupload'] = array( 'label' => $woocommerce_catalog_old_options['fileupload_label'], 'is_enable' => 'Enable' );
      }

      // Capta label
      if( isset($woocommerce_catalog_old_options['is_captcha']) &&  $woocommerce_catalog_old_options['is_captcha'] == 'Enable' && isset( $woocommerce_catalog_old_options['captcha_label'] ) && $woocommerce_catalog_old_options['captcha_label'] != ''  ){
        $woocommerce_catalog_old_options['form_captcha'] = array( 'label' => $woocommerce_catalog_old_options['captcha_label'], 'is_enable' => 'Enable' );
      }

      update_option( 'woocommerce_catalog_enquiry_from_settings', $woocommerce_catalog_old_options );

    }

    // By default set
    $general_settings = get_option( 'woocommerce_catalog_enquiry_general_settings' );
    if( is_array( $general_settings ) && !empty( $general_settings ) ){
      $general_settings['for-user-type'] = 2;
      update_option( 'woocommerce_catalog_enquiry_general_settings', $general_settings );
    } else {
      $general_settings = array();
      $general_settings['for-user-type'] = 2;
      update_option( 'woocommerce_catalog_enquiry_general_settings', $general_settings );
    }

    // set button type
    $button_settings = get_option( 'woocommerce_catalog_enquiry_button_appearence_settings' );
    if( is_array( $button_settings ) &&  !empty( $button_settings ) ){
      $button_settings['button_type'] = 1;
      update_option( 'woocommerce_catalog_enquiry_button_appearence_settings', $button_settings );
    } else {
      $button_settings = array();
      $button_settings['button_type'] = 1;
      update_option( 'woocommerce_catalog_enquiry_button_appearence_settings', $button_settings );
    }

    delete_option( 'dc_wc_Woocommerce_Catalog_Enquiry_general_settings_name' );
    delete_option( 'dc_wc_Woocommerce_Catalog_Enquiry_button_settings_name' );
    delete_option( 'dc_wc_Woocommerce_Catalog_Enquiry_exclusion_settings_name' );

    update_option( 'woocommerce_catalog_migration_completed', 'migrated' );
    endif;
  }
}

// find all wp users
if(!function_exists('woocommerce_catalog_wp_users')) {
  
  function woocommerce_catalog_wp_users(){
    $users = get_users();
    $all_users = array();
    foreach($users as $user) {                  
      $all_users[$user->data->ID] = $user->data->display_name;
    }
    return $all_users;
  }
}

// find all woocommerce product
if(!function_exists('woocommerce_catalog_products')) {
  
  function woocommerce_catalog_products() {
    $args = apply_filters('woocommerce_catalog_limit_backend_product', array( 'posts_per_page' => -1, 'post_type' => 'product', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' ));
    $woocommerce_product = get_posts( $args );
    $all_products = array();
    foreach ( $woocommerce_product as $post => $value ){
      $all_products[$value->ID] = $value->post_title;     
    }
    return $all_products;
  }
}
// find all product caegory
if(!function_exists('woocommerce_catalog_product_category')) {
  function woocommerce_catalog_product_category() { 
    $all_product_cat = array();
    $args = array( 'orderby' => 'name', 'order' => 'ASC' );
    $terms = get_terms( 'product_cat', $args );
    foreach ( $terms as $term) {
      $all_product_cat[$term->term_id] = $term->name;
    }
    return $all_product_cat;
  }
}

// Get all woocommerce pages
if(!function_exists('woocommerce_catalog_wp_pages')) {
  
  function woocommerce_catalog_wp_pages() {
    $args = array( 'posts_per_page' => -1, 'post_type' => 'page', 'orderby' => 'title', 'order' => 'ASC' );
        $wp_posts = get_posts( $args );
        foreach ( $wp_posts as $post ) : setup_postdata( $post );    
        $page_array[$post->ID] = $post->post_title;       
        endforeach; 
        wp_reset_postdata();
    return $page_array;
  }
}