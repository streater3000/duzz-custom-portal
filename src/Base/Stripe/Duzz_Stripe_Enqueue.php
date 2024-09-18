<?php

namespace Duzz\Base\Stripe;

use Duzz\Core\Duzz_Get_Data;

class Duzz_Stripe_Enqueue {

    private $plugin_version;

    public function __construct() {
        $this->plugin_version = DUZZ_PLUGIN_VERSION;
        
        add_action('wp_enqueue_scripts', array($this, 'duzz_enqueue_scripts'));
    }

    public function duzz_enqueue_scripts() {
        // Check if we are on the specific page with ID 9924
        if (is_page(9924)) {
            wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], $this->plugin_version, true);
            wp_enqueue_script('stripe', DUZZ_PLUGIN_URL . '/js/stripe.js', ['stripe-js', 'jquery'], $this->plugin_version, true);

            // Create a nonce for security
            $ajax_nonce = wp_create_nonce('my_ajax_nonce');

            // Localize the script for AJAX calls
            wp_localize_script('stripe', 'my_ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => $ajax_nonce
            ));

            // Get the correct Stripe API key based on test mode status
            $is_test_mode = Duzz_Get_Data::duzz_get_form_id('duzz_stripe_settings_stripe_test_toggle_field_data', 'Stripe_Test_API') == 1;
            $stripeKey = $is_test_mode ?
                Duzz_Get_Data::duzz_get_form_id('duzz_stripe_settings_stripe_test_keys_field_data', 'API_publishable_key_test') :
                Duzz_Get_Data::duzz_get_form_id('duzz_stripe_settings_stripe_keys_data_field_data', 'API_publishable_key_live');

            // Pass the Stripe key to the script
            $data_to_pass = array(
                'stripeKey' => $stripeKey
            );
            wp_localize_script('stripe', 'duzzStripeData', $data_to_pass);
        }
    }
}
