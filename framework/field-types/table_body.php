<?php
// From fields
$from_fields = apply_filters( 'woo_catalog_from_fileds', array(
"name_label" => 
    array('title' => __('Custom label for name', 'woocommerce-catalog-enquiry'), 
        'id' => 'name_label', 
        'label_for' => 'name_label',         
        ),

"email_label" => 
    array('title' => __('Custom label for email', 'woocommerce-catalog-enquiry'), 
        'id' => 'email_label', 
        'label_for' => 'email_label', 
        ),
"is_captcha" => 
    array(
     'title' => __('Captcha Enable ?', 'woocommerce-catalog-enquiry'),
     'id' => 'is_captcha', 
     'label_for' => 'captcha_label', 
     'values'=> 'Enable',
     ),
"is_subject" => 
    array('title' => __('Subject Field Enable ?', 'woocommerce-catalog-enquiry'),
        'id' => 'is_subject',
        'label_for' => 'subject_label',
        'values'=> 'Enable' 
        
        ),
"is_phone" => 
    array('title' => __('Phone Field Enable ?', 'woocommerce-catalog-enquiry'),
     'id' => 'is_phone', 
     'label_for' => 'phone_label', 
     'values'=> 'Enable' 
     
     ),
"is_address" => 
    array('title' => __('Address Field Enable ?', 'woocommerce-catalog-enquiry'), 
        'id' => 'is_address', 
        'label_for' => 'address_label',
        'values'=> 'Enable'  
        
        ),
"is_comment" => 
    array('title' => __('Comment Field Enable ?', 'woocommerce-catalog-enquiry'), 
        'id' => 'is_comment', 
        'label_for' => 'comment_label', 
        'values'=> 'Enable' 
       ),
"is_fileupload" => 
    array('title' => __('File Upload Field Enable ?', 'woocommerce-catalog-enquiry'), 
        'id' => 'is_fileupload', 
        'label_for' => 'fileupload_label', 
        'values'=> 'Enable' 
        ),

"filesize_limit" => 
    array('title' => __('File Upload Size Limit', 'woocommerce-catalog-enquiry'), 
        'id' => 'filesize_limit', 
        'label_for' => 'filesize_limit', 
        ),

"captcha_input_label" => 
    array('title' => __('Custom label for captcha Input', 'woocommerce-catalog-enquiry'), 
        'id' => 'captcha_input_label', 
        'label_for' => 'captcha_input_label', 
        ),
    )
);
// from heading
$from_heading = apply_filters( 'woo_catalog_from_heading', array(
    __( "Field Name", 'woocommerce-catalog-enquiry' ),
    __( "Checkbox", 'woocommerce-catalog-enquiry' ),
    __( "Label", 'woocommerce-catalog-enquiry' )
    
    ) );

?>

<table class="table table-bordered responsive-table wcmp_catalog_from_setting widefat">
    <thead>
        <tr>
        <?php
        foreach ($from_heading as $key_heading => $value_heading) {
        ?>
            <th>
                <?php echo $value_heading; ?>
            </th>  
        <?php
        }
        ?>        
        </tr>
    </thead>
    <tbody>
    <?php
       foreach ($from_fields as $key => $value) {
        ?>
        <tr>
            <td >
                <?php
                echo $value['title'];
                ?>
            </td>
            <td>
                <?php 
                $ids = $value['id' ];
                if( isset($value['values' ] ) ){
                    echo "<label class='switch'><input class='".$value['id']."' type='checkbox' name='{$setting_id}[$ids]' value='".$value['values']."' " . ( ( isset($options[ $ids ]) ? $options[ $ids ] == "Enable" : '') ? 'checked' : '' ) . "  /><span class='slider round'></span></label><br/>";
                }
                ?>
            </td>
            <td>
                <?php
                if( isset($value['label_for' ] ) ){
                    $extra = isset( $value['label_for' ] ) ? $value['label_for' ] : '';

                    echo "<input id='".$extra."' class='regular-text' name='{$setting_id}[$extra]' type='text' value='" . esc_attr( isset( $options[ $extra ] ) ? $options[ $extra ] : '' ) . "' /><br>";
                }
                ?> 
            </td>
        </tr>
         <?php
        }
        ?>
    </tbody>
</table>