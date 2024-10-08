<?php

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Layout\Factory\Duzz_List_Factory;
use \WP_Query;


class Duzz_List_Pages {
  private $post_type;
  private $list_args;
  private $options_name;


  public function __construct($post_type, $items_per_page = 10, $options_name = null, $field_name = null, $include_fields = true) {
    $this->post_type = $post_type;
    $this->list_args = array();
    $this->options_name = $options_name;
    $this->items_per_page = $items_per_page;
    $this->field_name = $field_name;
    $this->include_fields = $include_fields;
  }
public function duzz_add_list_args($user_roles, $meta_key, $meta_value, $comparison = '=') {
    if ($user_roles === null || $user_roles === '') {
        if ($meta_value === 'empty') {
            // Search for entities where the meta key either doesn't exist 
            // or exists with an empty value
            $this->list_args['meta_query'][] = array(
                'relation' => 'OR',
                array(
                    'key' => $meta_key,
                    'compare' => 'NOT EXISTS', // checks non-existence of the key
                ),
                array(
                    'key' => $meta_key,
                    'value' => '',  // checks for an empty value
                    'compare' => '=',
                ),
            );
        } else {
            if ($comparison === 'contains') {
                $this->list_args['meta_query'][] = array(
                    'key' => $meta_key,
                    'value' => $meta_value,
                    'compare' => 'LIKE', // check if value contains the meta_value
                );
            } else {
                $this->list_args['meta_query'][] = array(
                    'key' => $meta_key,
                    'value' => $meta_value,
                    'compare' => '=', // default behavior, check if values are equal
                );
            }
        }
    } else {
        if ($meta_value === 'empty') {
            // Special project for searching for users where the meta key either doesn't exist 
            // or exists with an empty value and has a specific role
            $this->list_args['meta_query'][] = array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => $meta_key,
                        'compare' => 'NOT EXISTS', // checks non-existence of the key
                    ),
                    array(
                        'key' => $meta_key,
                        'value' => '',  // checks for an empty value
                        'compare' => '=',
                    ),
                ),
                array(
                    'key' => 'user_role',
                    'value' => $user_roles,
                    'compare' => 'IN',
                ),
            );
        } else {
            if ($comparison === 'contains') {
                $this->list_args['meta_query'][] = array(
                    'relation' => 'AND',
                    array(
                        'key' => $meta_key,
                        'value' => $meta_value,
                        'compare' => 'LIKE', // check if value contains the meta_value
                    ),
                    array(
                        'key' => 'user_role',
                        'value' => $user_roles,
                        'compare' => 'IN',
                    ),
                );
            } else {
                $this->list_args['meta_query'][] = array(
                    'relation' => 'AND',
                    array(
                        'key' => $meta_key,
                        'value' => $meta_value,
                        'compare' => '=', // default behavior, check if values are equal
                    ),
                    array(
                        'key' => 'user_role',
                        'value' => $user_roles,
                        'compare' => 'IN',
                    ),
                );
            }
        }
    }

    $this->list_args['posts_per_page'] = 10;
}

private function duzz_should_apply_args($user_roles) {
    $user = wp_get_current_user();
    $user_roles = (array) $user_roles; // Convert to an array if it's a string

    return empty($user_roles) || array_intersect($user_roles, (array) $user->roles);
}

  public function duzz_get_list_args() {
    $this->list_args['meta_query'] = $this->list_args['meta_query'] ?? [];
    $this->list_args['post_type'] = $this->post_type;
    $this->list_args['options_name'] = $this->options_name;
    $this->list_args['field_name'] = $this->field_name;
    $this->list_args['include_fields'] = $this->include_fields;

    return $this->list_args;
  }


public function duzz_get_post_id() {
    $post_id = get_query_var($this->post_type . '_id', false);

    return $post_id ? absint($post_id) : null;
}


  public function duzz_get_user_id() {
    $user_id = get_current_user_id();
    return $user_id;
  }

public function duzz_render() {

  $list_args = $this->duzz_get_list_args();
  $items_per_page = $this->items_per_page;
  $post_id = $this->duzz_get_post_id();
  $user_id = $this->duzz_get_user_id();
  $options_name = $this->options_name;
  $post_type = $this->post_type;
  

    $factory = new Duzz_List_Factory($list_args, $post_id, $user_id, $items_per_page);
    $list = $factory->duzz_render();
  
  return $list;
}
}