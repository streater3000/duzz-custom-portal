<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Layout\Factory as FactoryNamespace;
use Duzz\Shared\Entity\Duzz_Role;
use Duzz\Core\Duzz_Helpers;
use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Comment_List_Factory {

    private $list_args;
    private $post_id;
    private $user_id;
    private $list_name;
    private $items_per_page;
    private $feed_textarea;
    private $post_type;

    public function __construct($list_args, $post_id, $user_id, $items_per_page) {
        $this->list_args = $list_args;
        $this->user_id = $user_id;
        $this->post_id = $post_id;   
        $this->items_per_page = $items_per_page;

        $this->post_type = $this->list_args['post_type'];
        $this->meta_query = $this->list_args['meta_query'];
        $this->list_name = $this->list_args['list_name'];
        $this->options_name = $this->list_args['options_name'];
        $this->feed_textarea = new FactoryNamespace\Duzz_Feed_Textarea($this->post_id, $this->user_id, $this->post_type);
    }

    public function duzz_render() {
        $comment_feed = $this->duzz_display_comment_feed();
        return $comment_feed->duzz_render();
    }

    private function duzz_display_comment_feed() {
        $status_feed = new Duzz_Return_HTML('div', ['class' => 'duzz_status_feed', 'id' => 'status_feed']);
        
        $this->role = new Duzz_Role();
        $cu_role = $this->role->duzz_get_user_role_by_id(get_current_user_id());
        $comments_html = $this->duzz_format_comments($this->post_id, $cu_role);

        $status_feed->duzz_addChild($comments_html);

        return $status_feed;
    }

    private function duzz_format_comments($post_id, $cu_role) {
        $args = $this->duzz_get_args();

        $comments = $args['comment_query'];
        $total_items = count($comments);

        $list_args = array(
            'total' => $total_items,
        );

        $comments_div = new Duzz_Return_HTML('div', ['class' => 'duzz_status_feed', 'id' => 'status_feed']);

        if (in_array($cu_role, ['duzz_admin', 'no_role', 'administrator'])) {
            if (!empty($this->post_id)) {
                $feed_textarea = new FactoryNamespace\Duzz_Feed_Textarea($this->post_id, $this->user_id, $this->post_type);
                $form = $feed_textarea->duzz_get_status_feed_form();
                $chatfeed_title_div = $feed_textarea->duzz_get_chatfeed_title_div();

                $comments_div->duzz_addChild($form);
                $comments_div->duzz_addChild($chatfeed_title_div);
            }
        }

        foreach(array_slice($comments, ($this->paged - 1) * $this->items_per_page, $this->items_per_page) as $comment) {
            $comment_div = $this->duzz_create_comment_div($comment, $cu_role);
            $comments_div->duzz_addChild($comment_div);
        }

        $pagination_factory = new FactoryNamespace\Duzz_Pagination_Factory();
        $pagination = $pagination_factory->duzz_create_pagination($list_args, $this->items_per_page, $this->current);
        $comments_div->duzz_addChild($pagination);

        return $comments_div;
    }

private function duzz_get_args() {

    $this->current = max(1, get_query_var('paged'));
    $this->paged = get_query_var('paged') ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => $this->post_type,
            'post_id' => !empty($this->post_id) ? $this->post_id : null,
            'offset' => ($this->paged - 1) * $this->items_per_page,
            'paged' => $this->paged,
        );


    $comment_query = new \WP_Comment_Query($args);

    if (empty($this->post_id)) {
        $mentions_args = array(
            'search' => get_current_user_id(),
            'author__not_in' => $this->user_id,
            'offset' => ($this->paged - 1) * $this->items_per_page,
        );

            $mentions_comment_query = new \WP_Comment_Query($mentions_args );

        $comments_merge = array_merge((array) $comment_query->found_comments, (array) $mentions_comment_query->found_comments);

       $args['total'] = $comments_merge;

    } else {
        $args['total'] = $comment_query->found_comments;
    }

    $args['comment_query'] = $comment_query->comments;


    return $args;
}


