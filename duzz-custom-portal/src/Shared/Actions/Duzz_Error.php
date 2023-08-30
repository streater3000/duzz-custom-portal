<?php

namespace Duzz\Shared\Actions;

class Duzz_Error {
  public static function print_error_message($key, $message, $is_success = true) {
    if (isset($_GET[$key]) && ($_GET[$key] === 'true') === $is_success) {
      return '<p class="' . ($is_success ? 'duzz-success' : 'duzz-error') . '">' . $message . '</p>';
    }
    return '';
  }

  public static function list_error_messages() {
    $message = '';
    $message .= self::print_error_message('no-project-email', 'Error: Email does not exist. Try again.');
    $message .= self::print_error_message('resend-project-email', 'Email resent with Project Link.');
    $message .= self::print_error_message('failed_project_email', 'Try again or contact us for help.');
    $message .= self::print_error_message('sentinvite', 'Customer invite sent.');
    $message .= self::print_error_message('sentinvite', 'Customer invite sent.', false);
    return $message;
  }
}
