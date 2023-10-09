<?php

namespace Duzz\Base;

class Duzz_Post_Types {

    public $post_types = [];

    public function __construct() {
        add_action('init', array($this, 'duzz_register_post_types'));
    }

	public function duzz_getAllPostTypeNames() {
    return array_keys($this->post_types);
}
 

    public function duzz_register_post_types() {
        foreach ($this->post_types as $name => $args) {
            register_post_type($name, $args);
        }
    }
}
