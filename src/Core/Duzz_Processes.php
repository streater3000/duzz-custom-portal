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

    $this->data_passer = new Duzz_Data_Passer();
    
    add_action('admin_post_send_invoice', array($this, 'send_invoice'));
    add_action('wpforms_process_complete', array($this, 'staff_add_project'), 10, 4);
    add_action('wpforms_process_complete', array($this, 'customer_add_project'), 10, 4);
    add_action('init', array($this, 'resend_project_email'));
    add_action('init', array($this, 'add_custom_post'), 10); // Default priority
    add_action('init', array($this, 'update_duzz_fields'), 20); // Runs after add_custom_post

   $this->stripe_checkout = new Duzz_Stripe_Checkout();
}



private function check_empty_fields(array $fields) {
    foreach ($fields as $field => $value) {
        if (empty($value)) {
            wp_safe_redirect(add_query_arg('error', 'true', wp_get_referer()));
            exit;
        }
    }
}

private function check_user_capability($capability) {
    if (!current_user_can($capability)) {
        die('You do not have permission to perform this action.');
    }
}

private function verify_nonce($nonce_action) {
    if (!$this->data_passer->retrieve('POST', '_wpnonce', 'action') || !wp_verify_nonce($this->data_passer->retrieve('POST', '_wpnonce', 'action'), $nonce_action)) {
        wp_die('Nonce verification failed!', 403);
    }
}

public function update_duzz_fields() {

    // If you want just the post_id
    $post_id = $this->data_passer->retrieve('POST', 'post_id');
    
    // If you want the entire request array
    $requestData = $this->data_passer->retrieve('POST');

    if(!is_array($requestData)) {
        return;
    }
    
    if (!function_exists('acf_form_head')) {
        
        foreach ($requestData as $key => $value) {
            if ($key == 'post_id') {
                continue;
            }
            
            Duzz_Helpers::duzz_update_field(
                sanitize_key($key),
                sanitize_text_field($value),
                $post_id
            );
        }
        
        do_action('duzz_fields_updated', $post_id);
    } 
}



function add_custom_post() {

    

    if ($this->data_passer->retrieve('POST', 'action', 'action')  === 'custom_post_add') {


        $this->verify_nonce('custom_post_add_nonce');

        if (!current_user_can('publish_posts')) {
            die('You do not have permission to perform this action.');
        }

        $company_id = $this->data_passer->retrieve('POST', 'company_id', 'action') ?? '';
        
        $team_id = $this->data_passer->retrieve('POST', 'team_id', 'action') ?? '';
        
        $staff_id = $this->data_passer->retrieve('POST', 'staff_id', 'action') ?? '';
 
        $post_type = $this->data_passer->retrieve('POST', 'post_type', 'action') ?? '';

        // Fetch the metadata field names
        $selected_columns = Duzz_Get_Data::get_form_id('settings_list_projects_field_data', 'selected_columns');

        $selected_columns_data_title = Duzz_Get_Data::get_form_id('settings_list_projects_field_data', 'selected_columns_data_title');

        $selected_columns_data_title = $selected_columns_data_title[0];

        // Get the value for the title from Duzz_Data_Passer using the specified meta key for title
        $title = $this->data_passer->retrieve('POST', $selected_columns_data_title, 'action') ?? '';

        // Check if essential fields are not empty
        if (empty($title) || empty($post_type)) {
            return false; // or handle error
        }

        $tracking_id = Duzz_Project_Number::generate();

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

        // Loop through the metadata_keys to fetch data and save them
        foreach ($meta_keys as $meta_key) {
            $meta_value = $this->data_passer->retrieve('POST', $meta_key, 'action') ?? '';
            $args['meta_input'][$meta_key] = $meta_value;
        }

        // Now, insert the post with the meta data
        $post_id = wp_insert_post($args);
        wp_redirect(site_url('/workspace/'));
        exit;
    }
}


