<?php
class WC_Woocommerce_Catalog_Enquiry_Library {
  
  public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $jquery_lib_path;
  
  public $jquery_lib_url;

  public function __construct() {
	global $WC_Woocommerce_Catalog_Enquiry;
	  
	$this->lib_path = $WC_Woocommerce_Catalog_Enquiry->plugin_path . 'lib/';
    $this->lib_url = $WC_Woocommerce_Catalog_Enquiry->plugin_url . 'lib/';
    $this->php_lib_path = $this->lib_path . 'php/';
    $this->php_lib_url = $this->lib_url . 'php/';
    $this->jquery_lib_path = $this->lib_path . 'jquery/';
    $this->jquery_lib_url = $this->lib_url . 'jquery/';
	}
	
	/**
	 * Jquery qTip library
	 */
	public function load_qtip_lib() {
	  global $WC_Woocommerce_Catalog_Enquiry;
	  wp_enqueue_script('qtip_js', $this->jquery_lib_url . 'qtip/qtip.js', array('jquery'), $WC_Woocommerce_Catalog_Enquiry->version, true);
		wp_enqueue_style('qtip_css',  $this->jquery_lib_url . 'qtip/qtip.css', array(), $WC_Woocommerce_Catalog_Enquiry->version);
	}

	/**
     * Select2 library
     */
    public function load_select2_lib() {
        global $WC_Woocommerce_Catalog_Enquiry;
        wp_enqueue_script('select2_js', $this->jquery_lib_url . 'select2/select2.js', array('jquery'), $WC_Woocommerce_Catalog_Enquiry->version, true);
        wp_enqueue_style('select2_css', $this->jquery_lib_url . 'select2/select2.css', array(), $WC_Woocommerce_Catalog_Enquiry->version);
    }

	
	/**
	 * WP Media library
	 */
	public function load_upload_lib() {
	  global $WC_Woocommerce_Catalog_Enquiry;
	  wp_enqueue_media();
	  wp_enqueue_script('upload_js', $this->jquery_lib_url . 'upload/media-upload.js', array('jquery'), $WC_Woocommerce_Catalog_Enquiry->version, true);
	  wp_enqueue_style('upload_css',  $this->jquery_lib_url . 'upload/media-upload.css', array(), $WC_Woocommerce_Catalog_Enquiry->version);
	}
	
	public function catalog_enquiry_get_options(){
    /**
     * Create new menus
     */

    $woo_catalog_options[ ] = array(
        "type" => "menu",
        "menu_type" => "add_menu_page",
        "page_name" => __( "catelog", 'woocommerce-catalog-enquiry' ),
        "menu_slug" => "wcmp_catalog",
        "layout" => "2-col"
    );

    /**
     * Settings Tab
     */
    $woo_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo_catalog_general",
        "label" => __( "General", 'woocommerce-catalog-enquiry' ),
    );

