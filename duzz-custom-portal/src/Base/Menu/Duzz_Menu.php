<?php

namespace Duzz\Base\Menu;

class Duzz_Menu{
    public function __construct()
    {
        if (!is_customize_preview()) {
            add_filter('wp_setup_nav_menu_item', array($this, 'add_custom_nav_fields'));
        }

        add_action('wp_update_nav_menu_item', array($this, 'update_custom_nav_fields'), 10, 3);
        add_filter('wp_edit_nav_menu_walker', array($this, 'edit_walker'), 10, 2);
        add_filter('wp_get_nav_menu_items', array($this, 'filter_menu_items'), 10, 3);
    }

    public function add_custom_nav_fields($menu_item) {
        $menu_item->visibility = sanitize_text_field(get_post_meta($menu_item->ID, '_menu_item_visibility', true));
        return $menu_item;
    }

    public function update_custom_nav_fields($menu_id, $menu_item_db_id, $args) {
        if (isset($_REQUEST['menu-item-visibility']) && isset($_REQUEST['menu-item-visibility'][$menu_item_db_id])) {
            $visibility = sanitize_text_field($_REQUEST['menu-item-visibility'][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, '_menu_item_visibility', $visibility);
        }
    }

    public function edit_walker($walker, $menu_id) {
        return 'Duzz\Base\Menu\Duzz_Menu_Edit';
    }

    public function filter_menu_items($items, $menu, $args) {
        $filtered_items = array();

        foreach ($items as $item) {
            $visibility = sanitize_text_field(get_post_meta($item->ID, '_menu_item_visibility', true));

            if (
                empty($visibility) ||
                $visibility === 'all' ||
                ($visibility === 'logged_in' && is_user_logged_in()) ||
                ($visibility === 'logged_out' && !is_user_logged_in())
            ) {
                $filtered_items[] = $item;
            }
        }

        return $filtered_items;
    }
}