private function duzz_create_comment_div($comment, $cu_role) {
    $display_data = $this->duzz_get_comment_display_data($comment, $cu_role);
    $display_name = $display_data['display_name'];
    $display_role = $display_data['display_role'];
    $newdatetime = $display_data['newdatetime'];
    $comment_role = $display_data['comment_role'];
    $email = $display_data['email'];

    $comment_div = new Duzz_Return_HTML('div', ['class' => "comment comment--role_{$comment_role}", 'id' => "comment--id_{$comment->comment_ID}"]);
    $avatar = get_avatar($email); 

    $comment_content = wpautop($comment->comment_content);

if ($comment_role != 'no_role' && $comment_role != 'duzz_bot') {
    $avatar = get_avatar($email);
    $comment_div->duzz_addChild('div', ['class' => 'comment__avatar'], $avatar);
}

    // Create a new container div for flex alignment
    $info_container = new Duzz_Return_HTML('div', ['class' => 'comment__info-container']);

    $info_container->duzz_addChild('div', ['class' => 'comment__author-role'], $display_role);
    $info_container->duzz_addChild('div', ['class' => 'comment__author'], $display_name);
    $info_container->duzz_addChild('div', ['class' => 'comment__date'], $newdatetime);

            $comment_div->duzz_addChild($info_container);

    $comment_div->duzz_addChild('div', ['class' => 'comment__content'], $comment_content);

  if (empty($this->post_id)) {

    $link_attributes = array(
        'href' => site_url('/project/') . $comment->comment_post_ID,
        'target' => '_blank',
        'rel' => 'noopener noreferrer',
    );
    $link_text = 'View Project →';
    $comment_div->duzz_addChild('a', $link_attributes, $link_text);
}


    return $comment_div;
}


private function duzz_get_comment_display_data($comment, $cu_role) {
    $author = get_user_by('id', $comment->user_id);
    $role = $this->role->duzz_get_user_role_by_id($comment->user_id);
    $display_name = 'Empty';
    $display_role = 'Empty';
    $email = '';  // Initialize the email variable

    // Get the title of the project type with 'company_id' => 9909
    $company_title = get_the_title(9909);

    // Get the name of the User ID 262
    $user_id_262_name = trim(Duzz_Helpers::duzz_get_name(262));

    if (in_array($cu_role, ['duzz_customer', 'no_role'])) {
        if (in_array($role, ['duzz_admin', 'administrator'])) {
            $display_role = $company_title ?: 'Admin';
            $display_name = trim(Duzz_Helpers::duzz_get_name($comment->user_id)) ?: $user_id_262_name;
            $email = $author->user_email;  // Retrieve the email
        } else if ($role == 'no_role') {
            $display_role = 'Customer';
            $display_name = 'You' ?: 'Name Missing';
            $email = Duzz_Helpers::duzz_get_projectemail($comment->comment_post_ID);
        } else {
            $display_name = trim(Duzz_Helpers::duzz_get_name($comment->user_id)) ?: $user_id_262_name;
            $display_role = $company_title;
            $email = $author->user_email;  // Retrieve the email
        }
    }

    if (in_array($cu_role, ['duzz_admin', 'administrator'])) {
        if ($role == 'no_role') {
            $display_role = 'Customer';
            $display_name = trim(Duzz_Helpers::duzz_get_projectname($comment->comment_post_ID)) ?: 'Missing Name';
            $email = Duzz_Helpers::duzz_get_projectemail($comment->comment_post_ID);
        } else {
            $display_role = Duzz_Helpers::duzz_get_role_name($role);
            $display_name = trim(Duzz_Helpers::duzz_get_name($comment->user_id)) ?: $user_id_262_name;
            $email = $author->user_email;  // Retrieve the email
        }
    }

    $olddatetime = $comment->comment_date;
    $newdatetime = gmdate('m/d/y h:i A', strtotime($olddatetime));

    return [
        'display_name' => $display_name,
        'display_role' => $display_role,
        'newdatetime' => $newdatetime,
        'comment_role' => $role,
        'email' => $email,  // Add email to the returned array
    ];
}

}