<?php

namespace Duzz\Core;

use Duzz\Shared\Actions\Duzz_Project_Number;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Core\Duzz_Email;
use Duzz\Shared\Actions\Duzz_Status_Feed;
use Duzz\Base\Stripe\Duzz_Stripe_Checkout;
use Duzz\Shared\Entity\Duzz_Staff_Keys;
use Duzz\Shared\Layout\Factory\Duzz_Table_Factory;

class Duzz_Processes {

	private $stripe_checkout;

    public function __construct() {

		add_action( 'admin_post_send_invoice', array( $this, 'send_invoice' ));
		add_action( 'wpforms_process_complete', array( $this, 'staff_add_project' ), 10, 4 );
		add_action( 'wpforms_process_complete', array( $this, 'customer_add_project' ), 10, 4 );
        add_action( 'init', array( $this, 'resend_project_email' ) );
        add_action( 'init', array( $this, 'add_custom_post' ) );
         add_action('init', array($this, 'update_duzz_fields'));
		$this->stripe_checkout = new Duzz_Stripe_Checkout();
	}


public function update_duzz_fields() {
        if (isset($_POST['post_id'])) {
            $post_id = intval($_POST['post_id']);
        } else {
            return;
        }

        if (!function_exists('acf_form_head') && isset($_POST['duzz_nonce_name'])) {
            if (!wp_verify_nonce($_POST['duzz_nonce_name'], 'duzz_nonce_action')) {
                die('Security check failed.');
            }
            
            foreach ($_POST as $key => $value) {
                if ($key == 'duzz_nonce_name' || $key == 'post_id') {
                    continue;
                }
                Duzz_Helpers::duzz_update_field(
                    sanitize_key($key), // Sanitize key
                    sanitize_text_field($value), // Sanitize value
                    $post_id
                );
            }


        do_action('duzz_fields_updated', $post_id);
        }
    }


function add_custom_post() {

    // Check if the form was submitted
    if (isset($_POST['action']) && sanitize_text_field($_POST['action']) === 'custom_post_add') {

        // Verify nonce
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'custom_post_add_nonce')) {
            wp_die('Nonce verification failed!');
        }

        if (!current_user_can('publish_posts')) {
            die('You do not have permission to perform this action.');
        }

        $company_id = isset($_POST['company_id']) ? sanitize_text_field($_POST['company_id']) : '';
        $team_id = isset($_POST['team_id']) ? sanitize_text_field($_POST['team_id']) : '';
        $staff_id = isset($_POST['staff_id']) ? sanitize_text_field($_POST['staff_id']) : '';

        // Fetch the meta data field names
        $selected_columns = Duzz_Get_Data::get_form_id('settings_list_projects_field_data', 'selected_columns');
        $selected_columns_data_title = Duzz_Get_Data::get_form_id('settings_list_projects_field_data', 'selected_columns_data_title');
        $selected_columns_data_title = $selected_columns_data_title[0];


        // Get the value for the title from $_POST using the specified meta key for title
        $title = isset($_POST[$selected_columns_data_title]) ? sanitize_text_field($_POST[$selected_columns_data_title]) : '';

        $post_type = sanitize_text_field($_POST['post_type']);
        $tracking_id = Duzz_Project_Number::generate();

        // Check if title and post type are not empty
        if (empty($title) || empty($post_type)) {
            return false; // or handle error
        }

        // Combine them into a single list
        $meta_keys = is_array($selected_columns) ? $selected_columns : [];
        if ($selected_columns_data_title) {
            $meta_keys[] = $selected_columns_data_title;
        }

        // Create the post and obtain the ID
            $args = [
                'post_title'  => $title,
                'post_status' => 'publish',
                'post_type'   => $post_type,
                'import_id' => $tracking_id,
                'meta_input'  => [
                    'archived' => 0,
                    'company_id' => $company_id,
                    'team_id'    => $team_id,
                    'staff_id'   => $staff_id,
                ]
            ];

        // Loop through the metadata_keys to fetch data from $_POST and save them
        foreach ($meta_keys as $meta_key) {
            if (isset($_POST[$meta_key])) {
                // Sanitize meta data
                $args['meta_input'][$meta_key] = sanitize_text_field($_POST[$meta_key]);
            }
        }

        // Now, insert the post with the meta data
        $post_id = wp_insert_post($args);

