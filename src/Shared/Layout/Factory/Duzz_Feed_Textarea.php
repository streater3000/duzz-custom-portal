<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Feed_Textarea
{
    private $post_id;
    private $user_id;

    public function __construct($post_id, $user_id, $post_type)
    {
        $this->post_id = $post_id;
        $this->user_id = $user_id;
        $this->post_type_id = ($post_type . '_id');
    }

    public function duzz_get_status_feed_form()
    {
        // Create your form here and return it
        $form = new Duzz_Return_HTML('form', array('class' => 'status-feed-form', 'id' => 'updates', 'method' => 'POST'));

        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => '_wpnonce',
            'value' => wp_create_nonce('add-project-update'),
        ));

        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => 'action',
            'value' => 'add_project_update',
        ));

        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => $this->post_type_id,
            'value' => $this->post_id,
        ));

        $form->duzz_addChild('input', array(
            'type' => 'hidden',
            'name' => 'current_user_id',
            'value' => $this->user_id,
        ));

        // add other inputs and textarea
        $textarea = $form->duzz_addChild('textarea', array(
            'id' => 'mentionable', 
            'name' => 'content', 
            'rows' => '4', 
            'placeholder' => 'Write message here...'
        ));

        $submitBtn = $form->duzz_addChild('input', array(
            'class' => 'submit-project-update-button',
            'type' => 'submit',
            'value' => 'Send Message'
        ));

        $errorSpan = $form->duzz_addChild('span', array(
            'id' => 'error-message',
            'style' => 'display:none; color:red; margin-left:10px;',
        ), 'Error: Empty Message');

        return $form;
    }

    public function duzz_get_chatfeed_title_div(){
        // Create your chatfeed title div here and return it
        $chatfeed_title_div = new Duzz_Return_HTML('div', ['class' => 'chatfeed-title']);
        return $chatfeed_title_div;
    }
}
