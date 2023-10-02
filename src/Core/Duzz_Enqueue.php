<?php 

namespace Duzz\Core;

class Duzz_Enqueue {
    private $plugin_version;

    public function __construct() {
        $this->plugin_version = DUZZ_PLUGIN_VERSION;
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

public function enqueue_scripts() {
    // Existing enqueues
    wp_enqueue_script('jquery');
    wp_enqueue_script('tribute_js', DUZZ_PLUGIN_URL . '/js/tribute.js', array('jquery'), $this->plugin_version, true); 
    wp_enqueue_script('modal_js', DUZZ_PLUGIN_URL . '/js/featherlight.min.js', array('jquery'), $this->plugin_version, true);
    wp_enqueue_script('tribute_init_js', DUZZ_PLUGIN_URL . '/js/tribute_init.js', [], $this->plugin_version, true);
    wp_enqueue_script('custom_script', DUZZ_PLUGIN_URL . '/js/custom_script.js', [], $this->plugin_version, true);

         if (is_page(9924) || is_page(9925)) {
            // If the condition is met, enqueue your scripts and styles
            
        wp_enqueue_style('featherlight', DUZZ_PLUGIN_URL . '/assets/css/featherlight.min.css', [], $this->plugin_version);

    }
}
}