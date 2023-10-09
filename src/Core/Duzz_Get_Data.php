<?php 

namespace Duzz\Core;

use Duzz\Base\Admin\Duzz_Admin_Menu_Items;

class Duzz_Get_Data {
    public static function duzz_get_form_id($options_name, $saved_value) {
        $saved_options = get_option($options_name, []);
        return isset($saved_options[$saved_value]) ? $saved_options[$saved_value] : null;
    }

public static function duzz_get_duzz_connector_field_data($option_name) {
    return get_option( $option_name, [] );
}


public static function duzz_get_field_names($listType) {
        $field_data = Duzz_Admin_Menu_Items::duzz_settings_list_data($listType);
        return array_keys($field_data);
    }

    public static function duzz_get_field_values($option_name) {
    $field_data = get_option($option_name, []);
    return array_values($field_data);
}


}