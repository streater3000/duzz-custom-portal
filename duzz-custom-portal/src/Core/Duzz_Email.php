<?php

namespace Duzz\Core;

use Duzz\Core\Duzz_Get_Data;

/**
 * Email helper class.
 */

class Duzz_Email {

	public $template;
	public $data;
	public $headers = [ 'Content-Type: text/html; charset=UTF-8' ];

	/**
	 * Autoload method
	 */
	public function __construct( $template = 'blank', $data = [] ) {
		$this->template = $template;
		$this->data     = $data;

		add_filter( 'wp_mail_from', array( $this, 'sender_email' ) );
		add_filter( 'wp_mail_from_name', array( $this, 'sender_name' ) );
	}

/**
 * Set the sender email.
 */
public function sender_email() {
	 $admin_email_settings = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'admin_email');
    return $admin_email_settings;
}

/**
 * Set the sender name.
 */
public function sender_name() {
	 $admin_email_settings = Duzz_Get_Data::get_form_id('settings_email_settings_field_data', 'company_name');
    return $admin_email_settings;
}

	/**
	 * Send an email.
	 */
	public function send() {
		$contents = file_get_contents( DUZZ_PLUGIN_DIR . 'templates/email/' . $this->template . '.html' );

		foreach ( $this->data as $key => $value ) {
			$contents = str_replace( '{{ ' . $key . ' }}', $value, $contents );
		}

		$sent = wp_mail( $this->data['email_address'], $this->data['subject'], $contents, $this->headers );

		return $sent;
	}


}
