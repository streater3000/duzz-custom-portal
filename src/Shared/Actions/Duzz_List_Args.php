<?php

namespace Duzz\Shared\Actions;

 class Duzz_List_Args {
  private $args;

  public function __construct($list_name, $user_role, $meta_key, $meta_value, $items_per_page) {
    $this->args = array();
    $this->args['meta_query'][] = array(
      'key' => $meta_key,
      'value' => $meta_value,
      'compare' => '=',
    );
    if (!empty($user_role)) {
      $this->args['role'] = $user_role;
    }
    if (!empty($items_per_page)) {
      $this->args['items_per_page'] = $items_per_page;
    }
    update_option($list_name . '_args', $this->args);
  }

  public function get_args() {
    return $this->args;
  }
}
