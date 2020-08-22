<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db
if(empty($options[ $name ])){
	$options[ $name ] = '';
}
$content   = $options[ $name ];
$editor_id = $id;
$args      = array(
     'textarea_name' => "{$setting_id}[$name]",
     'textarea_rows' => 1
); 

wp_editor( $content, $editor_id, $args );