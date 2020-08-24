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

_e('Start Date', 'woocommerce-catalog-enquiry');
echo "<select id='mm' name='{$setting_id}[$id][start_month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['start_month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' placeholder='".__('day','woocommerce-catalog-enquiry')."' name='{$setting_id}[$id][start_day]' type='text' value='" . esc_attr( $options[ $id ]['start_day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' placeholder='".__('year','woocommerce-catalog-enquiry')."' name='{$setting_id}[$id][start_year]' type='text' value='" . esc_attr( $options[ $id ]['start_year'] ) . "' />";

echo '&nbsp;&nbsp;&nbsp;&nbsp;';
_e('End Date', 'woocommerce-catalog-enquiry');
echo "<select id='mm' name='{$setting_id}[$id][end_month]'>";
foreach ( $option_values as $k => $v ) {
    echo "<option value='$k' " . selected( $options[ $id ]['end_month'], $k, false ) . ">$v</option>";
}
echo "</select>";

echo "<input id='jj' class='small-text' placeholder='".__('day','woocommerce-catalog-enquiry')."' name='{$setting_id}[$id][end_day]' type='text' value='" . esc_attr( $options[ $id ]['end_day'] ) . "' />";

echo ',';
echo "<input id='aa' class='small-text' placeholder='".__('year','woocommerce-catalog-enquiry')."' name='{$setting_id}[$id][end_year]' type='text' value='" . esc_attr( $options[ $id ]['end_year'] ) . "' /><br>";