        wp_redirect(site_url('/workspace/'));
        exit;
    }
}


public function send_invoice() {
    // Check if the right action is set and verify nonce
if (isset($_POST['action']) && sanitize_text_field($_POST['action']) === 'send_invoice') {

            // Verify nonce
            if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'send_invoice_nonce')) {
                wp_die('Nonce verification failed!', 403);
            }
        
        // Check user capability
        if (!current_user_can('publish_posts')) {
            die('You do not have permission to perform this action.');
        }

        // Sanitize and fetch 'invoice_name' and 'sales_tax' fields
          $payment_id = filter_input(INPUT_POST, 'payment_id', FILTER_SANITIZE_STRING);
        $invoice_name = filter_input(INPUT_POST, 'invoice_name', FILTER_SANITIZE_STRING);
        $sales_tax = filter_input(INPUT_POST, 'sales_tax', FILTER_SANITIZE_STRING);
        $prev_invoice_type = get_post_meta($payment_id, 'invoice_type', true);
         $invoice_type = filter_input(INPUT_POST, 'invoice_type', FILTER_SANITIZE_STRING);

        // Check if they're empty
      if (empty($invoice_name) || empty($sales_tax)) {
                // Redirect back to the form page or any other error page with an error message
                wp_safe_redirect(add_query_arg('error', 'true', wp_get_referer()));
                exit;  // Make sure to terminate further execution
            }

     	$existing_post = get_post($payment_id);

        $line_items = [];
        $total_sum = 0; 
        $invoice_table = '<div class="header-pricing-container">';
        $invoice_table .= '<div class="invoice-feed-title">'. $invoice_type . '</div>';  
        $invoice_table .= '<div class="progress-sub-title">'. $invoice_name . '</div>';  
        $invoice_table .= '</div>';
        $invoice_table .= '<div class="invoice-pricing-container">';
        $invoice_table .= '<table><tbody>';



        if(isset($_POST['item']) && is_array($_POST['item'])) {
            foreach($_POST['item'] as $id => $item) {
                
                // Check if the row ID equals 0
                

                // Gather and sanitize data
                $units = intval($_POST['units'][$id]);
                $unit_type = sanitize_text_field($_POST['unit_type'][$id]);
                $price = floatval($_POST['price'][$id]);
                $total = $units * $price;

                $total_sum += $total; // Add the total for this item to the total sum
                 $total_aftertax_sum = $total_sum * (($sales_tax / 100) + 1);
                 $tax_total = $total_sum * ($sales_tax / 100);
                $line_items[] = [
                    'item' => sanitize_text_field($item),
                    'units' => $units,
                    'unit_type' => $unit_type,
                    'price' => $price,
                    'total' => $total
                ];

                // Modify table structure here
 
                $invoice_table .= '<tr class="pricing-invoice-header">';
                $invoice_table .= '<td>'.sanitize_text_field($item).'</td>';
                $invoice_table .= '<td class="invoice-align-right">$'.$total.'</td>';
                $invoice_table .= '</tr>';
                $invoice_table .= '<tr class="pricing-invoice-sub">';
                $invoice_table .= '<td>'.$units.' '.$unit_type.'s</td>';
                $invoice_table .= '<td class="invoice-align-right">$'.$price.' per '.$unit_type.'</td>';
                $invoice_table .= '</tr>';
            }
        }

        // Add total sum row to table after loop

        $invoice_table .= '</tbody></table>';
        $invoice_table .= '</div>';
        $invoice_table .= '<div class="total-invoice-pricing-container">';
        $invoice_table .= '<table><tbody>';
        $invoice_table .= '<tr class="pricing-border-top">';
        $invoice_table .= '<td>Total</td>';
        $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">$'.$total_sum.'</td>';
        $invoice_table .= '</tr>';

        $invoice_table .= '<tr class="pricing-border-top">';
        $invoice_table .= '<td>Tax</td>';
        $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">'.$sales_tax.'%</td>';
        $invoice_table .= '</tr>';

        $invoice_table .= '<tr class="pricing-border-top-total-price">';
        $invoice_table .= '<td>After Tax</td>';
        $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">$'.$total_aftertax_sum.'</td>';
        $invoice_table .= '</tr>';

    $invoice_table .= '</tbody></table>';
    
    $project_id = sanitize_text_field($_POST['project_id']);
    if ($invoice_type === 'Invoice'){
    $payNowButton = $this->stripe_checkout->generatePayNowButton($total_aftertax_sum, $project_id);

    $invoice_table .= $payNowButton;
            }
        $invoice_table .= '</div>';

        $post_data = [
            'post_title'  => $invoice_name,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type'   => 'payment',
            'import_id'   => $payment_id,
            'meta_input'  => [
                'archived'   => 0,
                'project_id' => $project_id,
                'line_items' => $line_items,
                'invoice_type' => $invoice_type,
                'invoice_name' => $invoice_name,
                'sales_tax'		=> $sales_tax,
                'tax_total' => $tax_total,
                'total_sum' => $total_sum,
                'total_aftertax_sum' => $total_aftertax_sum
            ],
        ];

    if ($invoice_type !== $prev_invoice_type) {
        update_post_meta($payment_id, 'invoice_type', $invoice_type);
    }

            switch (true) {
                case $invoice_type === 'Invoice' && $existing_post && $prev_invoice_type !== 'Invoice':
                    $post_data['ID'] = $existing_post->ID; 
                    $post_id = wp_update_post($post_data);
                    Duzz_Status_Feed::add_comment_to_status_feed('Your invoice is ready. Click the button below to pay:<br>' . $invoice_table, $project_id, $payment_id);
                    break;

                case $invoice_type === 'Invoice' && !$existing_post && $prev_invoice_type !== 'Invoice':
                    $post_id = wp_insert_post($post_data);
                    Duzz_Status_Feed::add_comment_to_status_feed('Your invoice is ready. Click the button below to pay:<br>' . $invoice_table, $project_id, $payment_id);
                    break;

                case $invoice_type === 'Invoice':
                    $post_data['ID'] = $existing_post->ID; 
                    $post_id = wp_update_post($post_data);
                    Duzz_Status_Feed::add_comment_to_status_feed('Your ' . $invoice_type . ' has been updated:<br>' . $invoice_table, $project_id, $payment_id);
                    break;

                case $invoice_type === 'Estimate' && $existing_post:
                    $post_data['ID'] = $existing_post->ID; 
                    $post_id = wp_update_post($post_data);
                    Duzz_Status_Feed::add_comment_to_status_feed('Your ' . $invoice_type . ' has been updated:<br>' . $invoice_table, $project_id);
                    break;

                default:
                    $post_id = wp_insert_post($post_data);
                    Duzz_Status_Feed::add_comment_to_status_feed($invoice_table, $project_id);
                    break;
            }

        if (!is_wp_error($post_id)) {
            wp_safe_redirect(site_url('/project/?project_id=' . esc_attr($project_id)));
            exit;
        }
    }
}



