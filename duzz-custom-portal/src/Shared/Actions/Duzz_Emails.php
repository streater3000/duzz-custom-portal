<?

namespace Duzz\Shared\Actions;

use Duzz\Core\Duzz_Email;
use Duzz\Shared\Actions\Duzz_Status_Feed;
use Duzz\Core\Duzz_Helpers;

class Duzz_Emails {

    public function __construct() {
        add_action( 'init', [$this, 'send_customer_invite'] );
    }

function send_customer_invite() {
    // Check user capability and nonce
    if (!current_user_can('manage_options')) {
        return; // only allow admins (or any other capability you prefer)
    }

    if (isset($_POST['sendinvite']) && check_admin_referer('sendinvite', '_wpnonce')) {
        $project_id = isset($_POST['project_id']) ? absint(sanitize_text_field($_POST['project_id'])) : 0;

        if (!Duzz_Validate_ID::validate($project_id)) {
            return; 
        }

        $client_first = isset($_POST['client_first']) ? sanitize_text_field($_POST['client_first']) : '';
        $client_last = isset($_POST['client_last']) ? sanitize_text_field($_POST['client_last']) : '';
        $company_name = isset($_POST['company_name']) ? sanitize_text_field($_POST['company_name']) : '';
        $client_email = isset($_POST['client_email']) ? sanitize_email($_POST['client_email']) : '';
        $email_subject = isset($_POST['email_subject']) && !empty($_POST['email_subject']) ? sanitize_text_field($_POST['email_subject']) : 'Track your project!';
        $email_message = isset($_POST['email_message']) && !empty($_POST['email_message']) ? sanitize_text_field($_POST['email_message']) : 'Hey ' . $client_first . ', <br><br>You can track the progress of your project here:';

        $data = [
            'email_address' => $client_email,
            'subject' => $email_subject,
            'first_name' => $client_first,
            'message' => $email_message,
            'project_url' => site_url('/your-project/?project_id=' . $project_id),
            'company_sig' => $company_name,
        ];

        $email = new Duzz_Email('invite', $data);

        if ($email->send() === true) {
            Duzz_Status_Feed::add_to_status_feed(Duzz_Helpers::duzz_get_name(get_current_user_id()) . ' sent Client invite.', $project_id);
            wp_redirect(add_query_arg(['project_id' => $project_id, 'sentinvite' => 'true'], site_url('/project/')));
            exit;
        } else {
            wp_redirect(add_query_arg(['project_id' => $project_id, 'sentinvite' => 'false'], site_url('/project/')));
            exit;
        }
    }
}

}

