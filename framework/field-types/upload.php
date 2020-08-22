<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
global$Woocommerce_Catalog_Enquiry;
if(!isset($options[ $id ]))
	$options[ $id ] = null;


echo "<input id='$id' class='" . ( empty( $class ) ? 'regular-text' : $class ) . "' name='{$setting_id}[$id]' type='text' value='" . esc_attr( $options[ $id ] ) . "' />";
echo "<input id='{$id}_upload_image_button' class='button-secondary upload-button' type='button' value='" . __( 'Media Image Library', 'woocommerce-catalog-enquiry' ) . "' /><br>";

wp_enqueue_script( 'woocommerce-catalog-upload-js', $Woocommerce_Catalog_Enquiry->plugin_url . 'framework/field-types/js/upload.js', array() );
wp_enqueue_media();