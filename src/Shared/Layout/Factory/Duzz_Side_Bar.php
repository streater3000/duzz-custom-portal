<?php 

namespace Duzz\Shared\Layout\Factory;

class Duzz_Side_Bar {

    public function __construct() {
        add_action('widgets_init', [$this, 'duzz_register_sidebar']);
        add_action('dynamic_sidebar_before', [$this, 'duzz_insert_menu']);
    }

    public function duzz_register_sidebar() {
        register_sidebar(array(
            'name' => 'Duzz Sidebar',
            'id' => 'duzz-sidebar',
            'description' => 'Sidebar for the Duzz plugin',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widgettitle">',
            'after_title' => '</h2>',
        ));
    }

    public function duzz_print_menu() {
        ob_start();
        wp_nav_menu(array(
            'theme_location' => 'duzz-sidebar',
            'menu_class' => 'staff-sidebar',
            'container_class' => 'staff-menu',
            'container' => '',
            'echo' => true,
        ));
        $menu = ob_get_clean();
        return $menu;
    }

    public function duzz_insert_menu($index) {
        if ($index === 'duzz-sidebar') {
            return $this->duzz_print_menu();
        }
    }
}
