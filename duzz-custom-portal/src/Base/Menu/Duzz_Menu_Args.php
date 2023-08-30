<?php

namespace Duzz\Base\Menu;

class Duzz_Menu_Args
{
    public function __construct()
    {
        add_filter('wp_page_menu_args', array($this, 'duzz_exclude_pages_from_default_menu'));
    }


    public function duzz_exclude_pages_from_default_menu($args)
    {
        // Array of page IDs to exclude
        $exclude_page_ids = array(9923, 9924, 9925, 9926, 9921);

        if (isset($args['exclude'])) {
            $args['exclude'] .= ',' . implode(',', $exclude_page_ids);
        } else {
            $args['exclude'] = implode(',', $exclude_page_ids);
        }

        return $args;
    }
}
