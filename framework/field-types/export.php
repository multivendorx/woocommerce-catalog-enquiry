<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

$settings = array( );
foreach ( $this->options_general_settings as $k ) {
    switch ( $k[ 'type' ] ) {
        case 'setting':
            $s = get_option( $k[ 'id' ]);
            if(is_array($s)){
                $settings[$k[ 'id' ]] = $s; 
            }
            break;
    }
}

$export = json_encode($settings);

echo "<textarea id='$id' class='large-text'>" . $export . "</textarea><br>";

echo '
      <script>
      jQuery(document).ready(function($) {
        $("#export").click(function(){this.select()});
      });
      </script>';