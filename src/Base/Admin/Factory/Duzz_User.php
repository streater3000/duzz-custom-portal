<?php

namespace Duzz\Base\Admin\Factory;

use Duzz\Shared\Actions\Duzz_Format_Label;

class Duzz_User {
    public function __construct() {
        add_action('updated_option', array($this, 'duzz_admin_init'), 1, 3);
    }

    function duzz_admin_init($option_name, $old_value, $new_value) {
        // Get the email_options
        $email_options = get_option('settings_email_settings_field_data', array());

        // Get the new admin email and admin name
        $admin_email = isset($email_options['admin_email']) ? $email_options['admin_email'] : '';
        $admin_name = isset($email_options['admin_name']) ? $email_options['admin_name'] : '';
        $company_name = isset($email_options['company_name']) ? $email_options['company_name'] : '';

    // Make sure the email is lowercase
    $admin_email = strtolower($admin_email);

    // Capitalize the first letter of each word in the user's name and make the rest lowercase
    $admin_name = ucwords(strtolower($admin_name));
   $company_name = Duzz_Format_Label::format_company_name($company_name);

    // Check if the email or name has changed
    if (get_option('duzz_last_saved_email') !== $admin_email || get_option('duzz_last_saved_name') !== $admin_name || get_option('duzz_last_saved_company_name') !== $company_name) {
            update_option('duzz_last_saved_email', $admin_email);   
            wp_cache_delete('duzz_last_saved_email', 'options') ;
                
            update_option('duzz_last_saved_name', $admin_name); 
            wp_cache_delete('duzz_last_saved_name', 'options');

           update_option('duzz_last_saved_company_name', $company_name); 
            wp_cache_delete('duzz_last_saved_company_name', 'options');

        $user_id = 262;
        $user_login = strtolower(str_replace(' ', '_', $admin_name));

        // Check if the user with ID 262 exists
        $user = get_user_by('ID', $user_id);

            // Update the user if it exists
            $user_data = array(
                'ID' => $user_id,
                'user_login' => $user_login,
                'user_email' => $admin_email,
                'display_name' => $admin_name,
                'nickname' => $admin_name,
                'first_name' => $admin_name
            );

            wp_update_user($user_data);
        }
    
    // Update company name
    $updated_company = array(
        'ID'         => 9909,  // Use the post ID directly
        'post_title' => $company_name,
    );
    wp_update_post( $updated_company );

    // Update team name
    $updated_team = array(
        'ID'         => 9908,  // Use the post ID directly
        'post_title' => $company_name . ' Team',
    );
    wp_update_post( $updated_team );
}
}