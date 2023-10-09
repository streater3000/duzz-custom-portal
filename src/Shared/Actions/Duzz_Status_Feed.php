<?php
/**
 * Register the post types and taxonomies.
 */

namespace Duzz\Shared\Actions;

use Duzz\Core\Duzz_Helpers;
use Duzz\Core\Duzz_Email;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Core\Duzz_Data_Passer;

class Duzz_Status_Feed {

 private $data_passer;
	/**
	 * Autoload method
	 */
	public function __construct() {
		add_shortcode('duzz_status_feed', array( $this, 'duzz_register_shortcode' ) );
	  	add_action('acf/save_post', array( $this, 'duzz_acf_update_last_updated' ), 15, 1);
		add_filter('acf/update_value', array( $this, 'duzz_acf_update_status_feed' ), 10, 4);
		add_action( 'init', array( $this, 'duzz_add_project_update' ) );
		 $this->data_passer = new Duzz_Data_Passer();
	}


public function duzz_acf_update_last_updated( $post_id ) {

	
	$now = gmdate('d/m/Y g:i a');
    Duzz_Helpers::duzz_update_field('last_updated', $now, $post_id);

	}

	public function duzz_acf_update_status_feed( $value, $post_id, $field, $original ) {

    $remove_fields_feed = Duzz_Get_Data::duzz_get_field_values('duzz_settings_remove_keys_field_data');

    // Without this an infinite update loop will be triggered.
    if ( in_array($field['key'], $remove_fields_feed) ) {
        return $value;
    }


 		$acf_group_ids = Duzz_Get_Data::duzz_get_field_values('duzz_settings_acf_group_field_data');



  		if ( isset( $field['parent'] ) && in_array($field['parent'], $acf_group_ids) ) {
			 $current_user_id = get_current_user_id();
				    $name = Duzz_Helpers::duzz_get_name($current_user_id);				

				    if (!empty($name)) {
				        $user_id = 262;
				        $name = Duzz_Helpers::duzz_get_name($user_id);
				    }


			$old_value_raw = get_post_meta( $post_id, $field['name'], true );

			$new_value = '';

			if ( is_array( $value ) ) {
				$new_value = implode( ', ', $value );
			} else {
				$new_value = $value ?: 'blank';
			}

			if ( is_array( $old_value_raw ) ) {
				$old_value = implode( ', ', $old_value_raw );
			} else {
				$old_value = $old_value_raw ?: 'blank';
			}

			if ( $new_value != $old_value ) {
				self::duzz_add_to_status_feed( $name . ' updated ' . $field['label'] . ' to <strong>' . $new_value . '</strong> (previously: <strong>' . $old_value . '</strong>).', $post_id );
			}
		}
		return $value;
	}
    

static function duzz_add_comment_to_status_feed($update, $project_id, $payment_id = null, $user_id = 262) {

    if (!isset($project_id)) {
        return;
    }


    if ($payment_id) {
        // Check if there's already an associated comment with this payment_id
        $existing_comment_id = get_post_meta($payment_id, 'associated_comment', true);

        // Check if the comment with the given ID exists
        $existing_comment = get_comment($existing_comment_id);

        if ($existing_comment) {
           wp_trash_comment($existing_comment_id);

        
    }
}

    $content = wp_kses_post($update);
    $project_id = absint($project_id);
    $user_id = absint($user_id);

    // Create a new comment
$args = [
    'comment_post_ID' => $project_id,
    'comment_content' => $content,
    'user_id' => $user_id,
];



    $comment_id = wp_insert_comment($args);

    $post_type = get_post_type($project_id);
    add_comment_meta($comment_id, 'comment_post_type', $post_type);

    if ($payment_id) {
        // Link the new comment to the payment_id
        update_post_meta($payment_id, 'associated_comment', $comment_id);
        
    }
}
    
