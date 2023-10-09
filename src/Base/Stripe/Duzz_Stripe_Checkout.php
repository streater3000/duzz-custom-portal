<?php

namespace Duzz\Base\Stripe;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Actions\Duzz_Status_Feed;
use Duzz\Core\Duzz_Data_Passer;

class Duzz_Stripe_Checkout {

    private $stripeSecretKey;

    public function __construct() {
        // Determine if Stripe testing is turned on
        $is_test_mode = Duzz_Get_Data::duzz_get_form_id('duzz_payment_settings_stripe_toggle_field_data', 'Stripe_Test_API') == 1;

        // Retrieve the appropriate secret key based on testing mode
        if ($is_test_mode) {
            $this->stripeSecretKey = Duzz_Get_Data::duzz_get_form_id('duzz_payment_settings_stripe_test_field_data', 'API_secret_key_test');
        } else {
            $this->stripeSecretKey = Duzz_Get_Data::duzz_get_form_id('duzz_payment_settings_stripe_keys_field_data', 'API_secret_key_live');
        }

        // Initialize Stripe with the fetched secret key
        Stripe::setApiKey($this->stripeSecretKey); 

        $this->dataPasser = new Duzz_Data_Passer();
        add_action('template_redirect', [$this, 'duzz_check_for_payment_success']);

    }

   public function generatePayNowButton($amount, $project_id) {
        $clientSecret = $this->duzz_generateStripePopupContent($amount);

        // Error handling, in project we get an error message instead of a client secret
        if (strpos($clientSecret, 'Error:') !== false) {
            return $clientSecret;  // Return the error message directly
        }

        // Create the button
    $invoice_table = '<button type="button" class="featherlight-stipe-trigger" data-featherlight="#stripe-popup" data-secret="' . $clientSecret . '" data-amount="' . $amount . '" data-project-id="' . $project_id . '" >Pay Now</button>';

        return $invoice_table;
    }

    private function duzz_generateStripePopupContent($amount) {
        // Convert amount to cents (or smallest currency unit).
        $amountInCents = $amount * 100;

        // Create a payment intent
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);

            return $paymentIntent->client_secret;

        } catch (\Exception $e) {
            // Handle the error. For simplicity, we'll just output a simple message.
            return "Error: " . $e->getMessage();
        }
    }


public function duzz_check_for_payment_success() {

    
    $client_secret = get_query_var('payment_intent_client_secret', null);
    $payment_intent_id = get_query_var('payment_intent', null);
    $project_id = get_query_var('project_id', null); 
    
    if ($client_secret && $payment_intent_id && $project_id) {

        
        $payment_ids = get_posts([
            'post_type' => 'payment',
            'meta_key' => 'project_id',
            'meta_value' => $project_id,
            'posts_per_page' => 1,
            'fields' => 'ids',
        ]);
        
        if (empty($payment_ids)) {
            return;
        }
        
        $payment_id = $payment_ids[0];
        $current_payment_status = get_post_meta($payment_id, 'payment_confirm', true);
        
        if (strpos($current_payment_status, 'Completed') !== false) {
            return;
        }
        
        $amount = get_post_meta($payment_id, 'total_aftertax_sum', true);
        
        try {
            $paymentIntent = PaymentIntent::retrieve([
                'id' => $payment_intent_id,
                'expand' => ['charges.data.balance_transaction']
            ]);
            
            if ($paymentIntent->status === 'succeeded') {
                
                Duzz_Status_Feed::duzz_add_to_status_feed('Payment Complete! Total payment amount: ' . $amount, $project_id);
                update_post_meta($payment_id, 'payment_confirm', 'Completed');
                
                $associated_comment_id = get_post_meta($payment_id, 'associated_comment', true);
                
                if ($associated_comment_id) {
                    wp_delete_comment($associated_comment_id, true);
                }
            }

        } catch (\Exception $e) {
            // Handle exceptions, perhaps logging them or notifying an admin
        }
    }
}


}