public function send_invoice() {

    // Check if the form was submitted using Duzz_Data_Passer
    if ($this->data_passer->retrieve('POST', 'action', 'action') !== 'send_invoice') return;

    $this->verify_nonce('send_invoice_nonce');
    $this->check_user_capability('publish_posts');

    $payment_id = $this->data_passer->retrieve('POST', 'payment_id', 'action') ?? '';
    $invoice_name = $this->data_passer->retrieve('POST', 'invoice_name', 'action') ?? '';
    $sales_tax = $this->data_passer->retrieve('POST', 'sales_tax', 'action') ?? '';
    $invoice_type = $this->data_passer->retrieve('POST', 'invoice_type', 'action') ?? '';

    $this->check_empty_fields([
        'invoice_name' => $invoice_name,
        'sales_tax' => $sales_tax,
    ]);

    $existing_post = get_post($payment_id);
    $prev_invoice_type = get_post_meta($payment_id, 'invoice_type', true);

    $line_items = $this->get_line_items($this->data_passer);
    $invoice_table = $this->generate_invoice_table($invoice_name, $invoice_type, $line_items, $sales_tax);
    $post_id = $this->handle_post_data($invoice_name, $payment_id, $invoice_type, $existing_post, $prev_invoice_type, $line_items, $sales_tax, $invoice_table);
    
    $this->finalize($post_id, $this->data_passer->retrieve('POST', 'project_id', 'action'));
}



private function finalize($post_id, $project_id) {
    // Function to finalize the process
    if (!is_wp_error($post_id)) {
        wp_safe_redirect(site_url('/project/' . esc_attr($project_id) . '/'));
        exit;
    }
}


private function get_line_items($data_passer) {
    $line_items = [];
    $total_sum = 0;
    $items = $this->data_passer->retrieve('POST', 'item', 'action');
    
    if($items && is_array($items)) {
        foreach($items as $id => $item) {
            $units = intval($this->data_passer->retrieve('POST', 'units', 'action')[$id]);
            $unit_type = sanitize_text_field($this->data_passer->retrieve('POST', 'unit_type', 'action')[$id]);
            $price = floatval($this->data_passer->retrieve('POST', 'price', 'action')[$id]);
            $total = $units * $price;
            
            $total_sum += $total; 
            
            $line_items[] = [
                'item' => sanitize_text_field($item),
                'units' => $units,
                'unit_type' => $unit_type,
                'price' => $price,
                'total' => $total
            ];
        }
    }

    return ['line_items' => $line_items, 'total_sum' => $total_sum];
}


private function generate_invoice_table($invoice_name, $invoice_type, $line_items, $sales_tax) {

    $project_id = $this->data_passer->retrieve('POST', 'project_id', 'action') ?? '';

    // Initialize the invoice_table string
    $invoice_table = '<div class="header-pricing-container">';
    $invoice_table .= '<div class="invoice-feed-title">'. sanitize_text_field($invoice_type) . '</div>';  
    $invoice_table .= '<div class="progress-sub-title">'. sanitize_text_field($invoice_name) . '</div>';  
    $invoice_table .= '</div>';
    $invoice_table .= '<div class="invoice-pricing-container">';
    $invoice_table .= '<table><tbody>';
    
    $total_sum = 0;
    
    // Loop through line items and add them to the table
    foreach ($line_items['line_items'] as $item) {
        $total = $item['units'] * $item['price'];
        $total_sum += $total;
        
        $invoice_table .= '<tr class="pricing-invoice-header">';
        $invoice_table .= '<td>'.sanitize_text_field($item['item']).'</td>';
        $invoice_table .= '<td class="invoice-align-right">$'.number_format($total, 2).'</td>';
        $invoice_table .= '</tr>';
        $invoice_table .= '<tr class="pricing-invoice-sub">';
        $invoice_table .= '<td>'.$item['units'].' '.sanitize_text_field($item['unit_type']).'s</td>';
        $invoice_table .= '<td class="invoice-align-right">$'.number_format($item['price'], 2).' per '.sanitize_text_field($item['unit_type']).'</td>';
        $invoice_table .= '</tr>';
    }
    
    // Calculate the tax and total after tax
    $tax_total = $total_sum * ($sales_tax / 100);
    $total_aftertax_sum = $total_sum + $tax_total;

    // Append the totals to the invoice table
    $invoice_table .= '</tbody></table>';
    $invoice_table .= '</div>';
    $invoice_table .= '<div class="total-invoice-pricing-container">';
    $invoice_table .= '<table><tbody>';
    $invoice_table .= '<tr class="pricing-border-top">';
    $invoice_table .= '<td>Total</td>';
    $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">$'.number_format($total_sum, 2).'</td>';
    $invoice_table .= '</tr>';
    $invoice_table .= '<tr class="pricing-border-top">';
    $invoice_table .= '<td>Tax ('.sanitize_text_field($sales_tax).'%)</td>';
    $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">$'.number_format($tax_total, 2).'</td>';
    $invoice_table .= '</tr>';
    $invoice_table .= '<tr class="pricing-border-top-total-price">';
    $invoice_table .= '<td>After Tax</td>';
    $invoice_table .= '<td class="pricing-invoice-header invoice-align-right">$'.number_format($total_aftertax_sum, 2).'</td>';
    $invoice_table .= '</tr>';
    $invoice_table .= '</tbody></table>';

    if ($invoice_type === 'Invoice'){
    $payNowButton = $this->stripe_checkout->generatePayNowButton($total_aftertax_sum, $project_id);

    $invoice_table .= $payNowButton;
            }

    $invoice_table .= '</div>';
    
    return $invoice_table;
}


