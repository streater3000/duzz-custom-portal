<?php

namespace Duzz\Base\Admin\Factory;

class Duzz_Select2_Enqueue {
    
    public function __construct() {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_select2_assets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_select2_assets']);
    }

    public function enqueue_select2_assets() {
        // Enqueue Select2 CSS
        wp_enqueue_style('select2', DUZZ_PLUGIN_URL . 'vendor/select2/select2/dist/css/select2.min.css');

        // Enqueue Select2 JS
        wp_enqueue_script('select2', DUZZ_PLUGIN_URL . 'vendor/select2/select2/dist/js/select2.min.js', array('jquery'), null, true);
    }
}
