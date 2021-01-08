<?php

class Woocommerce_Catalog_Enquiry_Admin {

    public $settings;

    public function __construct() {
        //admin script and style
        add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_script'));
        add_action('woocommerce_catalog_enquiry_admin_footer', array(&$this, 'woocommerce_catalog_enquiry_admin_footer'));

        $this->load_class('settings');
        $this->settings = new Woocommerce_Catalog_Enquiry_Settings();
        $this->init_product_settings();
    }

    function load_class($class_name = '') {
        global $Woocommerce_Catalog_Enquiry;
        if ('' != $class_name) {
            require_once ($Woocommerce_Catalog_Enquiry->plugin_path . '/admin/class-' . esc_attr($Woocommerce_Catalog_Enquiry->token) . '-' . esc_attr($class_name) . '.php');
        } // End If Statement
    }

    // End load_class()

    public function woocommerce_catalog_enquiry_admin_footer() {
        global $Woocommerce_Catalog_Enquiry;
        ?>
        <div style="clear: both"></div>
        <div id="woocommerce-catalog-admin-footer">
        <?php _e('Powered by', 'woocommerce-catalog-enquiry'); ?> <a href="http://wc-marketplace.com/" target="_blank"><img src="<?php echo $Woocommerce_Catalog_Enquiry->plugin_url . '/assets/images/wcmp.png'; ?>"></a><?php _e('WC Marketplace', 'woocommerce-catalog-enquiry'); ?> &copy; <?php echo date('Y'); ?>
        </div>
        <?php
    }

    public function init_product_settings() {
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $options_button_appearence_settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable") {
            if (isset($options_button_appearence_settings['button_type']) && $options_button_appearence_settings['button_type'] == 3) {
                add_filter('woocommerce_product_data_tabs', array($this, 'catalog_product_data_tabs'), 99);
                add_action('woocommerce_product_data_panels', array($this, 'catalog_product_data_panel'));
                add_action('woocommerce_process_product_meta_simple', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_grouped', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_external', array($this, 'save_catalog_data'));
                add_action('woocommerce_process_product_meta_variable', array($this, 'save_catalog_data'));
            }
        }
    }

    public function catalog_product_data_tabs($tabs) {
        $tabs['woocommerce_catalog_enquiry'] = array(
            'label' => __('Catalog Enquiry', 'catalog-enquiry'),
            'target' => 'woocommerce-catalog-enquiry-product-data',
            'class' => array(''),
        );
        return $tabs;
    }

    /**
     * Save meta.
     *
     * Save the product catalog enquiry meta data.
     *
     * @since 1.0.0
     *
     * @param int $post_id ID of the post being saved.
     */
    public function save_catalog_data($post_id) {

        // Save all meta
        update_post_meta($post_id, 'woocommerce_catalog_enquiry_product_link', esc_url($_POST['woocommerce_catalog_enquiry_product_link']));
    }

    /**
     * Output catalog individual product link.
     *
     * Output settings to the product link tab.
     *
     * @since 1.0.0
     */
    public function catalog_product_data_panel() {
        global $Woocommerce_Catalog_Enquiry;
        ?><div id="woocommerce-catalog-enquiry-product-data" class="panel woocommerce_options_panel"><?php
        woocommerce_wp_text_input(array(
            'id' => 'woocommerce_catalog_enquiry_product_link',
            'label' => __('Enter product external link', 'woocommerce-catalog-enquiry'),
            'placeholder' => __('https://www.google.com', 'woocommerce-catalog-enquiry')
        ));
        ?></div><?php
        }

        /**
         * Admin Scripts
         */
        public function enqueue_admin_script() {
            global $Woocommerce_Catalog_Enquiry;
            $screen = get_current_screen();

            $settings_buttons = get_option( 'woocommerce_catalog_enquiry_button_appearence_settings' );
            // Enqueue admin script and stylesheet from here
            if ($screen->id == 'toplevel_page_woo-catalog' ) :

                $Woocommerce_Catalog_Enquiry->library->load_qtip_lib();
                $Woocommerce_Catalog_Enquiry->library->load_select2_lib();
                $Woocommerce_Catalog_Enquiry->library->load_upload_lib();
                $Woocommerce_Catalog_Enquiry->library->load_colorpicker_lib();
                $Woocommerce_Catalog_Enquiry->library->load_datepicker_lib();

                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script('catalog_admin_js', $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/admin/js/admin.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);
                wp_enqueue_style('catalog_admin_css', $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/admin/css/admin.css', array(), $Woocommerce_Catalog_Enquiry->version);

                // Colorpicker css
                wp_enqueue_style('button_color_picker_css', $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/admin/css/colorpicker_btn.css', array(), $Woocommerce_Catalog_Enquiry->version);
                // Colorpicker js
                wp_enqueue_script('button_color_picker_js', $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/admin/js/colorpicker_btn.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);
                // Button js
                wp_enqueue_script('button_gen_js', $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/admin/js/button_gen.js', array('jquery'), $Woocommerce_Catalog_Enquiry->version, true);

                wp_localize_script(
                'button_gen_js', 
                'wcmp_catalog_btn', 
                array(
                    'custom_css' => isset($settings_buttons['custom_enquiry_buttons_css']) ? $settings_buttons['custom_enquiry_buttons_css'] : '',  
                    'custom_cssStuff' => isset($settings_buttons['custom_enquiry_buttons_cssStuff']) ? $settings_buttons['custom_enquiry_buttons_cssStuff'] : '',
                    'custom_cssValues' => isset($settings_buttons['custom_enquiry_buttons_cssValues']) ? $settings_buttons['custom_enquiry_buttons_cssValues'] : '',    
                    ));

            endif;
        }
    }

    

    