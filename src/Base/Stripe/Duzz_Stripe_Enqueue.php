<?php

namespace Duzz\Base\Stripe;

use Duzz\Core\Duzz_Get_Data;

class Duzz_Stripe_Enqueue {

    private $plugin_version;

    public function __construct() {
        $this->plugin_version = DUZZ_PLUGIN_VERSION;
        
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));


    }

    public function enqueue_scripts() {
        wp_enqueue_script('stripe-js', 'https://js.stripe.com/v3/', [], $this->plugin_version, true);
        wp_enqueue_script('stripe', DUZZ_PLUGIN_URL . '/js/stripe.js', ['stripe-js', 'jquery'], $this->plugin_version, true);

        // Create a nonce for security
        $ajax_nonce = wp_create_nonce('my_ajax_nonce');

        // Localize the script for AJAX calls
        wp_localize_script('stripe', 'my_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $ajax_nonce
        ));

        $is_test_mode = Duzz_Get_Data::get_form_id('payment_settings_stripe_toggle_field_data', 'Stripe_Test_API') == 1;
        $stripeKey = $is_test_mode ?
            Duzz_Get_Data::get_form_id('payment_settings_stripe_test_field_data', 'API_publishable_key_test') :
            Duzz_Get_Data::get_form_id('payment_settings_stripe_keys_field_data', 'API_publishable_key_live');

        $data_to_pass = array(
            'stripeKey' => $stripeKey
        );
        wp_localize_script('stripe', 'duzzStripeData', $data_to_pass);
    }
}

