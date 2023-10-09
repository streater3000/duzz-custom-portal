<?php

namespace Duzz\Core;

use Duzz\Core\Duzz_Get_Data;

/**
 * Email helper class.
 */
class Duzz_Email {
    public $template;
    public $data;
    public $headers = ['Content-Type: text/html; charset=UTF-8'];

    /**
     * Autoload method
     */
    public function __construct($template = 'blank', $data = []) {
        $this->template = $template;
        $this->data     = $data;

        add_filter('wp_mail_from', [$this, 'duzz_sender_email']);
        add_filter('wp_mail_from_name', [$this, 'duzz_sender_name']);
    }

    /**
     * Set the sender email.
     */
    public function duzz_sender_email() {
        $admin_email_settings = Duzz_Get_Data::duzz_get_form_id('duzz_settings_email_settings_field_data', 'admin_email');
        return $admin_email_settings;
    }

    /**
     * Set the sender name.
     */
    public function duzz_sender_name() {
        $admin_email_settings = Duzz_Get_Data::duzz_get_form_id('duzz_settings_email_settings_field_data', 'company_name');
        return $admin_email_settings;
    }

    /**
     * Send an email.
     */
    public function duzz_send() {
        global $wp_filesystem;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        $filepath = DUZZ_PLUGIN_DIR . 'templates/email/' . $this->template . '.html';

        if ($wp_filesystem && $wp_filesystem->is_readable($filepath)) {
            $contents = $wp_filesystem->get_contents($filepath);
        } else {
            // Handle the error appropriately, e.g., log error, display error message, etc.
            return false;
        }

        foreach ($this->data as $key => $value) {
            $contents = str_replace('{{ ' . $key . ' }}', $value, $contents);
        }

        $sent = wp_mail($this->data['email_address'], $this->data['subject'], $contents, $this->headers);

        return $sent;
    }
}
