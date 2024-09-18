<?php

namespace Duzz\Shared\Actions;

use Duzz\Core\Duzz_Helpers;
use Duzz\Core\Duzz_Get_Data;


class Duzz_Popup_Invite {

    public function duzz_invite_customer_button_shortcode() {
        // Use get_query_var instead of $_GET
        $project_id = absint(sanitize_text_field(get_query_var('project_id', 0)));
        
        // If project_id is 0, then it was not set, hence return
        if(!$project_id) {
            return;
        }

        if (!Duzz_Validate_ID::duzz_validate($project_id)) {
            return 'Invalid project_id provided';
        }

        // Get data and sanitize.
        $client_first = sanitize_text_field( Duzz_Helpers::duzz_get_field( 'customer_first_name', $project_id ) ?: '' );
        $client_last = sanitize_text_field( Duzz_Helpers::duzz_get_field( 'customer_last_name', $project_id ) ?: '' );
        $company_name = sanitize_text_field( Duzz_Helpers::duzz_get_field( 'client_company', $project_id ) ?: '' );
        $client_email = sanitize_email( Duzz_Helpers::duzz_get_field( 'customer_email', $project_id ) ?: '' );

        // Validate email
        if ( ! is_email( $client_email ) ) {
            $client_email = '';
        }

        $default_email_message = 'Hey ' . esc_html( $client_first ) . ', <br><br> You can track the progress of your project here:';

        $current_path = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );       

        $output = '
        <div class="flex-email">
          <div class="account-titles">Send to: </div>
          <div class="customer-email-shortcode left-account">' . esc_html( $client_email ) . '</div>
        </div>
        <div class="invite-button invite-button-customer">
          <form method="post" action="' . esc_url( $current_path ) . '">
            ' . wp_nonce_field( 'sendinvite', '_wpnonce', true, false ) . '
            <input type="hidden" name="project_id" value="' . esc_attr( $project_id ) . '"/>
            <input type="hidden" name="client_first" value="' . esc_attr( $client_first ) . '"/>
            <input type="hidden" name="client_last" value="' . esc_attr( $client_last ) . '"/>
            <input type="hidden" name="company_name" value="' . esc_attr( $company_name ) . '"/>
            <input type="hidden" name="client_email" value="' . esc_attr( $client_email ) . '"/>
            <input type="text" name="email_subject" placeholder="Subject"/>
             ' . apply_filters('duzz_before_send_invite_button', '', $project_id) . ' 
            <textarea type="text" name="email_message" id="email_message">' . esc_textarea( $default_email_message ) . '</textarea>
            <input class="button" type="submit" name="sendinvite" value="Send Invite" />
          </form>
        </div>
        <style>.flex-email{display:flex;margin-bottom: 5px;}</style>
        ';

        return $output;
    }
}

