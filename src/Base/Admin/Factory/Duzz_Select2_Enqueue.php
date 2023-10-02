<?php

namespace Duzz\Base\Admin\Factory;

class Duzz_Select2_Enqueue {
    
    private $plugin_version;
    
    public function __construct() {
        $this->plugin_version = DUZZ_PLUGIN_VERSION;
        add_action('admin_enqueue_scripts', [$this, 'enqueue_select2_assets']);
    }

    public function enqueue_select2_assets() {
        // Enqueue Select2 CSS
        wp_enqueue_style('select2', DUZZ_PLUGIN_URL . 'vendor/select2/select2/dist/css/select2.min.css', [], $this->plugin_version);

        // Enqueue Select2 JS
        wp_enqueue_script('select2', DUZZ_PLUGIN_URL . 'vendor/select2/select2/dist/js/select2.min.js', ['jquery'], $this->plugin_version, true);
    }
}
