<?php

namespace Duzz\Base;

class Duzz_NotificationFetcher {
    const API_ENDPOINT_URL = 'https://duzz.io/wp-json/duzz_new/v1/updated_message/';
    const API_KEY = 'duzz_new_api_notification';

    public function __construct() {
        add_action('in_admin_header', [$this, 'display_notifications']);
        add_action('duzz_display_notification', [$this, 'display_custom_notification']);
        add_action('wp_enqueue_scripts', [$this, 'duzz_enqueue_frontend_assets']);
        add_action('wp_ajax_mark_message_as_trashed', [$this, 'ajax_mark_message_as_trashed']);
    }

    public function display_notifications() {
        error_log("display_notifications was called");
        $this->fetch_and_display_notification('notice notice-info is-dismissible');
    }

    public function display_custom_notification() {
        $this->fetch_and_display_notification('duzz-custom-notification dismiss-duzz-notification');
    }

    private function fetch_and_display_notification($css_class) {
        $api_url = self::API_ENDPOINT_URL;
        $response = wp_remote_get(add_query_arg('api_key', self::API_KEY, $api_url));

        if (is_wp_error($response)) {
            error_log('API error: ' . $response->get_error_message()); // log the actual error
            return;
        }

        $response_body = wp_remote_retrieve_body($response);
        error_log('API Response: ' . $response_body); // Log the API response

        $message_obj = json_decode($response_body);

        if (!$message_obj) {
            error_log('Error decoding API response');
            return;
        }

        $trashed_messages = get_option('duzz_trashed_messages', []);

        if (isset($message_obj->message_text) && !empty(trim($message_obj->message_text))) {
            if (in_array($message_obj->message_id, $trashed_messages)) {
                error_log('Message with ID ' . $message_obj->message_id . ' is trashed.');
                return;
            }

            $message = make_clickable(wpautop($message_obj->message_text));
            $message = preg_replace('/<a /', '<a target="_blank" ', $message);

            echo "<div class='{$css_class}' data-message-id='{$message_obj->message_id}'>";
            echo '<div class="duzz-message-notification-container"><p>' . $message . '</p></div>';
            error_log('Displaying message: ' . $message);

            if (strpos($css_class, 'dismiss-duzz-notification') !== false) {
                echo '<span class="duzz-dismiss-icon" style="float:right; cursor:pointer;">&times;</span>';
            }

            echo '</div>';
        } else {
            error_log('No valid message_text in the API response or message_text is empty.');
        }
    }


    public function duzz_enqueue_frontend_assets() {
        if (!is_admin()) {
            wp_enqueue_script('jquery');
            wp_add_inline_script('jquery', '
                var ajaxurl = "' . admin_url('admin-ajax.php') . '";
                jQuery(document).ready(function($) {
                    $(document).on("click", ".duzz-dismiss-icon", function() {
                        var messageId = $(this).parent().data("message-id");
                        $.post(ajaxurl, {
                            action: "mark_message_as_trashed",
                            message_id: messageId
                        }, function(response) {
                            if (response.success) {
                                $("[data-message-id=" + messageId + "]").fadeOut();
                            }
                        });
                    });
                });
            ');
        }
    }

    public function ajax_mark_message_as_trashed() {
        if (isset($_POST['message_id'])) {
            $trashed_messages = get_option('duzz_trashed_messages', []);
            $trashed_messages[] = sanitize_text_field($_POST['message_id']);
            update_option('duzz_trashed_messages', $trashed_messages);

            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}

