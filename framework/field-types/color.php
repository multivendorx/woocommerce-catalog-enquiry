<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
global $Woocommerce_Catalog_Enquiry;
if(!isset($options[ $name ])){
	$options[ $name ] = '';
}

echo "<input id='$id' class='pickcolor-field' type='text' name='{$setting_id}[$name]' value='" . esc_attr( $options[ $name ] ) . "' style='background-color:" . ( empty( $options[ $name ] ) ? $default_value : $options[ $name ] ) . ";' />";

wp_enqueue_script( 'woocatalog-color-js', $Woocommerce_Catalog_Enquiry->plugin_url . 'framework/field-types/js/color.js', array(
     'wp-color-picker' 
),false, true );