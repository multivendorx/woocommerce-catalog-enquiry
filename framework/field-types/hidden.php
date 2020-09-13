<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
if(empty($options[ $name ])){
	$options[ $name ] = '';
}
echo "<input id='$id' class='" . ( empty( $class ) ? '' : 'hidden' ) . "' name='{$setting_id}[$name]' type='hidden' value='" . esc_attr( $options[ $name ] ) . "' /><br>";