private function handle_post_data($invoice_name, $payment_id, $invoice_type, $existing_post, $prev_invoice_type, $line_items, $sales_tax, $invoice_table) {
    $total_sum = array_sum(array_column($line_items['line_items'], 'total'));
    $tax_total = $total_sum * ($sales_tax / 100);
    $total_aftertax_sum = $total_sum + $tax_total;
    
    $project_id = $this->data_passer->retrieve('POST', 'project_id', 'action') ?? '';
    $post_data = [
        'post_title'  => sanitize_text_field($invoice_name),
        'post_status' => 'publish',
        'post_author' => get_current_user_id(),
        'post_type'   => 'payment',
        'import_id'   => sanitize_text_field($payment_id),
        'meta_input'  => [
            'archived'           => 0,
            'project_id'         => sanitize_text_field($project_id),
            'line_items'         => $line_items['line_items'],
            'invoice_type'       => sanitize_text_field($invoice_type),
            'invoice_name'       => sanitize_text_field($invoice_name),
            'sales_tax'          => sanitize_text_field($sales_tax),
            'tax_total'          => $tax_total,
            'total_sum'          => $total_sum,
            'total_aftertax_sum' => $total_aftertax_sum,
        ],
    ];
    
    if ($invoice_type !== $prev_invoice_type) {
        update_post_meta(sanitize_text_field($payment_id), 'invoice_type', sanitize_text_field($invoice_type));
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
            'import_id' => $tracking_id,
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
                'customer_name'             => ucwords(strtolower($customer_name['value'])) ?? '',
                'customer_address1'                  => $customer_address1,
                'customer_address2'                  => $customer_address2,
                'customer_city'                      => $customer_city,
                'customer_state'                     => $customer_state,
                'customer_zip'                       => $customer_zip,
                'customer_address'          =>  $combined_address,
            ],
        ];


        $project_id = wp_insert_post( $post_data );

        Duzz_Status_Feed::add_to_status_feed( 'Customer Created Account', $project_id );


         $admin_email_settings = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');

            $data = [
                'email_address'   => $admin_email_settings,
                'subject'         => 'Duzz ALERT: New Customer',
                'content'         => 'A customer just created a new project here: ',
                'project_url' => site_url('/project/' . $project_id . '/'),

            ];

            $email = new Duzz_Email( 'new-customer', $data );
            $email->send();


        $welcome_message = Duzz_Get_Data::get_form_id('settings_welcome_message_field_data', 'message');
        Duzz_Status_Feed::add_comment_to_status_feed('Hi ' . ucwords(strtolower($customer_name['first'])) . ', ' . $welcome_message, $project_id);

            wp_redirect(site_url('/your-project/' . $project_id . '/'));
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
                'customer_name'             => ucwords(strtolower($customer_name['value'])) ?? '',
                'customer_address1'                  => $customer_address1,
                'customer_address2'                  => $customer_address2,
                'customer_city'                      => $customer_city,
                'customer_state'                     => $customer_state,
                'customer_zip'                       => $customer_zip,
                'customer_address'          =>  $combined_address,
            ],
    ];


    $project_id = wp_insert_post($post_data);

    wp_redirect(site_url('/workspace/'));
    die();
}

