<?php

namespace Duzz\Core;

class Duzz_Edit_Wordpress {

    public function __construct() {
        add_action('wp', [$this, 'duzz_projects_buffer'], 0);
        add_action('pre_get_comments', [$this, 'duzz_filter_comments']);
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


public function duzz_filter_comments($query) {
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
                'compare' => 'NOT IN',
            ],
            [
                'key' => 'comment_post_type',
                'compare' => 'NOT EXISTS', // keep if there is no way around it
            ],
        ];

        // Maybe limit the number of comments queried.
        $query->query_vars['posts_per_page'] = apply_filters('duzz_comment_query_limit', 10); 
        $query->query_vars['meta_query'] = $meta_query;
    }
}

}



