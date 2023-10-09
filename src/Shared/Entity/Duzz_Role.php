<?php 

namespace Duzz\Shared\Entity;

class Duzz_Role {
    /**
     * Returns the user role.
     */
    function duzz_get_user_role() {
        global $current_user;
        $user_roles = (array) $current_user->roles;
        $user_role = $user_roles[0] ?? false;

        if (empty($user_role)) {
            return 'logged_out';
        }

        return $user_role;
    }

    /**
     * Returns the user role.
     */
    function duzz_get_user_role_by_id($id) {
        if (empty($id)) {
            return 'no_role';
        }

        $current_user = get_user_by('id', absint($id));
        $user_roles = (array) $current_user->roles;
        $user_role = $user_roles[0] ?? false;

        return $user_role;
    }

    /**
     * Add the role name to the body classes.
     */
    function duzz_role_class_names($classes) {
        $classes[] = 'role_' . $this->get_user_role();
        return $classes;
    }

    /**
     * Register the role class names filter.
     */
    function duzz_register_role_class_names_filter() {
        add_filter('body_class', array($this, 'duzz_role_class_names'));
    }
}