/***CUSTOMER CREATE PROJECT***/
public function customer_add_project( $fields, $entry, $form_data, $entry_id ) {
		// Below, we restrict output to form #9937.


    $form_number = Duzz_Get_Data::get_form_id('client_form_id_field_data', 'form_id');

    if (absint($form_data['id']) !== intval($form_number)) {
        return;
    }


    $option_name = 'client_field_numbers_field_data';
    $saved_values = Duzz_Get_Data::get_field_names('client_field_numbers');
    $field_numbers = [];

    foreach ($saved_values as $saved_value) {
        $field_numbers[$saved_value] = Duzz_Get_Data::get_form_id($option_name, $saved_value);
    }


   	$customer_ip = $fields[ intval($field_numbers['customer_ip']) ];
    $customer_address = $fields[ intval($field_numbers['customer_address']) ];
    $customer_name    = $fields[ intval($field_numbers['customer_name']) ];
    $customer_email   = $fields[ intval($field_numbers['customer_email']) ];
    $customer_phone   = $fields[ intval($field_numbers['customer_phone']) ];
    $staff_id         = $fields[ intval($field_numbers['staff_id']) ];
    $team_id          = $fields[ intval($field_numbers['team_id']) ];
    $company_id       = $fields[ intval($field_numbers['company_id']) ];
    $website          = $fields[ intval($field_numbers['website']) ];



$tracking_id = Duzz_Project_Number::generate();
$project_id = $tracking_id;
				

			$customer_address1 = ucwords(strtolower($customer_address['address1'])) ?? '';
				$customer_address2 = ucwords(strtolower($customer_address['address2'])) ?? '';
				$customer_city = ucwords(strtolower($customer_address['city'])) ?? '';
				$customer_state = strtoupper($customer_address['state']) ?? '';
				$customer_zip = $customer_address['postal'] ?? '';
				$combined_address = $customer_address1 .', '. $customer_city . ', ' . $customer_state .' '. $customer_zip;
   
		// create project
		$post_data = [
			'post_title'  => $customer_name['value'],
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type'   => 'project',
			'import_id'	=> $tracking_id,
    			'meta_input'  => [
				'archived'                  => 0,
				'customer_ip' => $customer_ip['value'],
				'company_id'                => $company_id['value'],
				'team_id'                   => $team_id['value'],
				'staff_id'             => $staff_id['value'],
				'customer_email'            => strtolower($customer_email['value']),
				'website'            => strtolower($website['value']),			
				'customer_phone'            => $customer_phone['value'],
				'customer_first_name'       => ucwords(strtolower($customer_name['first'])) ?? '',
				'customer_last_name'        => ucwords(strtolower($customer_name['last'])) ?? '',
				'customer_name'				=> ucwords(strtolower($customer_name['value'])) ?? '',
				'customer_address1'                  => $customer_address1,
				'customer_address2'                  => $customer_address2,
				'customer_city'                      => $customer_city,
				'customer_state'                     => $customer_state,
				'customer_zip'                       => $customer_zip,
				'customer_address'			=>	$combined_address,
			],
		];


		$project_id = wp_insert_post( $post_data );

		Duzz_Status_Feed::add_to_status_feed( 'Customer Created Account', $project_id );


         $admin_email_settings = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');

			$data = [
				'email_address'   => $admin_email_settings,
				'subject'         => 'Duzz ALERT: New Customer',
				'content'         => 'A customer just created a new project here: ',
				'project_url'        => site_url( '/view-project/?project_id=' . $project_id ),
			];

			$email = new Duzz_Email( 'new-customer', $data );
			$email->send();


		$welcome_message = Duzz_Get_Data::get_form_id('settings_welcome_message_field_data', 'message');
		Duzz_Status_Feed::add_comment_to_status_feed('Hi ' . ucwords(strtolower($customer_name['first'])) . ', ' . $welcome_message, $project_id);


		wp_redirect( site_url( '/your-project/?project_id=' . $project_id ) );
			die();
	}

	/***staff ADD PROJECT***/

