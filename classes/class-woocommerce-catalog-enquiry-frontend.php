<?php

class Woocommerce_Catalog_Enquiry_Frontend {

    public $available_for;

    public function __construct() {
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $options_button_appearence_settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        //enqueue scripts
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
        //enqueue styles
        add_action('wp_enqueue_scripts', array($this, 'frontend_styles'));
        add_action('template_redirect', array($this, 'redirect_cart_checkout_on_conditions'));

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $this->available_for = '';

        if (isset($exclusion['woocommerce_userroles_list'])) {
            if (is_array($exclusion['woocommerce_userroles_list'])) {
                if (in_array($current_user->roles[0], $exclusion['woocommerce_userroles_list'])) {
                    $this->available_for = $current_user->ID;
                }
            }
        }
        if (isset($exclusion['woocommerce_user_list'])) {
            if (is_array($exclusion['woocommerce_user_list'])) {
                if (in_array($current_user->ID, $exclusion['woocommerce_user_list'])) {
                    $this->available_for = $current_user->ID;
                }
            }
        }
        

        $for_user_type = isset($settings['for_user_type']) ? $settings['for_user_type'] : '';
        if ($for_user_type == 0 || $for_user_type == 3 || $for_user_type == '') {
            $this->init_catalog();
        } else if ($for_user_type == 1) {
            if ($current_user->ID == 0) {
                $this->init_catalog();
            }
        } else if ($for_user_type == 2) {
            if ($current_user->ID != 0) {
                $this->init_catalog();
            }
        }

        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable") {
            if (isset($options_button_appearence_settings['button_type'])) {
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
            }
        }
        // Enquiry button shortcode
        add_shortcode('wce_enquiry_button', array($this, 'wce_enquiry_button_shortcode'));
    }

    public function redirect_cart_checkout_on_conditions() {
        global $Woocommerce_Catalog_Enquiry, $post;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $count1 = 0;
        $count2 = 0;

        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable") {
            if (isset($settings['is_hide_cart_checkout']) && $settings['is_hide_cart_checkout'] == "Enable") {

                if (isset($exclusion['woocommerce_userroles_list'])) {
                    if (is_array($exclusion['woocommerce_userroles_list'])) {
                        $count1 = count($exclusion['woocommerce_userroles_list']);
                    }
                }
                if (isset($exclusion['woocommerce_user_list'])) {
                    if (is_array($exclusion['woocommerce_user_list'])) {
                        $count2 = count($exclusion['woocommerce_user_list']);
                    }
                }
                    
                
                $cart_page_id = wc_get_page_id('cart');
                $checkout_page_id = wc_get_page_id('checkout');

                if ($count2 == 0 && $count1 == 0) {

                    if (is_page($cart_page_id) || is_page($checkout_page_id)) {
                        wp_redirect(home_url());
                        exit;
                    }
                } else {
                    if ( isset($exclusion['woocommerce_userroles_list'] ) && !in_array($current_user->roles[0], $exclusion['woocommerce_userroles_list'] )) {
                        if (is_page((int) $cart_page_id) || is_page($checkout_page_id)) {
                            wp_redirect(home_url());
                            exit;
                        }
                    }
                    if (isset($exclusion['woocommerce_user_list'] ) && !in_array($current_user->ID, $exclusion['woocommerce_user_list'])) {
                        if (is_page((int) $cart_page_id) || is_page($checkout_page_id)) {
                            wp_redirect(home_url());
                            exit;
                        }
                    }
                }
            }
        }
    }
    
    public function woocommerce_loop_add_to_cart_link($add_to_cart_button, $product, $args = array()){
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        // button option
        $options_button_appearence_settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable") {
            $pro_link = '';
            if(isset($options_button_appearence_settings['button_type'])){
                switch ($options_button_appearence_settings['button_type']) {
                    case 2:
                        $link = isset($options_button_appearence_settings['button_link']) && !empty($options_button_appearence_settings['button_link']) ? $options_button_appearence_settings['button_link'] : '#';
                        $label = isset($options_button_appearence_settings['button_text']) && !empty($options_button_appearence_settings['button_text']) ? $options_button_appearence_settings['button_text'] : $product->add_to_cart_text();
                        $classes = implode( ' ', array('button','product_type_' . $product->get_type()));
                        $pro_link = sprintf( '<a id="%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_attr('woocommerce_catalog_enquiry_custom_button'),
                                esc_url( $link ),
                                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                esc_attr( $classes ),
                                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                esc_html( $label )
                        );
                        break;
                    
                    case 3:
                        $product_link = get_post_meta($product->get_id(), 'woocommerce_catalog_enquiry_product_link', true);
                        $link = !empty($product_link) ? $product_link : '#';
                        $label = isset($options_button_appearence_settings['button_text']) && !empty($options_button_appearence_settings['button_text']) ? $options_button_appearence_settings['button_text'] : $product->add_to_cart_text();
                        $classes = implode( ' ', array('button','product_type_' . $product->get_type()));
                        $pro_link = sprintf( '<a id="%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_attr('woocommerce_catalog_enquiry_custom_button'),
                                esc_url( $link ),
                                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                esc_attr( $classes ),
                                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                esc_html( $label )
                        );
                        break;
                    
                    case 4:
                        $link = '#';
                        $label = isset($options_button_appearence_settings['button_text']) && !empty($options_button_appearence_settings['button_text']) ? $options_button_appearence_settings['button_text'] : $product->add_to_cart_text();
                        $classes = implode( ' ', array('button','product_type_' . $product->get_type()));
                        $pro_link = sprintf( '<a id="%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_attr('woocommerce_catalog_enquiry_custom_button'),
                                $link,
                                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                esc_attr( $classes ),
                                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                esc_html( $label )
                        );
                        break;

                    default:
                        $link = get_permalink($product->get_id());
                        $label = isset($options_button_appearence_settings['button_text']) && !empty($options_button_appearence_settings['button_text']) ? $options_button_appearence_settings['button_text'] : __('Read More', 'woocommerce-catalog-enquiry');
                        $classes = implode( ' ', array('button','product_type_' . $product->get_type()));
                        $pro_link = sprintf( '<a id="%s" href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                esc_attr('woocommerce_catalog_enquiry_custom_button'),
                                $link,
                                esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
                                esc_attr( $classes ),
                                isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
                                esc_html( $label )
                        );
                        break;
                }
            }
            return apply_filters('woocommerce_catalog_enquiry_custom_product_link', $pro_link, $product, $settings, $options_button_appearence_settings);
        }else{
            return $add_to_cart_button;
        }
        
    }

    public function init_catalog() {
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;


        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable" && ($this->available_for == '' || $this->available_for == 0)) {
            add_action('init', array($this, 'remove_add_to_cart_button'));
            if (isset($settings['is_enable_enquiry']) && $settings['is_enable_enquiry'] == "Enable") {
                $piority = apply_filters('woocommerce_catalog_enquiry_button_possition_piority', 100);
                if (isset($settings['is_disable_popup']) && $settings['is_disable_popup'] == "Enable") {
                    add_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry_without_popup'), $piority);
                } else {
                    add_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry'), $piority);
                }
            }
            if (isset($settings['is_remove_price']) && $settings['is_remove_price'] == "Enable") {
                add_action('init', array($this, 'remove_price_from_product_list_loop'), 10);
                add_action('woocommerce_single_product_summary', array($this, 'remove_price_from_product_list_single'), 5);
                add_filter( 'woocommerce_catalog_orderby', array($this, 'remove_pricing_from_catalog_orderby'), 99 );
            }

            add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
            
            add_action('woocommerce_after_shop_loop_item_title', array($this, 'price_for_selected_product'), 5);
            add_action('woocommerce_after_shop_loop_item', array($this, 'add_to_cart_button_for_selected_product'), 5);
            add_action('woocommerce_before_shop_loop_item', array($this, 'change_permalink_url_for_selected_product'), 5);
            add_action('woocommerce_single_product_summary', array($this, 'catalog_woocommerce_template_single'), 5);
        }
    }

    public function change_permalink_url_for_selected_product() {
        global $Woocommerce_Catalog_Enquiry, $post, $product;
        $options_button_appearence_settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        $product_for = '';

        if (isset($exclusion['woocommerce_product_list'])) {
            if (is_array($exclusion['woocommerce_product_list']) && isset($post->ID)) {
                if (in_array($post->ID, $exclusion['woocommerce_product_list'])) {
                    $product_for = $post->ID;
                } else {
                    $product_for = '';
                }
            }
        }
        
        $category_for = '';
        if (isset($exclusion['woocommerce_category_list'])) {
            if (is_array($exclusion['woocommerce_category_list'])) {
                if (isset($product)) {
                    $term_list = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'ids'));

                    if (count(array_intersect($term_list, $exclusion['woocommerce_category_list'])) > 0) {
                        $category_for = $post->ID;
                    } else {
                        $category_for = '';
                    }
                } else {
                    $category_for = '';
                }
            } else {
                $category_for = '';
            }
        } else {
            $category_for = '';
        }
        
        
        if ($product_for == $post->ID || $category_for == $post->ID) {
            add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            remove_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
        } else {
            if($options_button_appearence_settings['button_type']){
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
            }else{
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            }
            
        }
    }

    public function catalog_woocommerce_template_single() {
        global $Woocommerce_Catalog_Enquiry, $post, $product;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        $product_for = '';

        if (isset($exclusion['woocommerce_product_list'])) {
            if (is_array($exclusion['woocommerce_product_list']) && isset($post->ID)) {
                if (in_array($post->ID, $exclusion['woocommerce_product_list'])) {

                    $product_for = $post->ID;
                } else {
                    $product_for = '';
                }
            } else {
                $product_for = '';
            }
        } else {
            $product_for = '';
        }
        

        $category_for = '';
        if (isset($exclusion['woocommerce_category_list'])) {
            if (is_array($exclusion['woocommerce_category_list'])) {
                if (isset($product)) {
                    $term_list = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'ids'));

                    if (count(array_intersect($term_list, $exclusion['woocommerce_category_list'])) > 0) {
                        $category_for = $post->ID;
                    } else {
                        $category_for = '';
                    }
                } else {
                    $category_for = '';
                }
            } else {
                $category_for = '';
            }
        } else {
            $category_for = '';
        }
       

        if ($product_for == $post->ID || $category_for == $post->ID) {
            remove_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry'), 100);
            remove_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry_without_popup'), 100);
            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
            add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            add_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
            remove_action('woocommerce_single_product_summary', array($this, 'add_variation_product'), 29);
        }else{
            remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
            remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
        }
    }

    public function add_form_for_enquiry_without_popup() {
        global $Woocommerce_Catalog_Enquiry, $woocommerce, $post, $product;
        $settings = $Woocommerce_Catalog_Enquiry->options_form_settings;
        $settings_buttons = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;

        if (isset($settings_buttons)) {
            $button_text = isset($settings_buttons['button_text']) ? $settings_buttons['button_text'] : __('Send an enquiry', 'woocommerce-catalog-enquiry');
            if ($button_text == '') {
                $button_text = __('Send an enquiry', 'woocommerce-catalog-enquiry');
            }
        }
        $productid = $post->ID;
        $current_user = wp_get_current_user();
        $product_name = get_post_field('post_title', $productid);
        $product_url = get_permalink($productid);
        ?>    
        <div id="woocommerce_catalog" name="woocommerce_catalog" >	
            <?php if ( isset($button_text) ) { ?>
                <br/>
                <button class="woocommerce_catalog_enquiry_btn button woocommerce_catalog_enquiry_custom_button_enquiry" href="#responsive"><?php echo $button_text; ?></button>
                <?php
            } else {
                ?>
                <button class="woocommerce_catalog_enquiry_btn button demo btn btn-primary btn-large" style="margin-top:15px;" href="#responsive"><?php echo __('Send an enquiry', 'woocommerce-catalog-enquiry') ?></button>
            <?php } ?>
            <input type="hidden" name="product_name_for_enquiry" id="product_name_for_enquiry" value="<?php echo get_post_field('post_title', $post->ID); ?>" />
            <input type="hidden" name="product_url_for_enquiry" id="product_url_for_enquiry" value="<?php echo get_permalink($post->ID); ?>" />
            <input type="hidden" name="product_id_for_enquiry" id="product_id_for_enquiry" value="<?php echo $post->ID; ?>" />
            <input type="hidden" name="enquiry_product_type" id="enquiry_product_type" value="<?php
            if ($product->is_type('variable')) {
                echo 'variable';
            }
            ?>" />
            <div id="responsive"  class="catalog_enquiry_form" tabindex="-1">
                <div class="modal-header">
                    <?php if (isset($settings['is_override_form_heading'])) { ?>
                        <?php if (isset($settings['custom_static_heading'])) { ?>
                            <h2><?php echo str_replace( "PRODUCT_NAME",$product_name, $settings['custom_static_heading'] ); ?></h2>
                        <?php } ?>
                    <?php } else { ?>
                        <h2><?php echo __('Enquiry about ', 'woocommerce-catalog-enquiry') ?> <?php echo $product_name; ?></h2>
                    <?php } ?>
                </div>
                <div class="modal-body">  
                    <?php
                    if (isset($settings['top_content_form']) && !empty($settings['top_content_form'])) {
                        echo '<p class="catalog-enquiry-top-content">' . $settings['top_content_form'] . '</p>';
                    }
                    ?>
                    <p id="msg-for-enquiry-error" style="color:#f00; text-align:center;"></p>
                    <p id="msg_for_enquiry_sucesss" style="color:#0f0; text-align:center;"></p>
                    <p id="loader_after_sumitting_the_form" style="text-align:center;"><img src="<?php echo $Woocommerce_Catalog_Enquiry->plugin_url; ?>assets/images/loader.gif" ></p>
                            <?php wp_nonce_field('wc_catalog_enquiry_mail_form', 'wc_catalog_enq'); ?>
                    <div class="cat-form-row">
                        <label><?php

                    if (isset($settings['form_name']['label']) && $settings['form_name']['label'] != '' && $settings['form_name']['label'] != ' ') {
                        echo $settings['form_name']['label'];
                    } else {
                        echo __('Enter your name : ', 'woocommerce-catalog-enquiry');
                    }
                            ?></label>	
                        <input name="woocommerce_user_name" id="woocommerce-user-name"  type="text" value="<?php echo $current_user->display_name; ?>" class="span12" />
                    </div>
                    <div class="cat-form-row">						
                        <label><?php
                    if (isset($settings['form_email']['label']) && $settings['form_email']['label'] != '' && $settings['form_email']['label'] != ' ') {
                        echo $settings['form_email']['label'];
                    } else {
                        echo __('Enter your Email Id : ', 'woocommerce-catalog-enquiry');
                    }
                            ?></label>	
                        <input name="woocommerce_user_email" id="woocommerce-user-email"  type="email" value="<?php echo $current_user->user_email; ?>" class="span12" />
                    </div>
                    <div class="cat-form-row">	
        <?php if (isset($settings['is_subject']) && $settings['is_subject'] == "Enable") { ?>
                            <label><?php
                            if (isset($settings['subject_label']['label']) && $settings['subject_label']['label'] != '' && $settings['subject_label']['label'] != ' ') {
                                echo $settings['subject_label']['label'];
                            } else {
                                echo __('Enter enquiry subject : ', 'woocommerce-catalog-enquiry');
                            }
                            ?></label>	
                            <input name="woocommerce_user_subject" id="woocommerce-user-subject"  type="text" value="<?php echo __('Enquiry about', 'woocommerce-catalog-enquiry'); ?> <?php echo $product_name; ?>" class="span12" />
                        <?php } ?>
                    </div>
                    <div class="cat-form-row">	
                        <?php if (isset($settings['form_phone']['is_enable']) && $settings['form_phone']['is_enable'] == "Enable") { ?>
                            <label><?php
                                if (isset($settings['phone_label']['label']) && $settings['phone_label']['label'] != '' && $settings['phone_label']['label'] != ' ') {
                                    echo $settings['phone_label']['label'];
                                } else {
                                    echo __('Enter your phone no : ', 'woocommerce-catalog-enquiry');
                                }
                                ?></label>	
                            <input name="woocommerce_user_phone" id="woocommerce-user-phone"  type="text" value="" class="span12" />
                        <?php } ?>
                    </div>
                    <div class="cat-form-row">	
                            <?php if (isset($settings['form_address']['is_enable']) && $settings['form_address']['is_enable'] == "Enable") { ?>
                            <label><?php
                                if (isset($settings['address_label']['label']) && $settings['address_label']['label'] != '' && $settings['address_label']['label'] != ' ') {
                                    echo $settings['address_label']['label'];
                                } else {
                                    echo __('Enter your address : ', 'woocommerce-catalog-enquiry');
                                }
                                ?></label>	
                            <input name="woocommerce_user_address" id="woocommerce-user-address"  type="text" value="" class="span12" />
                            <?php } ?>
                    </div>
                    <div class="cat-form-row">	
                            <?php if (isset($settings['form_comment']['is_enable']) && $settings['form_comment']['is_enable'] == "Enable") { ?>
                            <label><?php
                    if (isset($settings['comment_label']['label']) && $settings['comment_label']['label'] != '' && $settings['comment_label']['label'] != ' ') {
                        echo $settings['comment_label']['label'];
                    } else {
                        echo __('Enter your Message : ', 'woocommerce-catalog-enquiry');
                    }
                                ?></label>	
                            <textarea name="woocommerce_user_comment" id="woocommerce-user-comment"  rows="5" class="span12"></textarea>
                            <?php } ?>
                    </div>
                    <div class="cat-form-row">	
                            <?php if (isset($settings['form_fileupload']['is_enable']) && $settings['form_fileupload']['is_enable'] == "Enable") { ?>
                            <label><?php
                                    if (isset($settings['fileupload_label']['label']) && $settings['fileupload_label']['label'] != '' && $settings['fileupload_label']['label'] != ' ') {
                                        echo $settings['fileupload_label']['label'];
                                    } else {
                                        echo __('Upload your File : ', 'woocommerce-catalog-enquiry');
                                    }
                                    ?></label>	
                            <input type="file" name="woocommerce_user_fileupload" id="woocommerce-user-fileupload" class="span12" />
                    <?php } ?>
                    </div>
                    <div class="cat-form-row">							
        <?php do_action('woocommerce_catalog_enquiry_form_extra_fileds'); ?> 
        <?php if (isset($settings['form_captcha']['is_enable']) && $settings['form_captcha']['is_enable'] == "Enable") { ?>
                            <label><?php
            if (isset($settings['captcha_label']['label']) && $settings['captcha_label']['label'] != '' && $settings['captcha_label']['label'] != ' ') {
                echo $settings['captcha_label']['label'];
            } else {
                echo __('Security Code', 'woocommerce-catalog-enquiry');
            }
            ?> <span class="noselect captcha-wrap"><i><?php echo get_transient('woocaptcha'); ?></i></span></p>
                                <p><?php
            if (isset($settings['captcha_input_label']) && $settings['captcha_input_label'] != '' && $settings['captcha_input_label'] != ' ') {
                echo $settings['captcha_input_label'];
            } else {
                echo __('Enter the security code shown above', 'woocommerce-catalog-enquiry');
            }
            ?> </p>
                                <input type="text" id="woocommerce_catalog_captcha" name="woocommerce_captcha" class="span12" />
        <?php } ?>
                    </div>
        <?php
        if (isset($settings['bottom_content_form']) && !empty($settings['bottom_content_form'])) {
            echo '<p class="catalog-enquiry-bottom-content">' . $settings['bottom_content_form'] . '</p>';
        }
        ?> 
                </div>
                <div class="modal-footer">		
                    <button type="button" id="woocommerce_submit_enquiry" class="btn btn-primary"><?php echo __('Send', 'woocommerce-catalog-enquiry'); ?></button>
                </div>
            </div>				
        </div>	
        <?php
    }

    public function add_form_for_enquiry() {
        global $Woocommerce_Catalog_Enquiry, $woocommerce, $post, $product, $wp_version;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $settings_gen = $Woocommerce_Catalog_Enquiry->options_form_settings;
        $is_page_redirect = '';
        if (isset($settings['is_page_redirect'])) {
            $is_page_redirect = $settings['is_page_redirect'];
            $redirect_page_id = $settings['redirect_page_id'];
        }
        $settings_buttons = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        if (isset($settings_buttons)) {
            $custom_design_for_button = isset($settings_buttons['is_button']) ? $settings_buttons['is_button'] : '';
            $button_text = isset($settings_buttons['button_text']) ? $settings_buttons['button_text'] : __('Send an enquiry', 'woocommerce-catalog-enquiry');
            if ($button_text == '') {
                $button_text = __('Send an enquiry', 'woocommerce-catalog-enquiry');
            }
        }

        $productid = $post->ID;
        $current_user = wp_get_current_user();
        $product_name = get_post_field('post_title', $productid);
        $product_url = get_permalink($productid);
        ?>
        <div id="woocommerce_catalog" name="woocommerce_catalog" >
                        <?php if (isset($custom_design_for_button) && $custom_design_for_button == "Enable") { ?>
                <br/>
                <button class="woocommerce_catalog_enquiry_btn button woocommerce_catalog_enquiry_custom_button_enquiry" href="#responsive"><?php echo $button_text; ?></button>
                            <?php
                        } else {
                            ?>
                <button class="woocommerce_catalog_enquiry_btn button demo btn btn-primary btn-large" style="margin-top:15px;" href="#responsive"><?php echo __('Send an enquiry', 'woocommerce-catalog-enquiry') ?></button>
        <?php } ?>

            <input type="hidden" name="product_name_for_enquiry" id="product_name_for_enquiry" value="<?php echo get_post_field('post_title', $post->ID); ?>" />
            <input type="hidden" name="product_url_for_enquiry" id="product_url_for_enquiry" value="<?php echo get_permalink($post->ID); ?>" />
            <input type="hidden" name="product_id_for_enquiry" id="product_id_for_enquiry" value="<?php echo $post->ID; ?>" />
            <input type="hidden" name="enquiry_product_type" id="enquiry_product_type" value="<?php
                                if ($product->is_type('variable')) {
                                    echo 'variable';
                                }
                                ?>" />
            <div id="responsive"  class="catalog_modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close">&times;</button>
                                <?php if (isset($settings_gen['is_override_form_heading'])) { ?>
                                    <?php if (isset($settings_gen['custom_static_heading'])) { ?>
                                <h2><?php echo $settings_gen['custom_static_heading']; ?></h2>
                                    <?php } ?>
        <?php } else { ?>
                            <h2><?php echo __('Enquiry about ', 'woocommerce-catalog-enquiry') ?> <?php echo $product_name; ?></h2>
                            <?php } ?>
                    </div>
                    <div class="modal-body">  
                                <?php
                                if (isset($settings_gen['top_content_form'])) {
                                    echo '<p class="catalog-enquiry-top-content">' . $settings_gen['top_content_form'] . '</p>';
                                }
                                ?>
                        <p id="msg-for-enquiry-error" style="color:#f00; text-align:center;"></p>
                        <p id="msg_for_enquiry_sucesss" style="color:#0f0; text-align:center;"></p>
                        <p id="loader_after_sumitting_the_form" style="text-align:center;"><img src="<?php echo $Woocommerce_Catalog_Enquiry->plugin_url; ?>assets/images/loader.gif" ></p>
                            <?php wp_nonce_field('wc_catalog_enquiry_mail_form', 'wc_catalog_enq'); ?>
                        <div class="cat-form-row">
                            <label><?php
                                if (isset($settings_gen['form_name']['label']) && $settings_gen['form_name']['label'] != '' && $settings_gen['form_name']['label'] != ' ') {
                                    echo $settings_gen['form_name']['label'];
                                } else {
                                    echo __('Enter your name : ', 'woocommerce-catalog-enquiry');
                                }
                                ?></label>	
                            <input name="woocommerce_user_name" id="woocommerce-user-name"  type="text" value="<?php echo $current_user->display_name; ?>" class="span12" />
                        </div>
                        <div class="cat-form-row">
                            <label><?php
                                if (isset($settings_gen['form_email']['label']) && $settings_gen['form_email']['label'] != '' && $settings_gen['form_email']['label'] != ' ') {
                                    echo $settings_gen['form_email']['label'];
                                } else {
                                    echo __('Enter your Email Id : ', 'woocommerce-catalog-enquiry');
                                }
                                ?></label>	
                            <input name="woocommerce_user_email" id="woocommerce-user-email"  type="email" value="<?php echo $current_user->user_email; ?>" class="span12" />
                        </div>
                        <div class="cat-form-row">
                                <?php if (isset($settings_gen['is_subject']) && $settings_gen['is_subject'] == "Enable") { ?>
                                <label><?php
                                    if (isset($settings_gen['subject_label']['label']) && $settings_gen['subject_label']['label'] != '' && $settings_gen['subject_label']['label'] != ' ') {
                                        echo $settings_gen['subject_label']['label'];
                                    } else {
                                        echo __('Enter enquiry subject : ', 'woocommerce-catalog-enquiry');
                                    }
                                    ?></label>	
                                <input name="woocommerce_user_subject" id="woocommerce-user-subject"  type="text" value="<?php echo __('Enquiry about', 'woocommerce-catalog-enquiry'); ?> <?php echo $product_name; ?>" class="span12" />
                                <?php } ?>
                        </div>
                        <div class="cat-form-row">
                                <?php if (isset($settings_gen['form_phone']['is_enable']) && $settings_gen['form_phone']['is_enable'] == "Enable") { ?>
                                <label><?php
                        if (isset($settings_gen['phone_label']['label']) && $settings_gen['phone_label']['label'] != '' && $settings_gen['phone_label']['label'] != ' ') {
                            echo $settings_gen['phone_label']['label'];
                        } else {
                            echo __('Enter your phone no : ', 'woocommerce-catalog-enquiry');
                        }
                                    ?></label>	
                                <input name="woocommerce_user_phone" id="woocommerce-user-phone"  type="text" value="" class="span12" />
                                <?php } ?>
                        </div>
                        <div class="cat-form-row">
                                <?php if (isset($settings_gen['form_address']['is_enable']) && $settings_gen['form_address']['is_enable'] == "Enable") { ?>
                                <label><?php
                                    if (isset($settings_gen['address_label']['label']) && $settings_gen['address_label']['label'] != '' && $settings_gen['address_label']['label'] != ' ') {
                                        echo $settings_gen['address_label']['label'];
                                    } else {
                                        echo __('Enter your address : ', 'woocommerce-catalog-enquiry');
                                    }
                                    ?></label>	
                                <input name="woocommerce_user_address" id="woocommerce-user-address"  type="text" value="" class="span12" />
                        <?php } ?>
                        </div>
                        <div class="cat-form-row">
        <?php if (isset($settings_gen['form_comment']['is_enable']) && $settings_gen['form_comment']['is_enable'] == "Enable") { ?>
                                <label><?php
            if (isset($settings_gen['comment_label']['label']) && $settings_gen['comment_label']['label'] != '' && $settings_gen['comment_label']['label'] != ' ') {
                echo $settings_gen['comment_label']['label'];
            } else {
                echo __('Enter your Message : ', 'woocommerce-catalog-enquiry');
            }
            ?></label>	
                                <textarea name="woocommerce_user_comment" id="woocommerce-user-comment"  rows="5" class="span12"></textarea>
        <?php } ?>
                        </div>
                        <div class="cat-form-row">
        <?php if (isset($settings_gen['form_fileupload']['is_enable']) && $settings_gen['form_fileupload']['is_enable'] == "Enable") { ?>
                                <label><?php
            if (isset($settings_gen['fileupload_label']['label']) && $settings_gen['fileupload_label']['label'] != '' && $settings_gen['fileupload_label']['label'] != ' ') {
                echo $settings_gen['fileupload_label']['label'];
            } else {
                echo __('Upload your File : ', 'woocommerce-catalog-enquiry');
            }
            ?></label>	
                                <input type="file" name="woocommerce_user_fileupload" id="woocommerce-user-fileupload" class="span12" />
        <?php } ?>
                        </div>
                        <div class="cat-form-row">							
        <?php do_action('woocommerce_catalog_enquiry_form_extra_fileds'); ?> 
        <?php if (isset($settings_gen['form_captcha']['is_enable']) && $settings_gen['form_captcha']['is_enable'] == "Enable") { ?>
                                <label><?php
            if (isset($settings_gen['captcha_label']['label']) && $settings_gen['captcha_label']['label'] != '' && $settings_gen['captcha_label']['label'] != ' ') {
                echo $settings_gen['captcha_label']['label'];
            } else {
                echo __('Security Code', 'woocommerce-catalog-enquiry');
            }
            ?> <span class="noselect captcha-wrap"><i><?php echo get_transient('woocaptcha'); ?></i></span></label>
                                <p><?php
            if (isset($settings_gen['captcha_input_label']) && $settings_gen['captcha_input_label'] != '' && $settings_gen['captcha_input_label'] != ' ') {
                echo $settings_gen['captcha_input_label'];
            } else {
                echo __('Enter the security code shown above', 'woocommerce-catalog-enquiry');
            }
            ?> </p>
                                <input type="text" id="woocommerce_catalog_captcha" name="woocommerce_captcha" class="span12" />
        <?php } ?>
                        </div>							
        <?php
        if (isset($settings_gen['bottom_content_form']) && !empty($settings_gen['bottom_content_form'])) {
            echo '<p class="catalog-enquiry-bottom-content">' . $settings_gen['bottom_content_form'] . '</p>';
        }
        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default"><?php echo __('Close', 'woocommerce-catalog-enquiry'); ?></button>
                        <button type="button" id="woocommerce_submit_enquiry" class="btn btn-primary"><?php echo __('Send', 'woocommerce-catalog-enquiry'); ?></button>
                    </div>
                </div>
            </div>			
        </div>		
        <?php
    }

    public function price_for_selected_product() {
        global $Woocommerce_Catalog_Enquiry, $post, $product;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        $product_for = '';

        if (isset($exclusion['woocommerce_product_list'])) {
            if (is_array($exclusion['woocommerce_product_list']) && isset($post->ID)) {
                if (in_array($post->ID, $exclusion['woocommerce_product_list'])) {
                    $product_for = $post->ID;
                } else {
                    $product_for = '';
                }
            }
        }


        $category_for = '';
        if (isset($exclusion['woocommerce_category_list'])) {
            if (is_array($exclusion['woocommerce_category_list'])) {
                if (isset($product)) {
                    $term_list = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'ids'));

                    if (count(array_intersect($term_list, $exclusion['woocommerce_category_list'])) > 0) {
                        $category_for = $post->ID;
                    } else {
                        $category_for = '';
                    }
                } else {
                    $category_for = '';
                }
            } else {
                $category_for = '';
            }
        } else {
            $category_for = '';
        }



        if ($product_for == $post->ID || $category_for == $post->ID) {
            add_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
        } else {
            if (isset($settings['is_remove_price']) && $settings['is_remove_price'] == "Enable") {
                remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
            }
        }
    }

    public function add_to_cart_button_for_selected_product() {
        global $Woocommerce_Catalog_Enquiry, $post, $product;
        $settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $exclusion = $Woocommerce_Catalog_Enquiry->options_exclusion_settings;
        $product_for = '';

        if (isset($exclusion['woocommerce_product_list'])) {
            if (is_array($exclusion['woocommerce_product_list']) && isset($post->ID)) {
                if (in_array($post->ID, $exclusion['woocommerce_product_list'])) {
                    $product_for = $post->ID;
                } else {
                    $product_for = '';
                }
            }
        }
        

        $category_for = '';
        if (isset($exclusion['woocommerce_category_list'])) {
            if (is_array($exclusion['woocommerce_category_list'])) {
                if (isset($product)) {
                    $term_list = wp_get_post_terms($post->ID, 'product_cat', array('fields' => 'ids'));

                    if (count(array_intersect($term_list, $exclusion['woocommerce_category_list'])) > 0) {
                        $category_for = $post->ID;
                    } else {
                        $category_for = '';
                    }
                } else {
                    $category_for = '';
                }
            } else {
                $category_for = '';
            }
        } else {
            $category_for = '';
        }
        

        if ($product_for == $post->ID || $category_for == $post->ID) {
            add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        } else {
            if ($settings['button_type']) {
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
            } else {
                remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            }
        }
    }

    public function add_read_more_button() {
        global $Woocommerce_Catalog_Enquiry, $post;
        $settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $button_text = "Read More";
        if (!empty($settings['button_text'])) {
            $button_text = $settings['button_text'];
        }
        $link = get_permalink($post->ID);
        echo ' <center><a  id="woocommerce_catalog_enquiry_custom_button" href="' . $link . '" class="single_add_to_cart_button button">' . $button_text . '</a></center>';
    }

    public function add_external_link_button() {
        global $Woocommerce_Catalog_Enquiry;
        $settings_button = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $button_text = "Read More";
        if (!empty($settings_button['button_text'])) {
            $button_text = $settings_button['button_text'];
        }
        $link = $settings_button['button_link'];
        echo ' <center><a  id="woocommerce_catalog_enquiry_custom_button" href="' . $link . '" class="single_add_to_cart_button button">' . $button_text . '</a></center>';
    }

    public function add_external_link_button_independent() {
        global $Woocommerce_Catalog_Enquiry, $post;
        $settings_button = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $button_text = "Read More";
        if (!empty($settings_button['button_text'])) {
            $button_text = $settings_button['button_text'];
        }
        $link = get_post_field("woocommerce_catalog_enquiry_product_link", $post->ID);
        echo ' <center><a id="woocommerce_catalog_enquiry_custom_button" href="' . $link . '" class="single_add_to_cart_button button">' . $button_text . '</a></center>';
    }

    public function add_custom_button_without_link() {
        global $Woocommerce_Catalog_Enquiry;
        $settings_button = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $button_text = "Read More";
        if (!empty($settings_button['button_text'])) {
            $button_text = $settings_button['button_text'];
        }
        $link = "#";
        echo ' <center><a id="woocommerce_catalog_enquiry_custom_button" href="' . $link . '" class="single_add_to_cart_button button">' . $button_text . '</a></center>';
    }

    public function remove_add_to_cart_button() {
        global $Woocommerce_Catalog_Enquiry, $post;
        $settings_button = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        if ( isset( $settings_button['button_type'] ) ) {
            add_filter('woocommerce_loop_add_to_cart_link', array($this, 'woocommerce_loop_add_to_cart_link'), 99, 3);
        } else {
            remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
            // remove variation from product single
            remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);
        }
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        add_action('woocommerce_single_product_summary', array($this, 'add_variation_product'), 29);
    }

    public function add_variation_product() {

        global $Woocommerce_Catalog_Enquiry, $post, $product;
        if ($product->is_type('variable')) {
            $variable_product = new WC_Product_Variable($product);
            // Enqueue variation scripts
            wp_enqueue_script('wc-add-to-cart-variation');
            $available_variations = $variable_product->get_available_variations();
            //attributes
            include_once ($Woocommerce_Catalog_Enquiry->plugin_path . 'templates/woocommerce-catalog-enquiry-variable-product.php');
        } elseif ($product->is_type('simple')) {
            echo wc_get_stock_html($product);
        }
    }

    public function remove_price_from_product_list_loop() {
        remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
    }

    public function remove_price_from_product_list_single() {
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }

    function frontend_scripts() {
        global $Woocommerce_Catalog_Enquiry;
        $frontend_script_path = $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/frontend/js/';
        $frontend_script_path = str_replace(array('http:', 'https:'), '', $frontend_script_path);
        $pluginURL = str_replace(array('http:', 'https:'), '', $Woocommerce_Catalog_Enquiry->plugin_url);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend javascript from here
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $settings_gen = $Woocommerce_Catalog_Enquiry->options_form_settings;
        if (isset($settings['load_wp_js']) && $settings['load_wp_js'] == "Enable") {
            wp_enqueue_script("jquery");
        }
        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable") {

            wp_enqueue_script('wce_frontend_js', $frontend_script_path . 'frontend.js', array( 'jquery', 'jquery-blockui' ), $Woocommerce_Catalog_Enquiry->version, true);

            // Variable declarations
            $arr_field = array();
            $arr_field[] = "name";
            $arr_field[] = "email";
            if (isset($settings_gen['is_subject']) && $settings_gen['is_subject'] == "Enable") {
                $arr_field[] = "subject";
            }
            if (isset($settings_gen['form_phone']['is_enable']) && $settings_gen['form_phone']['is_enable'] == "Enable") {
                $arr_field[] = "phone";
            }
            if (isset($settings_gen['form_address']['is_enable']) && $settings_gen['form_address']['is_enable'] == "Enable") {
                $arr_field[] = "address";
            }
            if (isset($settings_gen['form_comment']['is_enable']) && $settings_gen['form_comment']['is_enable'] == "Enable") {
                $arr_field[] = "comment";
            }
            if (isset($settings_gen['form_fileupload']['is_enable']) && $settings_gen['form_fileupload']['is_enable'] == "Enable") {
                $arr_field[] = "fileupload";
            }

            // error levels
            $error_levels = array();
            $error_levels['name_required'] = __('Name is required field', 'woocommerce-catalog-enquiry');
            $error_levels['email_required'] = __('Email is required field', 'woocommerce-catalog-enquiry');
            $error_levels['email_valid'] = __('Please Enter Valid Email Id', 'woocommerce-catalog-enquiry');
            $error_levels['captcha_required'] = __('Please enter the security code', 'woocommerce-catalog-enquiry');
            $error_levels['captcha_valid'] = __('Please enter the valid seurity code', 'woocommerce-catalog-enquiry');
            $error_levels['ajax_error'] = __('Error in system please try later', 'woocommerce-catalog-enquiry');
            $error_levels['filetype_error'] = __('Invalid file format.', 'woocommerce-catalog-enquiry');
            $error_levels['filesize_error'] = __('Exceeded filesize limit.', 'woocommerce-catalog-enquiry');

            // Captcha
            $arr = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
            $i = 0;
            $captcha = '';
            while ($i < 8) {
                $v1 = rand(0, 35);
                $captcha .= $arr[$v1];
                $i++;
            }
            set_transient('woocaptcha', $captcha, 30 * MINUTE_IN_SECONDS);

            wp_localize_script(
                    'wce_frontend_js', 'catalog_enquiry_front', apply_filters('woocommerce_catalog_enquiry_localize_script_data', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'json_arr' => json_encode($arr_field),
                'settings' => $settings,
                'settings_gen' => $settings_gen,
                'error_levels' => $error_levels,
                'ajax_success_msg' => __('Enquiry sent successfully', 'woocommerce-catalog-enquiry'),
                'redirect_link' => get_permalink($settings['redirect_page_id']),
                'captcha' => $captcha,
            )));
        }
    }

    function frontend_styles() {
        global $Woocommerce_Catalog_Enquiry;
        $settings_genaral = $Woocommerce_Catalog_Enquiry->options_general_settings;
        $settings = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;
        $settings_buttons = $Woocommerce_Catalog_Enquiry->options_button_appearence_settings;

        $frontend_style_path = $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/frontend/css/';
        $frontend_style_path = str_replace(array('http:', 'https:'), '', $frontend_style_path);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Enqueue your frontend stylesheet from here
        if (isset($settings_genaral['is_enable']) && $settings_genaral['is_enable'] == "Enable") {
            wp_enqueue_style('wce_frontend_css', $frontend_style_path . 'frontend.css', array(), $Woocommerce_Catalog_Enquiry->version);

            if (isset($settings_buttons) || isset($settings)) {

                $custom_design_for_button = isset($settings_buttons['is_button']) ? $settings_buttons['is_button'] : '';
                $background_color = isset($settings_buttons['button_background_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings_buttons['button_background_color']) : '#ccc';
                $button_text_color = isset($settings_buttons['button_text_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings_buttons['button_text_color']) : '#fff';
                $button_text_color_hover = isset($settings_buttons['button_text_color_hover']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings_buttons['button_text_color_hover']) : '#ccc';
                $button_background_color_hover = isset($settings_buttons['button_background_color_hover']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings_buttons['button_background_color_hover']) : '#eee';
                $button_width = isset($settings_buttons['button_width']) ? $settings_buttons['button_width'] . 'px' : '200px';
                $button_height = isset($settings_buttons['button_height']) ? $settings_buttons['button_height'] . 'px' : '50px';
                $button_padding = isset($settings_buttons['button_padding']) ? $settings_buttons['button_padding'] . 'px' : '10px';
                $button_border_size = isset($settings_buttons['button_border_size']) ? $settings_buttons['button_border_size'] . 'px' : '1px';
                $button_fornt_size = isset($settings_buttons['button_fornt_size']) ? $settings_buttons['button_fornt_size'] . 'px' : '18px';
                $button_border_redius = isset($settings_buttons['button_border_redius']) ? $settings_buttons['button_border_redius'] . 'px' : '5px';
                $button_border_color = isset($settings_buttons['button_border_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings_buttons['button_border_color']) : '#999';
                $button_margin_top = isset($settings_buttons['button_margin_top']) ? $settings_buttons['button_margin_top'] . 'px' : '0px';
                $button_margin_bottom = isset($settings_buttons['button_margin_bottom']) ? $settings_buttons['button_margin_bottom'] . 'px' : '0px';

                // Custom button
                $custom_btn_background = isset($settings['button_background_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings['button_background_color']) : '#013ADF';
                $custom_btn_color = isset($settings['button_text_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings['button_text_color']) : '#FFF';
                $custom_btn_padding = isset($settings['button_padding']) ? $settings['button_padding'] . 'px' : '5px';
                $custom_btn_width = isset($settings['button_width']) ? $settings['button_width'] . 'px' : '80px';
                $custom_btn_height = isset($settings['button_height']) ? $settings['button_height'] . 'px' : '26px';
                $custom_btn_line_height = isset($settings['button_fornt_size']) ? $settings['button_fornt_size'] . 'px' : '14px';
                $custom_btn_border_radius = isset($settings['button_border_redius']) ? $settings['button_border_redius'] . 'px' : '5px';
                $custom_btn_border = isset($settings['button_border_size']) ? $settings['button_border_size'] . 'px' :( '1px' . ' solid ' . isset($settings['button_border_color']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings['button_border_color']) : '#333');
                $custom_btn_font_size = isset($settings['button_fornt_size']) ? $settings['button_fornt_size'] . 'px' : '12px';
                $custom_btn_margin_top = isset($settings['button_margin_top']) ? $settings['button_margin_top'] . 'px' : '5px';
                $custom_btn_margin_bottom = isset($settings['button_margin_bottom']) ? $settings['button_margin_bottom'] . 'px' : '5px';
                $custom_btn_hover_background = isset($settings['button_background_color_hover']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings['button_background_color_hover']) : '#0431B4';
                $custom_btn_hover_color = isset($settings['button_text_color_hover']) ? woocommerce_catalog_enquiry_validate_color_hex_code($settings['button_text_color_hover']) : '#CECEF6';

                $inline_css = "
	            .woocommerce_catalog_enquiry_custom_button_enquiry {
					background: {$background_color};
					color: {$button_text_color};
					padding: {$button_padding};
					width: {$button_width};
					height: {$button_height};
					line-height: {$button_fornt_size};
					border-radius: {$button_border_redius};
					border: {$button_border_size} solid {$button_border_color};
					font-size: {$button_fornt_size};
					margin-top : {$button_margin_top};
					margin-bottom : {$button_margin_bottom};
				
				}
				.woocommerce_catalog_enquiry_custom_button_enquiry:hover {
					background: {$button_text_color_hover};
					color: {$button_background_color_hover};
				}
				#woocommerce_catalog_enquiry_custom_button {
					background: {$custom_btn_background};
					color: {$custom_btn_color};
					padding: {$custom_btn_padding};
					width: {$custom_btn_width};
					height: {$custom_btn_height};
					line-height: {$custom_btn_line_height};
					border-radius: {$custom_btn_border_radius};
					border: {$custom_btn_border};
					font-size: {$custom_btn_font_size};
					margin-top: {$custom_btn_margin_top};
					margin-bottom: {$custom_btn_margin_bottom};
					
				}
				#woocommerce_catalog_enquiry_custom_button:hover {
					background: {$custom_btn_hover_background};
					color: {$custom_btn_hover_color};
				}
				/* The Modal (background) */
				#woocommerce_catalog .catalog_modal {
				    display: none; /* Hidden by default */
				    position: fixed; /* Stay in place */
				    z-index: 100000; /* Sit on top */
				    /*padding-top: 100px;*/ /* Location of the box */
				    left: 0;
				    top: 0;
				    width: 100%; /* Full width */
				    height: 100%; /* Full height */
				    overflow: auto; /* Enable scroll if needed */
				    background-color: rgb(0,0,0); /* Fallback color */
				    background-color: 'rgba(0,0,0,0.4)'; /* Black w/ opacity */
				}";

                wp_add_inline_style('wce_frontend_css', $inline_css);
            }
            if (isset($settings['custom_css_product_page']) && $settings['custom_css_product_page'] != "") {
                wp_add_inline_style('wce_frontend_css', $settings['custom_css_product_page']);
            }
        }
    }
    
    public function wce_enquiry_button_shortcode(){
        global $Woocommerce_Catalog_Enquiry;
        $settings = $Woocommerce_Catalog_Enquiry->options_general_settings;

        if (isset($settings['is_enable']) && $settings['is_enable'] == "Enable" && ($this->available_for == '' || $this->available_for == 0)) {
            if (isset($settings['is_enable_enquiry']) && $settings['is_enable_enquiry'] == "Enable") {
                $piority = apply_filters('woocommerce_catalog_enquiry_button_possition_piority', 100);
                if (isset($settings['is_disable_popup']) && $settings['is_disable_popup'] == "Enable") {
                    remove_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry_without_popup'), $piority);
                    $this->add_form_for_enquiry_without_popup();
                } else {
                    remove_action('woocommerce_single_product_summary', array($this, 'add_form_for_enquiry'), $piority);
                    $this->add_form_for_enquiry();
                }
            }
        }
    }

    public function remove_pricing_from_catalog_orderby( $orderby ) {
        if( isset( $orderby['price'] ) ) unset( $orderby['price'] );
        if( isset( $orderby['price-desc'] ) ) unset( $orderby['price-desc'] );
        return $orderby;
    }

}
