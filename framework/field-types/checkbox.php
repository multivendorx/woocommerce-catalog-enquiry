<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$c = 0;

/*if(empty($options[ $id ]) || !is_array($options[ $id ])){
	$options[ $id ] = array();
}*/
foreach ( $option_values as $k => $v ) {
    echo "<label class='switch'><input class='$id' type='checkbox' name='{$setting_id}[$id]' value='$k' " . ( ( isset($options[ $id ]) ? $options[ $id ] == "Enable" : '') ? 'checked' : '' ) . "  /><span class='slider round'></span></label> $v<br/>";
    $c++;
}

wp_enqueue_style('woo_check_css', $WC_Woocommerce_Catalog_Enquiry->plugin_url . 'framework/field-types/css/checkbox.css', array(), $WC_Woocommerce_Catalog_Enquiry->version);
