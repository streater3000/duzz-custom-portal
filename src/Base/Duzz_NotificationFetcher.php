<?php

namespace Duzz\Base;

use Duzz\Core\Duzz_Data_Passer;

class Duzz_NotificationFetcher {
    const API_ENDPOINT_URL = 'https://duzz.io/wp-json/duzz_new/v1/updated_message/';
    const API_KEY = 'duzz_new_api_notification';
        protected $data_passer;

    public function __construct() {
         $this->data_passer = new Duzz_Data_Passer();
        add_action('in_admin_header', [$this, 'duzz_display_notifications']);
        add_action('duzz_display_notification', [$this, 'duzz_display_custom_notification']);
        add_action('wp_enqueue_scripts', [$this, 'duzz_enqueue_frontend_assets']);
        add_action('wp_ajax_mark_message_as_trashed', [$this, 'duzz_ajax_mark_message_as_trashed']);
    }

    public function duzz_display_notifications() {
        $this->duzz_fetch_and_display_notification('notice notice-info is-dismissible');
    }

    public function duzz_display_custom_notification() {
        $this->duzz_fetch_and_display_notification('duzz-custom-notification dismiss-duzz-notification');
    }

    private function duzz_fetch_and_display_notification($css_class) {
        $api_url = self::API_ENDPOINT_URL;
        $response = wp_remote_get(add_query_arg('api_key', self::API_KEY, $api_url));

        if (is_wp_error($response)) {
            return;
        }

        $response_body = wp_remote_retrieve_body($response);


        $message_obj = json_decode($response_body);

        if (!$message_obj) {
            return;
        }

        $trashed_messages = get_option('duzz_trashed_messages', []);

        if (isset($message_obj->message_text) && !empty(trim($message_obj->message_text))) {
            if (in_array($message_obj->message_id, $trashed_messages)) {
                return;
            }

            $message = make_clickable(wpautop($message_obj->message_text));
            $message = preg_replace('/<a /', '<a target="_blank" ', $message);

            echo "<div class='" . esc_attr($css_class) . "' data-message-id='" . esc_attr($message_obj->message_id) . "'>";
            echo '<div class="duzz-message-notification-container"><p>' . wp_kses_post($message) . '</p></div>';

            if (strpos($css_class, 'dismiss-duzz-notification') !== false) {
                echo '<span class="duzz-dismiss-icon" style="float:right; cursor:pointer;">&times;</span>';
            }

            echo '</div>';
        } else {

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

 public function duzz_ajax_mark_message_as_trashed() {
        $message_id = $this->data_passer->duzz_retrieve('POST', 'message_id'); // Replaced $_POST with retrieve method
        
        if ($message_id) {
            $trashed_messages = get_option('duzz_trashed_messages', []);
            $trashed_messages[] = sanitize_text_field($message_id); // $message_id is already sanitized, but included this for extra precaution.
            update_option('duzz_trashed_messages', $trashed_messages);
            
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }
}

