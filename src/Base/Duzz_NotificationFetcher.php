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
        add_action('admin_enqueue_scripts', [$this, 'duzz_enqueue_backend_assets']);
        add_action('wp_enqueue_scripts', [$this, 'duzz_enqueue_frontend_assets']);
        add_action('wp_ajax_mark_message_as_trashed', [$this, 'duzz_ajax_mark_message_as_trashed']);
    }

    public function duzz_display_notifications() {
        $this->duzz_fetch_and_display_notification('notice notice-info dismiss-duzz-admin-notification');
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


            if (strpos($css_class, 'dismiss-duzz-notification') !== false) {
                


                echo "<div class='" . esc_attr($css_class) . "' data-message-id='" . esc_attr($message_obj->message_id) . "'>";
                echo '<div class="duzz-message-notification-container"><p>' . wp_kses_post($message) . '</p></div>';
                echo '<span class="duzz-dismiss-icon" style="float:right; cursor:pointer;">&times;</span>';
           
             } else {
                echo "<div class='" . esc_attr($css_class) . "' data-message-id='" . esc_attr($message_obj->message_id) . "'>";
                echo '<div class="duzz-message-notification-container"><p>' . wp_kses_post($message) . '</p></div>';
                echo '<button class="duzz-dismiss-icon" style="float:right; cursor:pointer;"></button>';
        }
            echo '</div>';
        }    
    }


public function duzz_enqueue_frontend_assets() {
    if (!is_admin()) {
        wp_enqueue_script('jquery');

        // Generate nonce and localize it
        $nonce = wp_create_nonce('message_id_action_pass_post_nonce');
        wp_localize_script('jquery', 'duzzData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'duzzTrashPassPostNonce' => $nonce
        ));

        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                $(document).on("click", ".duzz-dismiss-icon", function() {
                    var messageId = $(this).parent().data("message-id");

                    $.post(duzzData.ajaxurl, {
                        action: "mark_message_as_trashed",
                        message_id: messageId,
                        "message-id-pass-post-nonce": duzzData.duzzTrashPassPostNonce
                    }, function(response) {
                        if (response.success) {
                            $("[data-message-id=" + messageId + "]").fadeOut();
                        } else {
                            
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        
                    });
                });
            });
        ');
    }
}


public function duzz_enqueue_backend_assets() {
    if (is_admin()) { // Check if it's admin
        wp_enqueue_script('jquery');

        // Generate nonce and localize it
        $nonce = wp_create_nonce('message_id_action_pass_post_nonce');
        wp_localize_script('jquery', 'duzzData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'duzzTrashPassPostNonce' => $nonce
        ));

        wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {
                console.log("Script loaded in admin");
                $(document).on("click", ".duzz-dismiss-icon", function() {
                    var messageId = $(this).parent().data("message-id");
                    console.log("Dismissing message with ID:", messageId);
                    $.post(duzzData.ajaxurl, {
                        action: "mark_message_as_trashed",
                        message_id: messageId,
                        "message-id-pass-post-nonce": duzzData.duzzTrashPassPostNonce
                    }, function(response) {
                        if (response.success) {
                            console.log("Message trashed");
                            $("[data-message-id=" + messageId + "]").fadeOut();
                        } else {
                            console.log("Failed to trash message");
                        }
                    }).fail(function(jqXHR, textStatus, errorThrown) {
                        console.error("Ajax Error:", textStatus, errorThrown);
                    });
                });
            });
        ');
    }
}

public function duzz_ajax_mark_message_as_trashed() {
    check_ajax_referer('message_id_action_pass_post_nonce', 'message-id-pass-post-nonce');

    $message_id = $this->data_passer->duzz_retrieve('POST', 'message_id');

    if ($message_id) {
        $trashed_messages = get_option('duzz_trashed_messages', []);
        $trashed_messages[] = sanitize_text_field($message_id); 
        $updated = update_option('duzz_trashed_messages', $trashed_messages);

        if ($updated) {
            wp_send_json_success();
        } else {
            wp_send_json_error(['message' => 'Failed to update the trashed messages in database.']);
        }
    } else {
        wp_send_json_error(['message' => 'Invalid message ID.']);
    }
}


}

