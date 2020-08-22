<?php
class Woocommerce_Catalog_Enquiry_Settings {
  
  private $tabs = array();
  
  private $options;
  
  /**
   * Start up
   */
  public function __construct() {
    // Admin menu
    add_action( 'admin_menu', array( $this, 'add_settings_page' ), 100 );
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

    add_submenu_page(
      'woo-catalog',
      __("Settings", 'woocommerce-catalog-enquiry'),
      __("Settings", 'woocommerce-catalog-enquiry'),
      'manage_options',
      'woo-catalog', array( $this,
      'option_page')
      );
    
    if( apply_filters( 'woocommerce_catalog_enquiry_menu_hide', true ) ) {
      add_submenu_page(
        'woo-catalog',
        __("Upgrade to Pro", 'woocommerce-catalog-enquiry'),
        __("Upgrade to Pro", 'woocommerce-catalog-enquiry'),
        'manage_options',
        '',
        array( $this, 'option_page' )
        );
    }

    
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
                          $$this->wpp_catalog_do_settings_sections($v[ 'id' ]);
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

                  <a class="image-adv" 
                  href="https://www.seedprod.com/landing/coming-soon-page-getting-started-video/?utm_source=coming-soon-plugin&utm_medium=banner&utm_campaign=coming-soon-banner-in-plugin"
                  target="_blank"><img
                  src="<?php echo plugins_url() .'/woocommerce-catalog-enquiry/'; ?>framework/getting-started-banner.png" /></a>
                  <br><br>

                  <a class="image-adv"><img
                  src="<?php echo plugins_url() .'/woocommerce-catalog-enquiry/'; ?>framework/coming-soon-pro-sidebar.png" /></a>
                  <br><br>
                  <div class="postbox ">
                    <div class="inside">
                      <div class="support-widget">
                        <p class="supt-link"><a
                          href="https://wordpress.org/support/plugin/woocommerce-catalog-enquiry/"
                          target="_blank"><?php _e('Got a Support Question', 'woocommerce-catalog-enquiry') ?></a> <i
                          class="fas fa-question-circle"></i>
                        </p>
                      </div>
                    </div>
                  </div>
                  
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
          if ($v[ 'id' ] == 'wcmp_catalog_from') {
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="admin.php?wcmp_catalog&tab=woo-catalog-from">';
          } else {
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="?page=' . $menu_slug . '&tab=' . $v[ 'id' ] . '">';
          }

          if( isset( $v[ 'font_class' ] ) ) {
            echo '<i class="fas '.$v[ 'font_class' ].'"></i> ';
          }

          // Add extra tab for pro version
          do_action( 'woocommerce_catalog_enquiry_added_extra_tab', $v );

          echo $v[ 'label' ] . '</a>';
          $c++;
        }
      }
      echo '<a class="nav-tab thickbox-preview" target="_blank" href="http://wcmpdemos.com/all-in-one-demo/my-account/" title="'.__('&larr; Close Window', 'woocommerce-catalog-enquiry').'"><i class="fas fa-tv"></i> '.__('Live Preview', 'woocommerce-catalog-enquiry').'</a>';
      
      // For free version only
      if( apply_filters( 'woocommerce_catalog_enquiry_free_active', true ) ){
        echo '<a class="nav-tab" style="background-color: #04be5b;color: #fff" href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank" rel="noopener noreferrer"><i class="fas fa-trophy"></i> '.__('Upgrade to Pro for More Features', 'woocommerce-catalog-enquiry').'</a>';
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
        echo '<th scope="row" class="' . $field['id'] . '"><strong>' . $field['title'] . '</strong><!--<br>'.$field['args']['desc'].'--></th>';
      }
      echo '<td>';
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