<?php 

namespace Duzz\Core;

use Duzz\Shared\Actions\Duzz_IP_Check;
use Duzz\Shared\Entity\Duzz_Customer;
use Duzz\Shared\Layout\Pages\Duzz_ResendProject;
use Duzz\Shared\Layout\Pages\Duzz_WP_Forms;
use Duzz\Core\Duzz_Helpers;
use Duzz\Shared\Actions\Duzz_Status_Feed;

class Duzz_Redirect {

    public function __construct() {
        add_action('template_redirect', [$this, 'duzz_redirect_non_logged_in_users_to_login']);
        add_action('wp', [$this, 'duzz_autoredirect_project_to_updates']);
        add_action('wp', [$this, 'duzz_view_no_project_redirect']);
        add_filter('the_content', [$this, 'duzz_updates_no_project_redirect'], 11);
        add_action('the_content', [$this, 'duzz_hide_view_project'], 11);
        add_filter('login_redirect', [$this, 'duzz_custom_login_redirect'], 10, 3);
    }


public function duzz_custom_login_redirect($redirect_to, $request, $user) {
    // Check the toggle
    $is_toggle_on = Duzz_Get_Data::duzz_get_form_id('duzz_settings_toggle_redirect_field_data', 'redirect_to_workspace_on_login') == 1;

    if ($is_toggle_on) {
        // If the toggle is on, redirect to /workspace/
        return home_url('/workspace/');
    }

    // If the toggle is off, proceed as usual
    return $redirect_to;
}

        
public function duzz_updates_no_project_redirect($content) {
    if (!is_page(9925)) {
        return $content;
    }
    
    $resend_project = new Duzz_ResendProject();
    $form_ip = '<div class="feed-title">confirm email to view</div><div class="redirect-form-margins">' . $resend_project->duzz_render() . '</div>';

    $wp_forms = new Duzz_WP_Forms();
    $wp_forms->duzz_render_customer_form();
    $form = '<div class="feed-title">create project</div><div class="redirect-form-margins">' . $wp_forms->container->duzz_render() . '</div>';

    $project_id = absint(get_query_var('project_id', false)); // Updated to use get_query_var

    $customer_name = Duzz_Helpers::duzz_get_field('customer_name', $project_id);
    $customer_ip = Duzz_Helpers::duzz_get_field('customer_ip', $project_id);
    $customer_ip_arr = explode("\n", $customer_ip);
    $customer_ip_arr = array_map('trim', $customer_ip_arr);

    $ip_check = new Duzz_IP_Check();
    $ip = $ip_check->duzz_get_client_ip_address();

    switch (true) {
        case (empty($project_id) || empty($customer_name)): // Updated condition to check $project_id
            return $form;
        case (!empty($customer_ip) && !is_user_logged_in() && !in_array($ip, $customer_ip_arr)):
            return $form_ip;
        case (empty($customer_ip) && !is_user_logged_in()):
            $new_ip = $customer_ip . "\n" . $ip;
            Duzz_Helpers::duzz_update_field('customer_ip', $new_ip, $project_id);
            Duzz_Status_Feed::duzz_add_to_status_feed('Customer accepted invite.', $project_id);
            return $content;
        default:
            return $content;
    }
}



    public function duzz_redirect_non_logged_in_users_to_login() {
        $restricted_page_ids = array(9923, 9924, 9926, 9921, 9920);
        $current_page_id = get_queried_object_id();

        if (!is_user_logged_in() && in_array($current_page_id, $restricted_page_ids)) {
            wp_redirect(wp_login_url(get_permalink($current_page_id)));
            exit;
        }
    }

public function duzz_autoredirect_project_to_updates() {
    if (!is_page(9924)) {
        return;
    }

    $project_id = get_query_var('project_id', 0); // 0 is the default value if project_id is not set

    if (!$project_id || !is_user_logged_in()) {
        return;
    }

    $project_id = absint($project_id);
    $customer = new Duzz_Customer(get_current_user_id());

    if (in_array('duzz_customer', (array)$customer->roles)) {
        wp_redirect(site_url('/your-project/' . $project_id)); // Updated
        exit();
    }
}


public function duzz_view_no_project_redirect() {
    if (is_page(9924) && !get_query_var('project_id', false)) { // false is the default value if project_id is not set
        wp_redirect(site_url('/workspace/'));
        exit;
    }
}

public function duzz_hide_view_project($content) {
    if (!is_page(9924)) {
        return $content;
    }

    $project_id = get_query_var('project_id', false); // false is the default value if project_id is not set

    $redirect_url = $project_id ? site_url('/project/' . absint($project_id)) : ''; // Updated

    if (!is_user_logged_in()) {
        return '<p class="duzz-error">You must be logged in to view this page.</p>';
    }

    return $content;
}
}
