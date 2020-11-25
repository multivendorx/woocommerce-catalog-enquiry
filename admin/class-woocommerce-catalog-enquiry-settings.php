<?php
class Woocommerce_Catalog_Enquiry_Settings {
  
  private $tabs = array();
  
  private $options;
  
  /**
   * Start up
   */
  public function __construct() {
    // Admin menu
    add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
    add_action( 'admin_init', array( $this, 'settings_page_init' ) );
  }
  
  /**
   * Add options page
   */
  public function add_settings_page() {

    add_menu_page(
      "Catalog",
      "Catalog",
      'manage_options',
      'woo-catalog',array( $this,
      'option_page' ), 'dashicons-store',
      50
      );
    $setting_text = apply_filters( 'woocommer_catalog_general_setting_text', __('Settings', 'woocommerce-catalog-enquiry') );
    add_submenu_page(
      'woo-catalog',
      $setting_text,
      $setting_text,
      'manage_options',
      'woo-catalog', array( $this,
      'option_page')
      );

    if( apply_filters( 'woocommerce_catalog_enquiry_free_active', true ) ) {
      add_submenu_page(
        'woo-catalog',
        __("Upgrade to Pro", 'woocommerce-catalog-enquiry'),
        '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . __( 'Upgrade to Pro', 'woocommerce-catalog-enquiry' ),
        'manage_options',
        '',
        array( $this, 'handle_external_redirects' )
        );
    }
  }

  // Upgrade to pro link
  public function handle_external_redirects() {
    wp_redirect( 'https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/' );
    die;
  }

  public function option_page(){
    global $Woocommerce_Catalog_Enquiry;
    $menu_slug = null;
    $page   = $_REQUEST[ 'page' ];
    $layout = $this->woocommerce_catalog_get_page_layout(); ?>
    <div class="">
      <?php $this->woocommerce_catalog_plugin_options_tabs(); ?>
      <div class="wcmp-catalog-space">
        <?php if ($layout == '2-col'): ?>
          <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
              <div id="post-body-content">
              <?php endif; ?>
              <form action="options.php" method="post">
                <?php
                $show_submit = false;
                foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
                  if (isset($v[ 'menu_slug' ])) {
                    $menu_slug = $v[ 'menu_slug' ];
                  }
                  if ($menu_slug == $page) {
                    switch ($v[ 'type' ]) {
                      case 'menu':
                      break;
                      case 'tab':
                      $tab = $v;
                      if (empty($default_tab)) {
                        $default_tab = $v[ 'id' ];
                      }
                      break;
                      case 'setting':
                      $current_tab = isset($_GET[ 'tab' ]) ? $_GET[ 'tab' ] : $default_tab;
                      if ($current_tab == $tab[ 'id' ]) {
                        settings_fields($v[ 'id' ]);
                        $show_submit = true;
                      }

                      break;
                      case 'section':
                      $current_tab = isset($_GET[ 'tab' ]) ? $_GET[ 'tab' ] : $default_tab;
                      if ($current_tab == $tab[ 'id' ] or $current_tab === false) {
                        if ($layout == '2-col') {
                          echo '<div id="'.$v[ 'id' ].'" class="postbox">';
                          $this->wpp_catalog_do_settings_sections($v[ 'id' ], $show_submit);
                          echo '</div>';
                        } else {
                          $this->wpp_catalog_do_settings_sections($v[ 'id' ]);
                        }
                      }
                      break;
                    }
                  }
                } ?>
              </form>

              <?php if ($layout == '2-col'): ?>
              </div> <!-- #post-body-content -->
              <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                <?php if( apply_filters( 'woocommerce_catalog_enquiry_free_active', true ) ) { ?>
                  <a class="image-adv" 
                  href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/"
                  target="_blank"><img
                  src="<?php echo plugins_url() .'/woocommerce-catalog-enquiry/'; ?>framework/Catalog-Pro-Banner.jpg" /></a>
                  <br><br>

                  <div class="postbox ">
                    <div class="inside">
                      <div class="support-widget">
                        <p class="supt-link"><a
                          href="https://wordpress.org/support/plugin/woocommerce-catalog-enquiry/"
                          target="_blank"><?php _e('Got a Support Question', 'woocommerce-catalog-enquiry') ?></a>
                        </p>
                      </div>
                    </div>
                  </div>
                <?php } 
                // Additional banner for pro version
                do_action( 'woocommerce_catalog_enquiry_additional_banner' );
                ?>
              </div>
            </div>
          </div> <!-- #post-body -->
        </div> <!-- #poststuff -->
      <?php endif; ?>
    </div> <!-- .wrap -->
  </div>

