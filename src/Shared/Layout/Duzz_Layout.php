<?php

namespace Duzz\Shared\Layout;

use Duzz\Shared\Layout\Factory\Duzz_Side_Bar;
use Duzz\Shared\Layout\Pages\Duzz_WP_Forms;
use Duzz\Shared\Layout\Pages\Duzz_Projects_Page_Content;
use Duzz\Shared\Layout\Pages\Duzz_Customer_Page_Content;
use Duzz\Shared\Layout\Pages\Duzz_ResendProject;
use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Layout {
    public function __construct() {
        add_filter('the_content', [$this, 'duzz_display_page_content'], 11);
        add_action('init', array($this, 'register_rewrite_rules'), 10);
        add_filter('query_vars', array($this, 'add_custom_query_var'));
    }


    public function register_rewrite_rules() {
    add_rewrite_tag('%project_id%', '([0-9]+)');
    add_rewrite_rule('^project/([0-9]+)/?', 'index.php?pagename=project&project_id=$matches[1]', 'top');
    add_rewrite_rule('^your-project/([0-9]+)/?', 'index.php?pagename=your-project&project_id=$matches[1]', 'top');
}

    public function add_custom_query_var($vars) {
        $vars[] = "project_id";
        $vars[] = "payment_intent";
        $vars[] = "payment_intent_client_secret";
        $vars[] = "redirect_status";
        return $vars;
    }

    public function duzz_display_page_content($content) {
        if (!is_page()) {
            return $content;
        }

        $page_id = get_the_ID();
        $pages_with_sidebar = array(9921, 9923, 9924, 9926, 9920);
        $page_content = null;

        if (in_array($page_id, $pages_with_sidebar)) {
            $page_content = new Duzz_Return_HTML('div', ['class' => 'page-content-flex-container']);

            $side_bar = new Factory\Duzz_Side_Bar();
            $side_bar_html = $side_bar->print_duzz_menu();

            $sidebar_container = new Duzz_Return_HTML('div', ['class' => 'sidebar-container']);
            $sidebar_container->addChild('div', [], $side_bar_html); 
            $page_content->addChild('div', ['class' => 'sidebar-container'], $sidebar_container->render());                      
        }

    // Now continue with your switch projects.
    switch ($page_id) {
                case 9917:
                    // Display content for the 'Resend Project' page
                    $resend_project = new Duzz_ResendProject();
                    return $resend_project->render();
                    break;
                case 9921:
                    // Display content for the 'User Create Project' page
                    $wp_forms = new Duzz_WP_Forms();
                    $wp_forms->render_staff_form();
                    $container = $wp_forms->container->render();
                    break;

                case 9922:
                    // Display content for the 'Customer' page
                    $wp_forms = new Duzz_WP_Forms();
                    $wp_forms->render_customer_form();
                    return $wp_forms->container->render();
                    break;

                case 9924:
                    // Display content for the 'Projects' page
                    $pageContent = new Duzz_Projects_Page_Content();
                    $container = $pageContent->container->render();
                    break;

                case 9925:
                    // Display content for the 'Customer' page
                    $pageContent = new Duzz_Customer_Page_Content();
                    return $pageContent->container->render();
                    break;

                case 9926:
                    $comment_factory = new Pages\Duzz_List_Factory("comments_list", "project", "comments", 5);
                    $container = $comment_factory->create_list();
                    break;

case 9923:
    // Instantiate the List_Projects class
    $table_factory = new Pages\Duzz_List_Factory("project_list", "project", "table", 10, "settings_list_projects_field_data", "selected_columns");
    $table_factory->add_list_args(null, 'archived', 0);

    do_action('before_create_list', $table_factory, $page_id);
    
    // Start buffering for the notification
    ob_start();
    do_action('duzz_display_notification');
    $notification_output = ob_get_clean();
    
    $container_content = $notification_output . $table_factory->create_list();
    $container = $container_content;
    break;


                case 9920:
                    // Instantiate the List_Projects class
                    $table_factory = new Pages\Duzz_List_Factory("archive_list", "project", "table", 10, "settings_list_projects_field_data", "selected_columns", false);
                    $table_factory->add_list_args(null, 'archived', 1);
                  do_action('before_archive_list', $table_factory, $page_id);
                    $container = $table_factory->create_list();
                    break;

                 default:
                    return $content;
                    break;
            }
         if (isset($container)) {
            $content_container = new Duzz_Return_HTML('div', ['class' => 'content-container']);
            $content_container->addChild('div', [], $container);

            $page_content->addChild('div', ['class' => 'content-container'], $content_container->render());
        }

        // Output the container
        $content = $page_content->render();

        return $content;
    }
}