public function resend_project_email() {
    error_log("resend_project_email method triggered");

    if ($this->data_passer->retrieve('POST', 'action', 'action') === 'resend_project_email') {
        
        $this->verify_nonce('resend_project_email');

        $project_id = sanitize_text_field($this->data_passer->retrieve('POST', 'project_id', 'action'));
        $search_email = sanitize_text_field($this->data_passer->retrieve('POST', 'project_email_search', 'action'));
        $search_last_name = sanitize_text_field($this->data_passer->retrieve('POST', 'project_last_name_search', 'action'));
        $ip = sanitize_text_field($this->data_passer->retrieve('POST', 'ip', 'action'));
        
        if (!empty($project_id)) {
            $customer_email = Duzz_Helpers::duzz_get_field('customer_email', $project_id);
            $customer_last_name = Duzz_Helpers::duzz_get_field('customer_last_name', $project_id);

            if ($customer_email == $search_email && $customer_last_name == $search_last_name) {
                $new_ip = $ip;
                Duzz_Helpers::duzz_update_field('customer_ip', $new_ip, $project_id);

                $redirect_url = site_url("/your-project/{$project_id}/");
                $nonce_url = htmlspecialchars_decode(wp_nonce_url($redirect_url, 'resend_project_email_nonce', 'resend-project-email-nonce'));
                
                wp_redirect($nonce_url);
                exit;
            } else {
                $redirect_url = site_url("/resend-project/?failed_project_email=true");
                $nonce_url = htmlspecialchars_decode(wp_nonce_url($redirect_url, 'failed_project_email_nonce', 'failed-project-email-nonce'));
                
                wp_redirect($nonce_url);
                exit;
            }
        }

        if (empty($project_id)) {
            $args = array(
                'post_type'      => 'project',
                'posts_per_page' => 1,
                'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'customer_email',
                        'value'   => $search_email,
                        'compare' => '='
                    ),
                    array(
                        'key'     => 'customer_last_name',
                        'value'   => $search_last_name,
                        'compare' => '='
                    )
                )
            );

            $projects = get_posts($args);

            if (count($projects) == 0) {
                $redirect_url = site_url("/resend-project/?noprojectemail=true");
                $nonce_url = htmlspecialchars_decode(wp_nonce_url($redirect_url, 'noprojectemail_pass_get_nonce', 'noprojectemail-pass-get-nonce'));
                
                wp_redirect($nonce_url);
                exit;
            }

            $project_id = $projects[0]->ID;
            $customer_name = Duzz_Helpers::duzz_get_field('customer_first_name', $project_id) ?: 'there';

            $data = [
                'email_address' => $search_email,
                'subject' => 'Resend Project Link!',
                'first_name' => $customer_name,
                'login_url' => site_url("/your-project/{$project_id}/"),
                'staff_name' => Duzz_Helpers::duzz_get_field(Duzz_Staff_Keys::$first_name, 'user_262'),
                'message' => 'You requested to resend your project link. A project with your email has been found:',
                'company_sig' => get_the_title(9909),
            ];

            $email = new Duzz_Email('invite', $data);

            if ($email->send() === true) {
                Duzz_Status_Feed::add_to_status_feed('Customer requested project email resend', $project_id);

                $redirect_url = site_url("/resend-project/?resendprojectemail=true");
                $nonce_url = htmlspecialchars_decode(wp_nonce_url($redirect_url, 'resendprojectemail_pass_get_nonce', 'resendprojectemail-pass-get-nonce'));
                
                wp_redirect($nonce_url);
                exit;
            } else {
                Duzz_Status_Feed::add_to_status_feed('Failed Project Access: New device detected', $project_id);

                $redirect_url = site_url("/resend-project/?failedprojectemail=true");
                $nonce_url = htmlspecialchars_decode(wp_nonce_url($redirect_url, 'failedprojectemail_pass_get_nonce', 'failedprojectemail-pass-get-nonce'));
                
                wp_redirect($nonce_url);
                exit;
            }
        }
    }
}



}


$init_processes = new Duzz_Processes;