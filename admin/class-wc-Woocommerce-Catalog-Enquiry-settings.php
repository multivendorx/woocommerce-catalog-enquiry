<?php
class WC_Woocommerce_Catalog_Enquiry_Settings {
  
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
      'wcmp_catalog',array( $this,
      'option_page' ), '',
      50
      );

    add_submenu_page(
      'wcmp_catalog',
      __("Settings", 'woocommerce-catalog-enquiry'),
      __("Settings", 'woocommerce-catalog-enquiry'),
      'manage_options',
      'wcmp_catalog', array( $this,
      'option_page')
      );

    add_submenu_page(
      'wcmp_catalog',
      __("Upgrade to pro", 'woocommerce-catalog-enquiry'),
      __("Upgrade to pro", 'woocommerce-catalog-enquiry'),
      'manage_options',
      '',
      array( $this, 'option_page' )
      );
  }

  public function option_page(){
    global $WC_Woocommerce_Catalog_Enquiry;
    $menu_slug = null;
    $page   = $_REQUEST[ 'page' ];
    $layout = $this->woo_catalog_get_page_layout(); ?>
    <div class="">
      <?php $this->woo_catalog_plugin_options_tabs(); ?>
      <div class="">
        <?php if ($layout == '2-col'): ?>
          <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
              <div id="post-body-content">
              <?php endif; ?>
              <form action="options.php" method="post">
                <?php
                $show_submit = false;
                foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
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
                    <div class="handlediv" title="Click to toggle"><br /></div>
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
  }

  public function woo_catalog_get_page_layout() {
    global $WC_Woocommerce_Catalog_Enquiry;
    $layout = 'classic';
    foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
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

  public function woo_catalog_plugin_options_tabs() {
    global $WC_Woocommerce_Catalog_Enquiry;
    $menu_slug   = null;
    $page        = $_REQUEST[ 'page' ];
    $uses_tabs   = false;
    $current_tab = isset($_GET[ 'tab' ]) ? $_GET[ 'tab' ] : false;

    //Check if this config uses tabs
    foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
      if ($v[ 'type' ] == 'tab') {
        $uses_tabs = true;
        break;
      }
    }
    // If uses tabs then generate the tabs
    if ($uses_tabs) {
      echo '<h2 class="nav-tab-wrapper" style="padding-left:20px">';
      $c = 1;
      foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $v) {
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
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="admin.php?wcmp_catalog&tab=woo_catalog_from">';
          } else {
            echo '<a id="'.$v[ 'id' ].'" class="nav-tab ' . $active . '" href="?page=' . $menu_slug . '&tab=' . $v[ 'id' ] . '">';
          }
          if ($v[ 'id' ] == 'woo_catalog_general') {
            echo '<i class="fas fa-edit"></i> ';
          }
          if ($v[ 'id' ] == 'woo_catalog_button') {
            echo '<i class="fas fa-image"></i> ';
          }
          if ($v[ 'id' ] == 'woo_catalog_exclusion') {
            echo '<i class="fas fa-users"></i> ';
          }
          if ($v[ 'id' ] == 'woo_catalog_from') {
            echo '<i class="fas fa-code"></i> ';
          }
          echo $v[ 'label' ] . '</a>';
          $c++;
        }
      }
      echo '<a class="nav-tab thickbox-preview" target="_blank" href="http://wcmpdemos.com/all-in-one-demo/my-account/" title="'.__('&larr; Close Window', 'woocommerce-catalog-enquiry').'"><i class="fas fa-external-link-alt"></i> '.__('Live Preview', 'woocommerce-catalog-enquiry').'</a>';

      echo '<a class="nav-tab" style="background-color: #04be5b;color: #fff" href="https://wc-marketplace.com/product/woocommerce-catalog-enquiry-pro/" target="_blank" rel="noopener noreferrer"><i class="fas fa-star"></i> '.__('Upgrade to Pro for More Features', 'woocommerce-catalog-enquiry').'</a>';

      echo '</h2>';
    }   
  }

    public function wpp_catalog_do_settings_sections($page, $show_submit) {
      global $wp_settings_sections, $wp_settings_fields;
      if (!isset($wp_settings_sections) || !isset($wp_settings_sections[ $page ])) {
        return;
      }
      foreach ((array) $wp_settings_sections[ $page ] as $section) {
        echo "<h3 class='hndle'>{$section['title']}</h3>\n";
        echo '<div class="inside">';
        if (!isset($wp_settings_fields) || !isset($wp_settings_fields[ $page ]) || !isset($wp_settings_fields[ $page ][ $section[ 'id' ] ])) {
          continue;
        }
        echo '<table class="form-table">';
        $this->woo_catalog_do_settings_fields($page, $section[ 'id' ]);
        echo '</table>';
        if ($show_submit): ?>
        <p>
          <input name="submit" type="submit" value="<?php _e('Save All Changes', 'woocommerce-catalog-enquiry'); ?>" class="button-primary" />
        </p>
      <?php endif;
      echo '</div>';
    }
  }

  function woo_catalog_do_settings_fields($page, $section) {
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
    global $WC_Woocommerce_Catalog_Enquiry;
    foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $k => $v) {
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

        add_settings_field($v[ 'id' ], $v[ 'label' ], $v[ 'callback' ], $section_id, $section_id, array(
          'id' => $v[ 'id' ],
          'desc' => (isset($v[ 'desc' ]) ? $v[ 'desc' ] : ''),
          'setting_id' => $setting_id,
          'class' => (isset($v[ 'class' ]) ? $v[ 'class' ] : ''),
          'type' => $v[ 'type' ],
          'default_value' => (isset($v[ 'default_value' ]) ? $v[ 'default_value' ] : ''),
          'option_values' => (isset($v[ 'option_values' ]) ? $v[ 'option_values' ] : ''),
          'extra_input' => (isset($v[ 'extra_input' ]) ? $v[ 'extra_input' ] : '')
          ));

      }
    } 
  }

  public function field_machine($args) {
    global $WC_Woocommerce_Catalog_Enquiry;
    extract($args); //$id, $desc, $setting_id, $class, $type, $default_value, $option_values
    // Load defaults
    $defaults = array( );
    foreach ($WC_Woocommerce_Catalog_Enquiry->library->catalog_enquiry_get_options() as $k) {
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
    $path = $WC_Woocommerce_Catalog_Enquiry->plugin_path . 'framework/field-types/' . $type . '.php';
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