    // setting box
    $woo_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "dc_wc_Woocommerce_Catalog_general",
    );

    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_license",
        "label" => __( "Settings", 'woocommerce-catalog-enquiry' ),
    );

    // Catalog Mode
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_enable",
        "label" => __( "Catalog Mode", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this option to activate catalog mode sitewide ( to hide add to cart)', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );
    // Enable product enquiry button
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_enable_enquiry",
        "label" => __( "Enable product enquiry button", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Enable this option to add Enquiry button for all products', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        )
    );

    // for user type
    $woo_catalog_options[ ] = array(
        "type" => "select",
        "id" => "for_user_type",
        "desc" => __('Select the user type where this catalog is applicable', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Catalog Mode Applicable For", 'woocommerce-catalog-enquiry' ),
        "option_values" => array('0' =>'Please Select', '1' => 'Only for logout user', '2' => 'Only for logged in user', '3' => 'Either logged in or logged out'), 'hints' => __('Method applicable for only secleted user group default all.', 'woocommerce-catalog-enquiry'),   'desc' => __('Select the user type where this catalog is applicable.', 'woocommerce-catalog-enquiry'
            )
    );

    // Catalog Mode
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_hide_cart_checkout",
        "label" => __( "Choose custom button at the place of add to cart", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Do you want to redirect to home if any one click on the cart or checkout page link', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );

    /*************************************************/
    // Display Options
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_general",
        "label" => __( "Display Options", 'woocommerce-catalog-enquiry' ),
    );

    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_remove_price",
        "label" => __( "Display Product Price?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Just Checked this checkbox for remove the price from catalog', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
     
    );

    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_disable_popup_backdrop",
        "label" => __( "Enquiry Popup Backdrop Disable?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Check this for deactive the popup backdrop', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
     
    );

    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_disable_popup",
        "label" => __( "Enquiry Popup Disable?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Check this for deactive the popup.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
     
    );

    // Redirection Settings
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_page_settings",
        "label" => __( "Redirection Settings", 'woocommerce-catalog-enquiry' )
    );

    // displayt cart and checkout page
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "display_cart_checkout",
        "label" => __( "Disable Cart and  Checkout Page?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Do you want to redirect to home if any one click on the cart or checkout page link', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );

    

    // Redirect after enquiry success
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_page_redirect",
        "label" => __( "Redirect to other page", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Do you want to redirect to other page after enquiry successful.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );

    // set redirect page
    $woo_catalog_options[ ] = array(
        "type" => "select",
        "id" => "redirect_page_id",
        "label" => __( "Set Redirect page", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Select page where will be redirected after enquiry successful', 'woocommerce-catalog-enquiry'),
        "option_values" => get_all_pages()
    );

    // Header
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_header",
        "label" => __( "Enquiry Mail Receiver Settings", 'woocommerce-catalog-enquiry' )
    );

    // Additional enquiry receivers
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "other_emails",
        "class" => "large-text",
        "label" => __( "Other Emails (commma seperated)", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter email address if you want to receive enquiry mail along with admin mail.", 'woocommerce-catalog-enquiry' ),
    );

    // Remove admin email
    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_other_admin_mail",
        "label" => __( "Remove admin email", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Do you want remove admin email from reciever list.', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );

    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "other_admin_mail",
        "class" => "large-text",
        "label" => __( "Enter mail id who get the email", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter email address if you want to receive enquiry mail along with admin mail.", 'woocommerce-catalog-enquiry' ),
    );

    /**
     * Button Appearance Tab
     */
    $woo_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo_catalog_button",
        "label" => __( "Button Appearance", 'woocommerce-catalog-enquiry' )
    );

    $woo_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "dc_wc_Woocommerce_Catalog_button_appear"
    );

    // Themes

    // Background
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "woo_catalog_button_appearence",
        "label" => __( "Background", 'woocommerce-catalog-enquiry' )
    );
    // Enquiry Button label
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_text",
        "label" => __( "Enquiry Button label", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Do you want to remove admin email from the receiver list', 'woocommerce-catalog-enquiry'),
    );

    $woo_catalog_options[ ] = array(
        "type" => "select",
        "id" => "button_type",
        "desc" => __('By default Read More Button', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Choose your button type", 'woocommerce-catalog-enquiry' ),
        "option_values" => array('0' => __('Please Select', 'woocommerce-catalog-enquiry'),
         '1' => __('Read More', 'woocommerce-catalog-enquiry'),
         '2' => 'Custom Link For All Products',
         '3' => 'Individual link in all products',
         '4' => 'No Link Just #'
         )
    );

    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_link",
        "label" => __( "Button Link", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Aplicable only when you choose custom link for all products in button type', 'woocommerce-catalog-enquiry'),
    );

    // Background
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_well",
        "label" => __( "Override Color Scheme Colors", 'woocommerce-catalog-enquiry' )
    );
    // button_text_color
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_text_color",
        "label" => __( "Button Text Color", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    // Background Color
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_background_color",
        "label" => __( "Background Color", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    //button_background_color_hover
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_background_color_hover",
        "label" => __( "Custom Button background Color Hover", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    // button_text_color_hover
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_text_color_hover",
        "label" => __( "Custom Button Text Color Hover", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_background_color_hover",
        "label" => __( "Text Color", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "text_color",
        "label" => __( "Text Color", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );

    // Custom button Width
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_width",
        "class" => "large-text",
        "label" => __( "Custom button Width", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Custom button Height
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_height",
        "class" => "large-text",
        "label" => __( "Headline", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Custom button Padding
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_padding",
        "class" => "large-text",
        "label" => __( "Custom button Padding", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Custom button Border
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_border_size",
        "class" => "large-text",
        "label" => __( "Custom button Border", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Custom button Font size
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_fornt_size",
        "class" => "large-text",
        "label" => __( "Custom button Font size", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    
    // Custom button border redius
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_border_redius",
        "class" => "large-text",
        "label" => __( "Custom button border redius", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Choose Button Border Color
    $woo_catalog_options[ ] = array(
        "type" => "color",
        "id" => "button_border_color",
        "label" => __( "Choose Button Border Color", 'woocommerce-catalog-enquiry' ),
        "default_value" => "#666666",
        "validate" => 'required,color',
    );
    // Custom button margin top
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_margin_top",
        "class" => "large-text",
        "label" => __( "Custom button margin top", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );
    // Custom button margin bottom
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "button_margin_bottom",
        "class" => "large-text",
        "label" => __( "Custom button margin bottom", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Enter a headline for your page.", 'woocommerce-catalog-enquiry' ),
    );

    // Template
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_template",
        "label" => __( "Template", 'woocommerce-catalog-enquiry' )
    );


    $woo_catalog_options[ ] = array(
        "type" => "textarea",
        "id" => "custom_css_product_page",
        "class" => "large-text",
        "label" => __( "Custom CSS", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Put your custom css in this box for product page there is no need to put the style Tag','woocommerce-catalog-enquiry'),
    );

    /**
     * Subscribers Settings Tab
     */
    $woo_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo_catalog_exclusion",
        "label" => __( "Exclusion", 'woocommerce-catalog-enquiry' )
    );

    $woo_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "dc_wc_Woocommerce_Catalog_Enquiry_exclusion_settings_name"
    );

    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_scripts_subs",
        "label" => __( "User Specific", 'woocommerce-catalog-enquiry' )
    );

    $woo_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "myuserroles_list",
        "desc" => __('User Role List Excluded from catalog', 'woocommerce-catalog-enquiry' ),
        "label" => __( "User Role List Excluded from catalog", 'woocommerce-catalog-enquiry' ),
        "option_values" => array_keys( wp_roles()->roles )
    );
    // User Role List Excluded from catalog
    $woo_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "myuser_list",
        "desc" => __('User List Excluded from catalog', 'woocommerce-catalog-enquiry' ),
        "label" => __( "User List Excluded from catalog", 'woocommerce-catalog-enquiry' ),
        "option_values" => wp_all_users()
    );
    // Product List Excluded from catalog
    $woo_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "myproduct_list",
        "desc" => __('Product List Excluded from catalog', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Product List Excluded from catalog", 'woocommerce-catalog-enquiry' ),
        "option_values" => get_all_products()
    );
    // Category List Excluded from catalog
    $woo_catalog_options[ ] = array(
        "type" => "multiselect",
        "id" => "mycategory_list",
        "desc" => __('Category List Excluded from catalog', 'woocommerce-catalog-enquiry' ),
        "label" => __( "Category List Excluded from catalog", 'woocommerce-catalog-enquiry' ),
        "option_values" => get_all_product_category()
    );


    /**
     * Enquiry Form Settings Tab
     */
    $woo_catalog_options[ ] = array(
        "type" => "tab",
        "id" => "woo_catalog_from",
        "label" => __( "Enquiry Form", 'woocommerce-catalog-enquiry' )
    );

    $woo_catalog_options[ ] = array(
        "type" => "setting",
        "id" => "dc_wc_Woocommerce_Catalog_from_settings_name"
    );
 
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_general_from",
        "label" => __( "Enquiry General Settings", 'woocommerce-catalog-enquiry' )
    );
    

    $woo_catalog_options[ ] = array(
        "type" => "wpeditor",
        "id" => "top_content_form",
        "label" => __( "Enquiry Top content", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Put your content if you want to top of enquiry form", 'woocommerce-catalog-enquiry' ),
        "class" => "large-text"
    );

    $woo_catalog_options[ ] = array(
        "type" => "wpeditor",
        "id" => "bottom_content_form",
        "label" => __( "Enquiry Bottom content", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Just Checked this checkbox for product page enquiry form enable", 'woocommerce-catalog-enquiry' ),
        "class" => "large-text"
    );

    $woo_catalog_options[ ] = array(
        "type" => "checkbox",
        "id" => "is_override_form_heading",
        "label" => __( "Override Form Heading?", 'woocommerce-catalog-enquiry' ),
        "desc" => __('Just Checked this checkbox if you want to override dynamic heading with your static heading', 'woocommerce-catalog-enquiry'),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
             //'required' => __( 'Make Name Required', 'woocommerce-catalog-enquiry' ),
        )
    );
    $woo_catalog_options[ ] = array(
        "type" => "textbox",
        "id" => "custom_static_heading",
        "class" => "large-text",
        "label" => __( "Custom Form Heading", 'woocommerce-catalog-enquiry' ),
        "desc" => __( "Please Enter your custom Heading insteed of dynamic form heading.", 'woocommerce-catalog-enquiry' ),
    );


    // Enquiry Form Settings
    $woo_catalog_options[ ] = array(
        "type" => "section",
        "id" => "wcmp_catalog_scripts",
        "label" => __( "Enquiry Form Settings", 'woocommerce-catalog-enquiry' )
    );

    /***************  Body Start  ********************/
    // Capta fileds
    $woo_catalog_options[ ] = array(
        "type" => "table_body",
        "id" => "details_data",
        "label" => __( "", 'woocommerce-catalog-enquiry' ),
        "desc" => __("",''),
        "option_values" => array(
             'Enable' => __( '', 'woocommerce-catalog-enquiry' ),
        ),
    );

  	return apply_filters( 'woo_catalog_enquiry_fileds_options', $woo_catalog_options);
	}
}