	/**
	* Add an event to the status feed.
	*/
	static function duzz_add_to_status_feed( $update, $project_id, $user_id = 176 ) {

		if ( ! isset( $project_id ) ) {
			return;
		}

		$admin_email = Duzz_Get_Data::duzz_get_form_id('duzz_settings_email_settings_field_data', 'admin_email');

		$content = wp_kses_post( $update );
		$project_id = absint( $project_id );
		$user_id = absint( $user_id );

		$args = [
			'comment_post_ID' => $project_id,
			'comment_content' => $content,
			'user_id' => $user_id,
		];

		 $comment_id = wp_insert_comment( $args );

		   $post_type = get_post_type($project_id);
        add_comment_meta($comment_id, 'comment_post_type', $post_type);


		// send email
		$data = [
			'email_address'   => $admin_email,
			'subject'         => 'Project #' . $project_id . ' Updated',
			'content'       => $content,
			'project_id'       => $project_id,
			'project_url'       => site_url('/project/' . $project_id . '/'),
		];

		//$email = new Duzz_Email( 'project-updated', $data );

		//$email->duzz_send();
	}

	/**
	 * Add project update to status feed.
	 */
public function duzz_add_project_update() {
    if (!is_user_logged_in()) {
        $this->process_guest_update();
    } else {
        $this->process_user_update();
    }
}

private function process_guest_update() {

    if ($this->data_passer->duzz_retrieve('POST', 'action', 'action') !== 'add_project_update') {

        return;
    }

    $admin_email = Duzz_Get_Data::duzz_get_form_id('duzz_settings_email_settings_field_data', 'admin_email');
    $user_id = absint(get_current_user_id());
    $content = wp_kses_post($this->data_passer->duzz_retrieve('POST', 'content', 'action'));
    $project_id = $this->data_passer->duzz_retrieve('POST', 'project_id', 'action');

    $args = [
        'comment_post_ID' => $project_id,
        'comment_content' => $content,
        'user_id' => $user_id,
    ];

    $comment_id = wp_insert_comment($args);
    $post_type = get_post_type($project_id);
    add_comment_meta($comment_id, 'comment_post_type', $post_type);

    $data = [
        'email_address' => $admin_email,
        'subject' => 'Project #' . $project_id . ' Updated',
        'staff_name' => Duzz_Helpers::duzz_get_name(get_current_user_id()),
        'content' => $content,
        'project_url' => esc_url(site_url('/project/' . $project_id . '/')),
        'project_id' => $project_id,
    ];

    $email = new Duzz_Email('project-updated', $data);
    $email->duzz_send();

    $current_path = esc_url_raw(wp_unslash($_SERVER['REQUEST_URI']));
    $base_url = site_url() . $current_path;

    // Add fragment
    $url = $base_url . '#status_feed';

    // Redirect
    wp_redirect($url);
    exit;
}

private function process_user_update() {


    if ($this->data_passer->duzz_retrieve('POST', 'action', 'action') !== 'add_project_update') {

        return;
    }

    $content = wp_kses_post($this->data_passer->duzz_retrieve('POST', 'content', 'action'));
    $project_id = $this->data_passer->duzz_retrieve('POST', 'project_id', 'action');

    $tagged_user_name = Duzz_Helpers::duzz_get_field('customer_first_name', $project_id);
    $customer_ip = Duzz_Helpers::duzz_get_field('customer_ip', $project_id);
    $tagged_by = get_user_meta(get_current_user_id(), 'first_name', true);

    
        $tagged_by = get_user_meta(get_current_user_id(), 'first_name', true);
        if (!$tagged_by) {
            $fallback_user = get_user_by('id', 262);
            $tagged_by = $fallback_user->first_name;
        }
        $tagged_subject = $tagged_by . ' with Duzz Custom Portal';



    if (!$project_id) {
        return;
    }

    $client_email = Duzz_Helpers::duzz_get_field('customer_email', $project_id);

    if (!$client_email) {
        return;
    }

    $user_id = absint(get_current_user_id());
    
        $args = [
        'comment_post_ID' => $project_id,
        'comment_content' => $content,
        'user_id' => $user_id,
    ];

    $comment_id = wp_insert_comment($args);
    $post_type = get_post_type($project_id);
    add_comment_meta($comment_id, 'comment_post_type', $post_type);

    if (!empty($customer_ip)) {
    $data = [
        'email_address' => $client_email,
        'subject' => 'Tagged by ' . $tagged_subject,
        'tagged_by' => $tagged_by,
        'first_name' => $tagged_user_name,
        'content' => $content,
        'project_url' => site_url('/your-project/' . $project_id . '/'),
    ];

    $email = new Duzz_Email('project-tagged', $data);
    $email->duzz_send();
        }
    }

}
