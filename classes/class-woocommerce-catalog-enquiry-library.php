<?php
class Woocommerce_Catalog_Enquiry_Library {
  
    public $lib_url;  
    public $jquery_lib_url;

    public function __construct() {
        
        global $Woocommerce_Catalog_Enquiry;
        $this->lib_url = $Woocommerce_Catalog_Enquiry->plugin_url . 'lib/';
        $this->jquery_lib_url = $this->lib_url . 'jquery/';
    
    }

       /**
     * Jquery qTip library
     */
    public function load_qtip_lib() {
      global $Woocommerce_Catalog_Enquiry;
      wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);
        wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $Woocommerce_Catalog_Enquiry->version);
    }

    /**
     * Select2 library
     */
    public function load_select2_lib() {
        global $Woocommerce_Catalog_Enquiry;
        wp_enqueue_script('select2_js', $this->jquery_lib_url . 'select2/select2.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);
        wp_enqueue_style('select2_css', $this->jquery_lib_url . 'select2/select2.css', array(), $Woocommerce_Catalog_Enquiry->version);
    }

    
    /**
     * WP Media library
     */
    public function load_upload_lib() {
      global $Woocommerce_Catalog_Enquiry;
      wp_enqueue_media();
      wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);
      wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $Woocommerce_Catalog_Enquiry->version);
    }
    
    /**
     * WP ColorPicker library
     */
    public function load_colorpicker_lib() {
      global $Woocommerce_Catalog_Enquiry;
      wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script( 'colorpicker_init', $this->jquery_lib_url . 'colorpicker/colorpicker.js', array( 'jquery', 'wp-color-picker' ), $Woocommerce_Catalog_Enquiry->version, true );
    wp_enqueue_style( 'wp-color-picker' );
    }
    
    /**
     * WP DatePicker library
     */
    public function load_datepicker_lib() {
      global $Woocommerce_Catalog_Enquiry;
      wp_enqueue_script('jquery-ui-datepicker');
      $this->load_jqueryui_lib();
    }


    /**
     * Load JqueryUI library
     */
    public function load_jqueryui_lib() {
      global $wp_scripts;
      if(wp_style_is( 'jquery-ui-style', 'registered' )){
        wp_enqueue_style( 'jquery-ui-style' );
      }else{
        $jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.11.4';
        wp_register_style( 'jquery-ui-style', '//code.jquery.com/ui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css', array(), $jquery_version );
        wp_enqueue_style( 'jquery-ui-style' );
      }
    }

    public function catalog_enquiry_get_options(){
    /**
     * Create new menus
     */

    $woocommerce_catalog_options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_menu_page",
        "page_name" => __( "catelog", 'woocommerce-catalog-enquiry' ),
        "menu_slug" => "woo-catalog",
        "layout" => "2-col"
    );

    /**
     * Settings Tab
     */
    $woocommerce_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo-catalog-general",
        "label" => __( "General", 'woocommerce-catalog-enquiry' ),
        "font_class" => "dashicons-admin-generic"
    );

    // setting box
    $woocommerce_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "woocommerce_catalog_enquiry_general_settings",
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp-catalog-license",
        "label" => __( "Common Settings", 'woocommerce-catalog-enquiry' ),
    );

    // Catalog Mode
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-enable",
        "name" => "is_enable",
        "label" => __( "Catalog Mode", 'woocommerce-catalog-enquiry' ),
        "desc" => apply_filters( 'woocommerce_catalog_enquiry_enable_catalog_text', __('Enable this to activate catalog mode sitewide. This will remove your Add to Cart button. To keep Add to Cart button in your site, upgrade to  <a href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank">WooCommerce Catalog Enquiry Pro</a>.', 'woocommerce-catalog-enquiry', 'woocommerce-catalog-enquiry') ),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );
    // Enable product enquiry button
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-enable-enquiry",
        "name" => "is_enable_enquiry",
        "label" => __( "Product Enquiry Button", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this to add the Enquiry button for all products. Use Exclusion settings to exclude specific product or category from enquiry.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    //Enable when product is out of stock
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-enable-out-of-stock",
        "name" => "is_enable_out_of_stock",
        "label" => __( "Product Enquiry Button When Product is Out Of Stock", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this to add the Enquiry button for the products which is out of stock. Use Exclusion settings to exclude specific product or category from enquiry.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    // for user type
    $woocommerce_catalog_options[ ] = array(
        "type" => "select",
        "id" => "for-user-type",
        "name" => "for_user_type",
        "desc" => __('Select the type users where this catalog is applicable', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Catalog Mode Applicable For", 'woocommerce-catalog-enquiry' ),
        "option_values" => array('1' => 'Only Logged out Users', '2' => 'Only Logged in Users', '3' => 'All Users'), 'hints' => __('Method applicable for only secleted user group default all.', 'woocommerce-catalog-enquiry'),   'desc' => __('Select the user type where this catalog is applicable.', 'woocommerce-catalog-enquiry'
            )
    );

    // displayt cart and checkout page
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-hide-cart-checkout",
        "name" => "is_hide_cart_checkout",
        "label" => __( "Disable Cart and Checkout Page?", 'woocommerce-catalog-enquiry' ),
        "desc" => apply_filters( 'woocommerce_catalog_enquiry_hide_cart', __('Enable this to redirect user to home page, if they click on the cart or checkout page. To set the redirection to another page kindly upgrade to <a href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank">WooCommerce Catalog Enquiry Pro</a>.', 'woocommerce-catalog-enquiry') ),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );


    $woocommerce_catalog_options[ ] = array(
        "type" => "select",
        "id" => "disable-cart-page-link",
        "name" => "disable_cart_page_link",
        "label" => __( "Set Redirect Page", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Select page where user will be redirected for disable cart page.', 'woocommerce-catalog-enquiry'),
        "option_values" => woocommerce_catalog_wp_pages()
    );

    // Redirect after enquiry success
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-page-redirect",
        "name" => "is_page_redirect",
        "label" => __( "Redirect after Enquiry form Submission", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this to redirect user to another page after successful enquiry submission.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    // set redirect page
    $woocommerce_catalog_options[ ] = array(
        "type" => "select",
        "id" => "redirect-page-id",
        "name" => "redirect_page_id",
        "label" => __( "Set Redirect Page", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Select page where user will be redirected after successful enquiry.', 'woocommerce-catalog-enquiry'),
        "option_values" => woocommerce_catalog_wp_pages()
    );

    /*************************************************/
    // Display Options
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce-catalog-enquiry-general",
        "label" => __( "Display Options", 'woocommerce-catalog-enquiry' ),
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-remove-price-free",
        "name" => "is_remove_price_free",
        "label" => __( "Remove Product Price?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this option to remove the product price display from site.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
     
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-disable-popup",
        "name" => "is_disable_popup",
        "label" => __( "Disable Enquiry form via popup?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('By default the form will be displayed via popup. Enable this, if you want to display the form below the product description.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
     
    );

    

    // Header
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce-catalog-enquiry-header",
        "label" => __( "Enquiry Email Receivers Settings", 'woocommerce-catalog-enquiry' )
    );

    // Additional enquiry receivers
    $woocommerce_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "other-emails",
        "name" => "other_emails",
        "class" => "large-text",
        "label" => __( "Additional Recivers Emails", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter email address if you want to receive enquiry mail along with admin mail. You can add multiple commma seperated emails. Default: Admin emails.", 'woocommerce-catalog-enquiry' ),
    );

    // Remove admin email
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-other-admin-mail",
        "name" => "is_other_admin_mail",
        "label" => __( "Remove admin email", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this if you want remove admin email from reciever list.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    /**
     * Button Appearance Tab
     */
    $woocommerce_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo-catalog-button",
        "label" => __( "Button Appearance", 'woocommerce-catalog-enquiry' ),
        "font_class" => "dashicons-admin-appearance"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "woocommerce_catalog_enquiry_button_appearence_settings"
    );

    // Themes

    // Background
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce-catalog-enquiry-button-appearence",
        "label" => __( "Button Customizer", 'woocommerce-catalog-enquiry' )
    );
    // Enquiry Button label
    $woocommerce_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button-text",
        'name' => "enquiry_button_text",
        "label" => __( "Button Text", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enter the text for your Enquery Button.', 'woocommerce-catalog-enquiry'),
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "select",
        "id" => "button-type",
        "name" => "button_type",
        "desc" => __('Default: Read More.', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Button Type", 'woocommerce-catalog-enquiry' ),
        "option_values" => array('0' => __('Please Select', 'woocommerce-catalog-enquiry'),
         '1' => __('Read More', 'woocommerce-catalog-enquiry'),
         '2' => 'Custom Link For All Products',
         '3' => 'Individual link in all products',
         '4' => 'No Link Just #'
         )
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button-link-catalog",
        "name" => "button_link",
        "label" => __( "Button Link", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Aplicable only when you choose custom link for all products in button type', 'woocommerce-catalog-enquiry'),
    );


    // Your own button style
    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_button",
        "name" => "is_button",
        "label" => __( "Your own button style", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable the custom design for enquiry button.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    // Choose Button Border Color
    $woocommerce_catalog_options[ ] = array(
        "type" => "hidden",
        "id" => "custom_enquiry_buttons_css",
        "name" => "custom_enquiry_buttons_css",
        "label" => __( "Make your own Button Style", 'woocommerce-catalog-enquiry' ),
    );

    
    // Template
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce-catalog-enquiry-template",
        "label" => __( "Additional Settings", 'woocommerce-catalog-enquiry' )
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "textarea",
        "id" => "custom-css-product-page",
        "name" => "custom_css_product_page",
        "class" => "large-text",
        "label" => __( "Custom CSS", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Put your custom css here, to customize the enquiry form.','woocommerce-catalog-enquiry'),
    );

    /**
     * Subscribers Settings Tab
     */
    $woocommerce_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo-catalog-exclusion",
        "label" => __( "Exclusion", 'woocommerce-catalog-enquiry' ),
        "font_class" => "dashicons-unlock"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "woocommerce_catalog_enquiry_exclusion_settings"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce_catalog_enquiry_scripts_subs",
        "label" => __( "Exclusion Management", 'woocommerce-catalog-enquiry' )
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "woo-userroles-list",
        "name" => "woocommerce_userroles_list",
        "desc" => __('Select the user roles, who won’t be able to send enquiry.', 'woocommerce-catalog-enquiry' ),
        "label" => __( "User Role Specific Exclusion", 'woocommerce-catalog-enquiry' ),
        "option_values" => array_keys( wp_roles()->roles )
    );
    // User Role List Excluded from catalog
    $woocommerce_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "woo-user-list",
        "name" => "woocommerce_user_list",
        "desc" => __('Select the users, who won’t be able to send enquiry.', 'woocommerce-catalog-enquiry' ),
        "label" => __( "User Name Specific Exclusion", 'woocommerce-catalog-enquiry' ),
        "option_values" => woocommerce_catalog_wp_users()
    );
    // Product List Excluded from catalog
    $woocommerce_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "woo-product-list",
        "name" => "woocommerce_product_list",
        "desc" => __('Select the products that should have the Add to cart button, instead of enquiry button.', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Product Specific Exclusion", 'woocommerce-catalog-enquiry' ),
        "option_values" => woocommerce_catalog_products()
    );
    // Category List Excluded from catalog
    $woocommerce_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "woo-category-list",
        "name" => "woocommerce_category_list",
        "desc" => __('Select the Category, where should have the Add to cart button, instead of enquiry button.', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Category Specific Exclusion", 'woocommerce-catalog-enquiry' ),
        "option_values" => woocommerce_catalog_product_category()
    );


    /**
     * Enquiry Form Settings Tab
     */
    $woocommerce_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo-catalog-from",
        "label" => __( "Enquiry Form", 'woocommerce-catalog-enquiry' ),
        "font_class" => "dashicons-edit-page"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "woocommerce_catalog_enquiry_from_settings"
    );
 
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce-catalog-enquiry-general-from",
        "label" => __( "General Settings", 'woocommerce-catalog-enquiry' )
    );
    

    $woocommerce_catalog_options[ ] = array(
        "type" => "wpeditor",
        "id" => "top-content-form",
        "name" => "top_content_form",
        "label" => __( "Content Before Enquiry From", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "This content will be displayed above your from.", 'woocommerce-catalog-enquiry' ),
        "class" => "large-text"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "wpeditor",
        "id" => "bottom-content-form",
        "name" => "bottom_content_form",
        "label" => __( "Content After Enquiry From", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "This content will be displayed after your from.", 'woocommerce-catalog-enquiry' ),
        "class" => "large-text"
    );

    $woocommerce_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is-override-form-heading",
        "name" => "is_override_form_heading",
        "label" => __( "Override Form Title?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('By default it will be "Enquiry about PRODUCT_NAME". Enable this to set your custom title.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );
    $woocommerce_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "custom-static-heading",
        "name" => "custom_static_heading",
        "class" => "large-text",
        "label" => __( "Set Form Title", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Set custom from title. Use this specifier to replace the product name - %% PRODUCT_NAME %%.", 'woocommerce-catalog-enquiry' ),
    );


    // Enquiry Form Settings
    $woocommerce_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woocommerce_catalog_enquiry_from_script",
        "label" => __( "Enquiry Form Fields", 'woocommerce-catalog-enquiry' )
    );

    /***************  Body Start  ********************/
    // Capta fileds
    $woocommerce_catalog_options[ ] = array(
        "type" => "table_body",
        "id" => "details_data",
        "label" => __( "", 'woocommerce-catalog-enquiry' ),
        "desc" => __("",''),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
    );

    return apply_filters( 'woocommerce_catalog_enquiry_fileds_options', $woocommerce_catalog_options);
    }
}
