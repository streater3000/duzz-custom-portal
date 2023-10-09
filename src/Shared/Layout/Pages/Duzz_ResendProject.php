<?php

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Shared\Actions\Duzz_IP_Check;
use Duzz\Shared\Actions\Duzz_Error;

class Duzz_ResendProject {
    private $project_id;
    private $first_name;
    private $new_ip;
    private $form;

    public function __construct() {
    $this->duzz_checkInfo();
    $this->duzz_createForm();
}


public function duzz_render() {
    $errorMessage = Duzz_Error::duzz_list_error_messages();


    // Concatenate the error message with the form rendering
    return $errorMessage . $this->form->duzz_render();
}


    private function duzz_createForm() {
        $this->form = new Duzz_Return_HTML('form', array(
            'class' => 'duzz-inline-form add-team-member',
            'method' => 'POST',
        ));

        $this->duzz_addHiddenFields();
        $this->duzz_addVisibleFields();
        $this->duzz_addSendButton();
    }

private function duzz_addHiddenFields() {
    $this->form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'project_id',
        'value' => $this->project_id,
    ));

    $this->form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'first_name',
        'value' => $this->first_name,
    ));

    $this->form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'action',
        'value' => 'resend_project_email',
    ));

    $this->form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'ip',
        'value' => $this->new_ip,
    ));

    // Create a nonce
    $nonce = wp_create_nonce('resend_project_email');

    // Add the nonce field to the form
    $this->form->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => '_wpnonce',
        'value' => $nonce,
    ));
}

    private function duzz_addVisibleFields() {
        $this->form->duzz_addChild('input', array(
            'type' => 'text',
            'id' => 'project_email_search',
            'name' => 'project_email_search',
            'placeholder' => 'Email Address',
            'required' => '',
            'class' => 'reset-field-width',
        ));

        $this->form->duzz_addChild('input', array(
            'type' => 'text',
            'id' => 'project_last_name_search',
            'name' => 'project_last_name_search',
            'placeholder' => 'Last Name',
            'required' => '',
            'class' => 'reset-field-width',
        ));
    }

    private function duzz_addSendButton() {
        $this->form->duzz_addChild('input', array(
            'type' => 'submit',
            'value' => 'Resend Project',
        ));
    }

private function duzz_checkInfo() {
    // Getting the project_id from the URL using WordPress get_query_var function
    $this->project_id = absint(get_query_var('project_id', false)); // Set the class property here

    if (!$this->project_id) {
        return;
    }

    $ip_check = new Duzz_IP_Check();
    $ip = $ip_check->duzz_get_client_ip_address();
    $this->new_ip = $ip;
}


}
