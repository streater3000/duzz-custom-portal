<?php 

namespace Duzz\Shared\Layout\Factory;

class Duzz_Side_Bar {

    public function __construct() {
        add_action('widgets_init', [$this, 'register_duzz_sidebar']);
        add_action('dynamic_sidebar_before', [$this, 'insert_duzz_menu']);
    }

    public function register_duzz_sidebar() {
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

    public function print_duzz_menu() {
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

    public function insert_duzz_menu($index) {
        if ($index === 'duzz-sidebar') {
            return $this->print_duzz_menu();
        }
    }
}