public function staff_add_project($fields, $entry, $form_data, $entry_id)
{
    $form_number = Duzz_Get_Data::get_form_id('admin_form_id_field_data', 'form_id');

    if (absint($form_data['id']) !== intval($form_number)) {
        return;
    }

        if (!current_user_can('publish_posts')) {
            die('You do not have permission to perform this action.');
        }

    $option_name = 'admin_field_numbers_field_data';
    $saved_values = Duzz_Get_Data::get_field_names('admin_field_numbers');
    $field_numbers = [];

    foreach ($saved_values as $saved_value) {
        $field_numbers[$saved_value] = Duzz_Get_Data::get_form_id($option_name, $saved_value);
    }

    $customer_address = $fields[ intval($field_numbers['customer_address']) ];
    $customer_name    = $fields[ intval($field_numbers['customer_name']) ];
    $customer_email   = $fields[ intval($field_numbers['customer_email']) ];
    $customer_phone   = $fields[ intval($field_numbers['customer_phone']) ];
    $staff_id         = $fields[ intval($field_numbers['staff_id']) ];
    $team_id          = $fields[ intval($field_numbers['team_id']) ];
    $company_id       = $fields[ intval($field_numbers['company_id']) ];
    $website          = $fields[ intval($field_numbers['website']) ];

				$customer_address1 = ucwords(strtolower($customer_address['address1'])) ?? '';
				$customer_address2 = ucwords(strtolower($customer_address['address2'])) ?? '';
				$customer_city = ucwords(strtolower($customer_address['city'])) ?? '';
				$customer_state = strtoupper($customer_address['state']) ?? '';
				$customer_zip = $customer_address['postal'] ?? '';
				$combined_address = $customer_address1 .', '. $customer_city . ', ' . $customer_state .' '. $customer_zip;
   

    $tracking_id = Duzz_Project_Number::generate();
    $project_id = $tracking_id;

    // create project
    $post_data = [
			'post_title'  => $customer_name['value'],
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'project',
        'import_id' => $tracking_id,
        'meta_input' => $fields,
        	'meta_input'  => [
				'archived'                  => 0,
				'company_id'                => $company_id['value'],
				'team_id'                   => $team_id['value'],
				'staff_id'             => $staff_id['value'],
				'customer_email'            => strtolower($customer_email['value']),
				'website'            => strtolower($website['value']),			
				'customer_phone'            => $customer_phone['value'],
				'customer_first_name'       => ucwords(strtolower($customer_name['first'])) ?? '',
				'customer_last_name'        => ucwords(strtolower($customer_name['last'])) ?? '',
				'customer_name'				=> ucwords(strtolower($customer_name['value'])) ?? '',
				'customer_address1'                  => $customer_address1,
				'customer_address2'                  => $customer_address2,
				'customer_city'                      => $customer_city,
				'customer_state'                     => $customer_state,
				'customer_zip'                       => $customer_zip,
				'customer_address'			=>	$combined_address,
			],
    ];


    $project_id = wp_insert_post($post_data);

    wp_redirect(site_url('/workspace/'));
    die();
}



