<?php 

namespace Duzz\Shared\Actions;

use Duzz\Shared\Entity\Duzz_Staff;
use Duzz\Core\Duzz_Email;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Core\Duzz_Helpers;

class Duzz_Tribute {

    public function __construct() {
        add_action('wp_footer', [$this, 'tribute']);
        add_action('wp_insert_comment', [$this, 'duzz_send_project_tagged_notification'], 10, 2);
        add_action('wp_insert_comment', [$this, 'duzz_send_project_update_notification'], 10, 2);
    }

public function tribute() {
    if (isset($_GET['project_id'])) {
        // Sanitize
        $project_id = sanitize_text_field($_GET['project_id']);
        
        // Validate
        if (!Duzz_Validate_ID::validate($project_id)) {
            return ''; // return empty if invalid
        }

                    $project_company_id = absint(Duzz_Helpers::duzz_get_field('company_id', $project_id));

        $scriptParts = [];

        if (is_user_logged_in()) {
            $customer_first_name = Duzz_Helpers::duzz_get_field('customer_first_name', $project_id);
            $customer_email = Duzz_Helpers::duzz_get_field('customer_email', $project_id);
            $scriptParts[] = '{ key: ' . wp_json_encode($customer_first_name . ' (' . $customer_email . ')') . ', value: ' . wp_json_encode($customer_email) . ' }';
        }

        $admin_email = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');
        $admin_name = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_name');
        $scriptParts[] = '{ key: ' . wp_json_encode($admin_name . ' (Admin) ' . $admin_email) . ', value: ' . wp_json_encode($admin_email) . ' }';

        foreach (Duzz_Staff::get_all(['meta_key' => 'company_id', 'meta_value' => $project_company_id]) as $staff) {
            $scriptParts[] = '{ key: ' . wp_json_encode($staff->display_name . ' (' . $staff->user_email . ')') . ', value: ' . wp_json_encode($staff->user_email) . ' }';
        }

        $scriptContent = 'window.tributeValues = [' . implode(", ", $scriptParts) . ',];';
        $script = new \Duzz\Shared\Layout\HTML\Duzz_Script($scriptContent);
        $script->render();


    }
}



    public function duzz_send_project_tagged_notification($id, $comment) {
        $post_id = $comment->comment_post_ID;
        $comment_id = $comment->comment_ID;
        $post_url = get_permalink($post_id);
        $comment_url = get_comment_link($comment_id);
        $post_title = get_the_title($post_id);

        $pattern = '/[@]+([A-Za-z0-9-_\.@]+)\b/';
        preg_match_all($pattern, $comment->comment_content, $matches);

        // Make sure there's only one instance of each username.
        $emails = array_unique($matches[1]);

        // Bail if no usernames.
        if (empty($emails)) {
            return false;
        }

        foreach ($emails as $email) {

            $tagged_user_name = Duzz_Helpers::duzz_get_field('customer_first_name', $post_id);
            $url_project_link = site_url('/view-project/?project_id=' . $post_id);

            $customer_first_name = Duzz_Helpers::duzz_get_field('customer_first_name', $post_id);
            $customer_last_name = Duzz_Helpers::duzz_get_field('customer_last_name', $post_id);
            $tagged_by = $customer_first_name . ' ' . $customer_last_name;
            $tagged_subject = $customer_first_name . ' ' . $customer_last_name;

            if (!$tagged_user_name) {
                $tagged_user_name = Duzz_Helpers::duzz_get_field('customer_first_name', $post_id);
                $url_project_link = site_url('/your-project/?project_id=' . $post_id);
                $tagged_by = get_user_meta(get_current_user_id(), 'first_name', true);
                if (!$tagged_by) {
                    $fallback_user = get_user_by('id', 262);
                    $tagged_by = $fallback_user->first_name;
                }
                $tagged_subject = $tagged_by . ' with Duzz Custom Portal';
            }

            // send email
            $data = [
                'email_address' => $email,
                'subject' => 'Tagged by ' . $tagged_subject,
                'content' => $comment->comment_content,
                'tagged_by' => $tagged_by,
                'first_name' => $tagged_user_name,
                'project_url' => $url_project_link,
                'project_id' => $post_id,
            ];

            $email = new Duzz_Email('project-tagged', $data);
            $email->send();

        }

    }

    public function duzz_send_project_update_notification($id, $comment) {
        $post_id = $comment->comment_post_ID;
        $comment_id = $comment->comment_ID;
        $post_url = get_permalink($post_id);
        $comment_url = get_comment_link($comment_id);
        $post_title = get_the_title($post_id);

        // don't send emails for bot comments.
        if ($comment->user_id == 176) {
            return;
        }

        // Get the project company ID
        $project_id = absint($_GET['project_id'] ?? 0);
        $project_company_id = absint(Duzz_Helpers::duzz_get_field('company_id', $project_id));

        // Get all users of the company
        $users = Duzz_Staff::get_all(['meta_key' => 'company_id', 'meta_value' => $project_company_id]);

        foreach ($users as $user) {

            if (!get_user_by('email', $user['email'])) {
                continue;
            }

            if (str_contains($comment->comment_content, $user['email'])) {
                continue;
            }

            // send email
            $data = [
                'email_address' => $user['email'],
                'subject' => 'Project #' . $post_id . ' Updated',
                'staff_name' => $comment->comment_author,
                'content' => $comment->comment_content,
                'project_url' => site_url('/view-project/?project_id=' . $post_id),
                'project_id' => $post_id,
            ];

            $email = new Duzz_Email('project-updated', $data);
            $email->send();

        }
    }
}

