<?php

namespace Duzz\Base;

class Duzz_Post_Types {

    public $post_types = [];

    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
    }

	public function getAllPostTypeNames() {
    return array_keys($this->post_types);
}
    public function addType($name, $additional_supports = [], $show_in_menu = false) {
        $supports = array_merge(['title', 'custom-fields'], $additional_supports);

        $capabilities = $this->generateCapabilities($name);

        $args = [
            'labels' => [
                'name' => __( ucfirst($name) . 's' ),
                'singular_name' => __( ucfirst($name) ),
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => $supports,
            'taxonomies' => [],
            'hierarchical' => false,
            'show_in_menu' => $show_in_menu,
            'capabilities' => $capabilities,
        ];

        $args = apply_filters("duzz_{$name}_post_type_args", $args);

        $this->post_types[$name] = $args;
    }

    private function generateCapabilities($name) {
        return [
            'edit_post' => "edit_{$name}",
            'edit_posts' => "edit_{$name}s",
            'edit_others_posts' => "edit_others_{$name}s",
            'publish_posts' => "publish_{$name}s",
            'read_post' => "view_{$name}",
            'read_private_posts' => "view_private_{$name}s",
            'delete_post' => "archive_{$name}",
        ];
    }

    public function register_post_types() {
        foreach ($this->post_types as $name => $args) {
            register_post_type($name, $args);
        }
    }
}
