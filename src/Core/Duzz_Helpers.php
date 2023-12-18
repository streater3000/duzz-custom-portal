<?php


namespace Duzz\Core;

use Duzz\Utils\Duzz_Keys;
use Duzz\Shared\Entity\Duzz_Staff_Keys;

class Duzz_Helpers{

public static function duzz_get_field($field_name, $post_id = false, $format_value = true) {
  if (function_exists('acf_form_head')) {
    return get_field(Duzz_Get_Data::duzz_get_form_id('duzz_acf_settings_acf_keys_list_field_data', $field_name), $post_id, $format_value);
  } else {
    // ACF is not active, handle the error gracefully
    return get_post_meta($post_id, $field_name, true);
  }
}

public static function duzz_update_field($field_name, $value, $post_id = false) {
    if (function_exists('acf_form_head')) {
        // Using ACF's update_field
        return update_field(Duzz_Get_Data::duzz_get_form_id('duzz_acf_settings_acf_keys_list_field_data', $field_name), $value, $post_id);
    } else {
        // ACF is not active, fallback to WordPress's core function
        return update_post_meta($post_id, $field_name, $value);
    }
}

public static function duzz_get_field_object($field_name, $post_id = false) {
    if (function_exists('acf_form_head')) {
        // Using ACF's get_field_object
        return get_field_object(Duzz_Get_Data::duzz_get_form_id('duzz_acf_settings_acf_keys_list_field_data', $field_name), $post_id);
    } else {
        // ACF is not active, return a basic structure with only the value
        $value = get_post_meta($post_id, $field_name, true);
        return array(
            'key' => $field_name,
            'value' => $value
        );
    }
}


/**
 * Create a new user with custom meta data.
 */
public static function duzz_create_user( $first_name, $last_name, $email, $password, $role, $meta ) {
	if ( ! username_exists( $email ) && ! email_exists( $email ) ) {
		$userdata = array(
			'user_pass'            => $password,
			'user_login'           => $email,
			'user_email'           => $email,
			'display_name'         => $first_name . ' ' . $last_name,
			'first_name'           => $first_name,
			'last_name'            => $last_name,
			'show_admin_bar_front' => 'false',
			'role'                 => $role,
		);
		$id = wp_insert_user( $userdata );

		foreach ( $meta as $k => $v ) {
			update_field( $k, $v, 'user_' . $id );
		}

		return $id;
	}
	return email_exists( $email );
}

/**
 * Get company_id when we have the team_id
 */
public static function duzz_get_company_by_team_id( $id ) {
	return duzz_get_field( 'company_id', $id );
}

/**
 * Get company_id when we have the staff_id
 */
public static function duzz_get_company_by_staff_id( $id ) {
	return self::duzz_get_field(\Duzz\Shared\Entity\Duzz_Staff_Keys::$company_id, 'user_' .$id);
}

/**
 * Get company_id when we have the staff_id
 */
public static function duzz_get_company_by_project_id( $id ) {
	return duzz_get_field( Duzz_Keys::duzz_get_acf_key('company_id'), $id );
}

/**
 * Get team_id when we have the staff_id
 */
public static function duzz_get_team_by_staff_id( $id ) {
	return self::duzz_get_field(\Duzz\Shared\Entity\Duzz_Staff_Keys::$team_id, 'user_' .$id);
}

/**
 * Compare two roles.
 * If role 1 is higher than role 2 it will return 1
 * If role 1 is equal to role 2 it will return 0
 * If role 1 is lower than role 2 it will return -1
 */
public static function duzz_compare_roles( $role1, $role2 ) {

	if ( $role1 === $role2 ) {
		return 0;
	}

	if ( $role1 === 'administrator' ) {
		return 1;
	}

	if ( $role1 === 'duzz_staff' ) {

		switch ($role2) {
			case 'duzz_team_leader':
			case 'duzz_subadmin':
			case 'duzz_admin':
			case 'administrator':
				return -1;
				break;

			default:
				return -1;
				break;
		}

	}

	if ( $role1 === 'duzz_team_leader' ) {

		switch ($role2) {
			case 'duzz_staff':
				return 1;
				break;

			case 'duzz_subadmin':
			case 'duzz_admin':
			case 'administrator':
				return -1;
				break;

			default:
				return -1;
				break;
		}

	}

	if ( $role1 === 'duzz_subadmin' ) {

		switch ($role2) {
			case 'duzz_staff':
			case 'duzz_team_leader':
				return 1;
				break;

			case 'duzz_admin':
			case 'administrator':
				return -1;
				break;

			default:
				return -1;
				break;
		}

	}

	if ( $role1 === 'duzz_admin' ) {

		switch ($role2) {
			case 'duzz_staff':
			case 'duzz_team_leader':
			case 'duzz_subadmin':
				return 1;
				break;

			case 'administrator':
				return -1;
				break;

			default:
				return -1;
				break;
		}

	}

	if ( $role1 === 'administrator' ) {

		switch ($role2) {
			case 'duzz_staff':
			case 'duzz_team_leader':
			case 'duzz_subadmin':
			case 'duzz_admin':
				return 1;
				break;

			default:
				return -1;
				break;
		}

	}

}

/**
 * Get role label when we have the id.
 */
public static function duzz_get_role_name( $role ) {

	if ( is_array( $role ) ) {
		$role = $role[0] ?? false;
	}

	switch ($role) {
		case 'no_role':
			return 'Missing Role';
			break;
		case 'logged_out':
			return 'Logged Out';
			break;
		case 'duzz_customer':
			return 'Customer';
			break;
		case 'duzz_bot':
			return 'Bot';
			break;
		case 'duzz_staff':
			return 'Team Member';
			break;
		case 'duzz_team_leader':
			return 'Team Leader';
			break;
		case 'duzz_subadmin':
			return 'Sub Admin';
			break;
		case 'duzz_admin':
			return 'Admin';
			break;
		case 'administrator':
			return 'Superadmin';
			break;

		default:
			return 'Missing Role';
			break;
	}
}


public static function duzz_get_name($id) {

        // If ACF is not active, use WordPress core functions
        $user = get_userdata($id);

            $fname = $user->first_name;
            $lname = $user->last_name;
      
    
    return $fname . ' ' . $lname;
}

public static function duzz_get_projectname( $project_id ) {
	$project_id = absint($project_id );
	$fname = self::duzz_get_field( 'customer_first_name', $project_id ) ?: '';
	$lname = self::duzz_get_field( 'customer_last_name', $project_id ) ?: '';
	$customer_name = $fname . ' ' . $lname;
	return $customer_name;
}

public static function duzz_get_projectemail($project_id) {
    $project_id = absint($project_id);
    $email = self::duzz_get_field('customer_email', $project_id) ?: '';
    return $email;
}

}


