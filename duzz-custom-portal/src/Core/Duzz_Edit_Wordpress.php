<?php

namespace Duzz\Core;

class Duzz_Edit_Wordpress {

    public function __construct() {
        add_action('wp', [$this, 'duzz_projects_buffer'], 0);
        add_action('pre_get_comments', [$this, 'filter_comments']);
    }

   public function duzz_projects_buffer() {
        if ( is_page( 9924 ) ) {
            ob_start(); // Start output buffering
            add_action('wp_head', [$this, 'duzz_projects_head'], 0); 
        }
    }

    public function duzz_projects_head() {
     if (function_exists('acf_form_head')) {
    // ACF is active, so run the acf_form_head() function
    acf_form_head();
}
        ob_end_flush(); // Flush the output buffer and send it to the browser
    }


    public function filter_comments($query) {
        if (!is_admin()) {
            return;
        }

        global $pagenow;

        if ($pagenow === 'edit-comments.php') {
            $meta_query = [
                'relation' => 'OR',
                [
                    'key' => 'comment_post_type',
                    'value' => 'project',
                    'compare' => '!=',
                ],
                [
                    'key' => 'comment_post_type',
                    'compare' => 'NOT EXISTS', // This will include comments where the 'comment_post_type' meta key isn't set
                ]
            ];

            $query->query_vars['meta_query'] = $meta_query;
        }
    }

}