  <?php
    // Wc marketplace admin footer
    do_action('woocommerce_catalog_enquiry_admin_footer');

  }

  public function woocommerce_catalog_get_page_layout() {
    global $Woocommerce_Catalog_Enquiry;
    $layout = 'classic';
    foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
      switch ($v[ 'type' ]) {
        case 'menu':
        $page = $_REQUEST[ 'page' ];
        if ($page == $v[ 'menu_slug' ]) {
          if (isset($v[ 'layout' ])) {
            $layout = $v[ 'layout' ];
          }
        }
        break;
      }
    }
    return $layout;
  }

  public function woocommerce_catalog_plugin_options_tabs() {
    global $Woocommerce_Catalog_Enquiry;
    $menu_slug   = null;
    $page        = $_REQUEST[ 'page' ];
    $uses_tabs   = false;
    $current_tab = isset($_GET[ 'tab' ]) ? $_GET[ 'tab' ] : false;

    //Check if this config uses tabs
    foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
      if ($v[ 'type' ] == 'tab') {
        $uses_tabs = true;
        break;
      }
    }
    // If uses tabs then generate the tabs
    if ($uses_tabs) {
      echo '<h2 class="nav-tab-wrapper">';
      $c = 1;
      foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
        if (isset($v[ 'menu_slug' ])) {
          $menu_slug = $v[ 'menu_slug' ];
        }
        if ($menu_slug == $page && $v[ 'type' ] == 'tab') {
          $active = '';
          if ($current_tab) {
            $active = $current_tab == $v[ 'id' ] ? 'nav-tab-active' : '';
          } elseif ($c == 1) {
            $active = 'nav-tab-active';
          }
          if ($v[ 'id' ] == 'wcmp-catalog-from') {
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="admin.php?woo-catalog&tab=woo-catalog-from">';
          } else {
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="?page=' . $menu_slug . '&tab=' . $v[ 'id' ] . '">';
          }

          if( isset( $v[ 'font_class' ] ) ) {
            echo '<i class="dashicons '.$v[ 'font_class' ].'"></i> ';
          }

          // Add extra tab for pro version
          do_action( 'woocommerce_catalog_enquiry_add_additional_tabs', $v );

          echo $v[ 'label' ] . '</a>';
          $c++;
        }
      }
      echo '<a class="nav-tab thickbox-preview" target="_blank" href="http://wcmpdemos.com/all-in-one-demo/my-account/" title="'.__('&larr; Close Window', 'woocommerce-catalog-enquiry').'"><i class="dashicons dashicons-format-video"></i> '.__('Live Preview', 'woocommerce-catalog-enquiry').'</a>';
      
      // For free version only
      if( apply_filters( 'woocommerce_catalog_enquiry_free_active', true ) ){
        echo '<a class="nav-tab woocommerce-catalog-upgrade" href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank" rel="noopener noreferrer"><i class="dashicons dashicons-star-filled"></i> '.__('Upgrade to Pro for More Features', 'woocommerce-catalog-enquiry').'</a>';
      }

      // Add extra tab for pro version
      do_action( 'woocommerce_catalog_enquiry_added_extra_tab_after', $v );

      echo '</h2>';
    }   
  }

    public function wpp_catalog_do_settings_sections($page, $show_submit) {
      global $wp_settings_sections, $wp_settings_fields;
      if (!isset($wp_settings_sections) || !isset($wp_settings_sections[ $page ])) {
        return;
      }
      foreach ((array) $wp_settings_sections[ $page ] as $section) {
        echo '<div class="postbox-header">';
        echo "<h3 class='hndle'>{$section['title']}</h3>\n";
        echo '</div>';
        echo '<div class="inside">';
        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[ $page ]) || !isset($wp_settings_fields[ $page ][ $section[ 'id' ] ])) {
          continue;
        }
        echo '<table class="form-table">';
        $this->woocommerce_catalog_do_settings_fields($page, $section[ 'id' ]);
        echo '</table>';
        if ($show_submit): ?>
        <p>
          <input name="submit" type="submit" value="<?php _e('Save All Changes', 'woocommerce-catalog-enquiry'); ?>" class="button-primary" />
        </p>
      <?php endif;
      echo '</div>';
    }
  }

  function woocommerce_catalog_do_settings_fields($page, $section) {
    global $wp_settings_fields;

    if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section])) {
      return;
    }
    foreach ((array) $wp_settings_fields[$page][$section] as $field) {
      echo '<tr valign="top">';
      if (!empty($field['args']['label_for'])) {
        echo '<th scope="row"><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label></th>';
      } else {
        $fields_description = isset($field['args']['desc']) ? $field['args']['desc'] : '';
        echo '<th scope="row" class="' . $field['id'] . '"><strong>' . $field['title'] . '</strong><!--<br>'.$fields_description.'--></th>';
      }
      echo '<td>';
      do_action('field_start_' . $field['id']);
      call_user_func($field['callback'], $field['args']);
      echo '</td>';
      echo '</tr>';
    }
  }

  /**
   * Register and add settings
   */
  public function settings_page_init() { 
    global $Woocommerce_Catalog_Enquiry;
    foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $k => $v) {
      switch ($v[ 'type' ]) {
        case 'menu':
        $menu_slug = $v[ 'menu_slug' ];

        break;
        case 'setting':
        if (empty($v[ 'validate_function' ])) {
          $v[ 'validate_function' ] = array(
            &$this,
            'validate_machine'
            );
        }
        register_setting($v[ 'id' ], $v[ 'id' ], $v[ 'validate_function' ]);
        $setting_id = $v[ 'id' ];
        break;
        case 'section':
        if (empty($v[ 'desc_callback' ])) {
          $v[ 'desc_callback' ] = array(
            &$this,
            'return_empty_string'
            );
        } else {
          $v[ 'desc_callback' ] = $v[ 'desc_callback' ];
        }
        add_settings_section($v[ 'id' ], $v[ 'label' ], $v[ 'desc_callback' ], $v[ 'id' ]);
        $section_id = $v[ 'id' ];
        break;
        case 'tab':
        break;
        default:
        if (empty($v[ 'callback' ])) {
          $v[ 'callback' ] = array($this, 'field_machine');
        }

        add_settings_field($v[ 'id' ], $v[ 'label' ], $v[ 'callback' ], $section_id, $section_id, apply_filters( 'woocommerce_catalog_add_settings_field', array(
          'id' => $v[ 'id' ],
          'name' => (isset($v[ 'name' ]) ? $v[ 'name' ] : ''),
          'desc' => (isset($v[ 'desc' ]) ? $v[ 'desc' ] : ''),
          'setting_id' => $setting_id,
          'class' => (isset($v[ 'class' ]) ? $v[ 'class' ] : ''),
          'type' => $v[ 'type' ],
          'default_value' => (isset($v[ 'default_value' ]) ? $v[ 'default_value' ] : ''),
          'option_values' => (isset($v[ 'option_values' ]) ? $v[ 'option_values' ] : ''),
          'extra_input' => (isset($v[ 'extra_input' ]) ? $v[ 'extra_input' ] : ''),
          'font_class' => (isset($v[ 'font_class' ]) ? $v[ 'font_class' ] : '')
          ), $v ));

      }
    }
    add_action('field_start_custom_enquiry_buttons_css', array($this, 'custom_enquiry_buttons_css_html_callback')); 
  }

  public function custom_enquiry_buttons_css_html_callback() {
    
    global $WCMP_Woocommerce_Catalog_Enquiry;
    $extra_fonts = apply_filters('wcce_catalog_enquiry_extra_button_style_fonts',array());
    $extra_fonts_options = '';
    if(!empty($extra_fonts) && is_array($extra_fonts)){
      foreach ($extra_fonts as $key => $value) {
        $extra_fonts_options .= '<option value="'.$value.', Helvetica, Arial, Sans-Serif">'.$value.'</option>';
      }
    }
    $html = '<div id="Enquiry_Btn_wrapper">
    <div class="controls">
      <div>
        <label>Button Size:</label> 
        <div class="sliderBar" id="sizer"><div id="sizer-handle" class="ui-slider-handle"></div></div>
      </div>
      <div>
        <label>Font Size:</label> 
        <div class="sliderBar" id="font-sizer"><div id="font-sizer-handle" class="ui-slider-handle"></div></div>
      </div>
      <div>
        <label>Border Radius:</label>
        <div class="sliderBar" id="border-rounder"><div id="border-rounder-handle" class="ui-slider-handle"></div></div>
      </div>
      <div>
        <label>Border Size:</label>
        <div class="sliderBar" id="border-sizer"><div id="border-sizer-handle" class="ui-slider-handle"></div></div>
      </div>
      <div id="colors">
        <!--div class="background-color-control">
        <label>
          Solid Background color: <input type="radio" name="backgroundColor" checked>
        </label>
        <label>
          Gradient Background color: <input type="radio" name="backgroundColor">
        </label>
      </div-->
      <div>
        <label for="topGradientValue">Top Gradient Color</label>
        <input type="text" maxlength="6" size="6" id="topGradientValue" class="pickable backgroundTop" rel="backgroundTop" value="3e779d" style="background: #3e779d;" />
      </div>
      <div>
        <label for="bottomGradientValue">Bottom Gradient Color</label>
        <input type="text" maxlength="6" size="6" id="bottomGradientValue" class="pickable backgroundBottom" rel="backgroundBottom" value="65a9d7" style="background: #65a9d7;" />
      </div>
      <div>
        <label for="borderTopColorValue">Border Color</label>
        <input type="text" maxlength="6" size="6" id="borderTopColorValue" class="pickable borderColor" rel="borderColor" value="96d1f8" style="background: #96d1f8;" />
      </div>
      <div>
        <label for="hoverBackgroundColorValue">Hover Background Color</label>
        <input type="text" maxlength="6" size="6" id="hoverBackgroundColorValue" class="pickable hoverBackground" rel="hoverBackground" value="28597a" style="background: #28597a;" />
      </div>
      <div>
        <label for="textColor">Text Color</label>
        <input type="text" maxlength="6" size="6" id="textColor" class="pickable textColor" rel="textColor" value="white" style="background: white;" />
      </div>
      <div>
        <label for="hoverTextColorValue">Hover Text Color</label>
        <input type="text" maxlength="6" size="6" id="hoverTextColorValue" class="pickable hoverColor" rel="hoverColor" value="cccccc" style="background: #cccccc;" />
      </div>
      <div>
        <label for="activeBackgroundColor">Active Background Color</label>
        <input type="text" maxlength="6" size="6" id="activeBackgroundColor" class="pickable activeBackground" rel="activeBackground" value="1b435e" style="background: #1b435e;" />
      </div>
      <div>
        <label for="fontSelector">Select Font: </label>
        <select id="fontSelector">
          <option value="">Default</option>
          <option value="Helvetica, Arial, Sans-Serif">Helvetica</option>
          <option value="Georgia, Serif">Georgia</option>
          <option value="Lucida Grande, Helvetica, Arial, Sans-Serif">Lucida Grande</option>'.$extra_fonts_options.'
        </select>
      </div>
    </div>
    </div>
    <div class="button-box">
      <a href="#" class="custom_enquiry_buttons_css_new previewbutton">Example Enquiry</a>
    </div> 
    </div>';
    echo $html;
  }

  public function field_machine($args) {
    global $Woocommerce_Catalog_Enquiry;
    extract($args); //$id, $desc, $setting_id, $class, $type, $default_value, $option_values
    // Load defaults
    $defaults = array( );
    foreach ($Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $k) {
      switch ($k[ 'type' ]) {
        case 'setting':
        case 'section':
        case 'tab':
        break;
        default:
        if (isset($k[ 'default_value' ])) {
          $defaults[ $k[ 'id' ] ] = $k[ 'default_value' ];
        }
      }
    }
    $options = get_option($setting_id);

    $options = wp_parse_args($options, $defaults);
    $path = $Woocommerce_Catalog_Enquiry->plugin_path . 'framework/field-types/' . $type . '.php';
    if (file_exists($path)) {
      // Show Field
      include($path);
      // Show description
      if (!empty($desc)) {
        echo "<small class='description'>{$desc}</small>";
      }
    }
  }
  
}