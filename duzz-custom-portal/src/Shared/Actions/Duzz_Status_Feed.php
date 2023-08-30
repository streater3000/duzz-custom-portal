<?php
/**
 * Register the post types and taxonomies.
 */

namespace Duzz\Shared\Actions;

use Duzz\Core\Duzz_Helpers;
use Duzz\Core\Duzz_Email;
use Duzz\Core\Duzz_Get_Data;

class Duzz_Status_Feed {

	/**
	 * Autoload method
	 */
	public function __construct() {
		add_shortcode('duzz_status_feed', array( $this, 'register_shortcode' ) );
	  	add_action('acf/save_post', array( $this, 'acf_update_last_updated' ), 15, 1);
		add_filter('acf/update_value', array( $this, 'acf_update_status_feed' ), 10, 4);
		add_action( 'init', array( $this, 'add_project_update' ) );
	}


public function acf_update_last_updated( $post_id ) {

	
		$now = date('d/m/Y g:i a');
    Duzz_Helpers::duzz_update_field('last_updated', $now, $post_id);

	}

	public function acf_update_status_feed( $value, $post_id, $field, $original ) {

    $remove_fields_feed = Duzz_Get_Data::get_field_values('settings_remove_keys_field_data');

    // Without this an infinite update loop will be triggered.
    if ( in_array($field['key'], $remove_fields_feed) ) {
        return $value;
    }


 		$acf_group_ids = Duzz_Get_Data::get_field_values('settings_acf_group_field_data');



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
				self::add_to_status_feed( $name . ' updated ' . $field['label'] . ' to <strong>' . $new_value . '</strong> (previously: <strong>' . $old_value . '</strong>).', $post_id );
			}
		}
		return $value;
	}
    

static function add_comment_to_status_feed($update, $project_id, $payment_id = null, $user_id = 262) {

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
	static function add_to_status_feed( $update, $project_id, $user_id = 176 ) {

		if ( ! isset( $project_id ) ) {
			return;
		}

		$admin_email = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');

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
			'project_url'       => site_url( '/project/?project_id=' . $project_id ),
		];

		//$email = new Duzz_Email( 'project-updated', $data );

		//$email->send();
	}

	/**
	 * Add project update to status feed.
	 */


public function add_project_update() {
        if (isset($_POST['action']) && sanitize_text_field($_POST['action']) === 'add_project_update') {
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'add-project-update')) {
                die('Nonce verification failed!');
            }



            $admin_email = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');

            $project_id = isset($_POST['project_id']) ? absint(sanitize_text_field($_POST['project_id'])) : 0;
            if (!Duzz_Validate_ID::validate($project_id)) {
                die('Invalid Project ID');
            }

            $user_id = absint(get_current_user_id());
            $content = wp_kses_post($_POST['content']);

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
                'project_url' => esc_url(site_url('/project/?project_id=' . $project_id)),
                'project_id' => $project_id,
            ];

            $email = new Duzz_Email('project-updated', $data);
            $email->send();

			$url = site_url('/project/') . '?project_id=' . $project_id . '&project-updated=true#status_feed';
			wp_redirect($url);
			exit;



        }
    }


}
