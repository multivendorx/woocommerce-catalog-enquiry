<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$option_values = array(
	'01'=>__('01-Jan','woocommerce-catalog-enquiry'),
	'02'=>__('02-Feb','woocommerce-catalog-enquiry'),
	'03'=>__('03-Mar','woocommerce-catalog-enquiry'),
	'04'=>__('04-Apr','woocommerce-catalog-enquiry'),
	'05'=>__('05-May','woocommerce-catalog-enquiry'),
	'06'=>__('06-Jun','woocommerce-catalog-enquiry'),
	'07'=>__('07-Jul','woocommerce-catalog-enquiry'),
	'08'=>__('08-Aug','woocommerce-catalog-enquiry'),
	'09'=>__('09-Sep','woocommerce-catalog-enquiry'),
	'10'=>__('10-Oct','woocommerce-catalog-enquiry'),
	'11'=>__('11-Nov','woocommerce-catalog-enquiry'),
	'12'=>__('12-Dec','woocommerce-catalog-enquiry'),
	);


echo "<select id='mm' name='{$setting_id}[$id][month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' name='{$setting_id}[$id][day]' placeholder='".__('day','woocommerce-catalog-enquiry')."' type='text' value='" . esc_attr( $options[ $id ]['day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' name='{$setting_id}[$id][year]' placeholder='".__('year','woocommerce-catalog-enquiry')."'  type='text' value='" . esc_attr( $options[ $id ]['year'] ) . "' /><br>";
