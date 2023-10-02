<?php 

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Pagination_Factory {
  public function create_pagination($list_args, $items_per_page = 10, $current) {
    $base = add_query_arg('paged', '%#%'); // Always use 'paged' now
    
    $pagination = new Duzz_Return_HTML('div', ['class' => 'pagination-div', 'id' => 'pagination_div']);

    $pagination_args = array(
      'current' => $current,
      'total' => ceil($list_args['total'] / $items_per_page),
      'base' => $base,
      'format' => '',
      'prev_text' => __('« prev'),
      'next_text' => __('next »'),
    );
    
    $pagination_links = paginate_links($pagination_args);

    $pagination->addChild('div', [], $pagination_links);

    return $pagination;
  }
}