public function resend_project_email() {

    if ( isset( $_POST['action'] ) && $_POST['action'] === 'resend_project_email' ) {

        if (!check_admin_referer('resend_project_email', '_wpnonce')) {
            wp_die('Nonce check failed, please try again.', 'Nonce Check Failed');
        }

        $project_id = sanitize_text_field( $_POST['project_id'] );   
        $search_email = sanitize_text_field( $_POST['project_email_search'] );
        $search_last_name = sanitize_text_field( $_POST['project_last_name_search'] );
        $ip = sanitize_text_field( $_POST['ip'] );
        $customer_ip = Duzz_Helpers::duzz_get_field( 'customer_ip', $project_id );

                if ( !empty($project_id)) {
                    $customer_email = Duzz_Helpers::duzz_get_field( 'customer_email', $project_id );
                    $customer_last_name = Duzz_Helpers::duzz_get_field('customer_last_name', $project_id);               

                    if ($customer_email == $search_email && $customer_last_name == $search_last_name) {
                        $new_ip = $ip;
                        Duzz_Helpers::duzz_update_field('customer_ip', $new_ip, $project_id );
                        wp_redirect( site_url( '/your-project/?project_id=' . $project_id ));
                        exit;
                    } else {
                        wp_redirect( site_url( add_query_arg( 'failed_project_email', 'true' ) ) );
                    }
                }

        if ( empty($project_id)) {
            $args = array(
                'post_type' => 'project',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'customer_email',
                        'value' => $search_email,
                        'compare' => '='
                    ),
                    array(
                        'key' => 'customer_last_name',
                        'value' => $search_last_name,
                        'compare' => '='
                    )
                )
            );

            $projects = get_posts( $args );

            if ( count( $projects ) == 0 ) {

                wp_redirect( site_url( add_query_arg( 'no-project-email', 'true' ) ) );
                exit;
            }

            $project_id = $projects[0]->ID;

         $customer_name = Duzz_Helpers::duzz_get_field('customer_first_name', $project_id ) ?: 'there';

        $meta = [
            'archived'      => 0,
        ];


        // send email
        $data = [
            'email_address'   => $search_email,
            'subject'         => 'Resend Project Link!',
            'first_name'      => $customer_name,
            'login_url'       => site_url( '/your-project/?project_id=' . $project_id ),
            'staff_name' => Duzz_Helpers::duzz_get_field( Duzz_Staff_Keys::$first_name, 'user_262'),
            'message' => 'You requested to resend your project link. A project with your email has been found:',
            'company_sig' => get_the_title(9909),
        ];

        $email = new Duzz_Email( 'invite', $data );

        if ( $email->send() === true ) {
            Duzz_Status_Feed::add_to_status_feed( 'Customer requested project email resend', $project_id );
            wp_redirect( site_url( add_query_arg( 'resend-project-email', 'true' ) ) );
            exit;
        } else {
            Duzz_Status_Feed::add_to_status_feed( 'Failed Project Access: New device detected', $project_id );
            wp_redirect( site_url( add_query_arg( 'failed_project_email', 'true' ) ) );
            exit;
          }
         }
        }
    }



}


$init_processes = new Duzz_Processes;