<?php

namespace Duzz\Shared\Actions;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_Popup {

    public function manage_project_popup() {
        
        $project_id = absint(sanitize_text_field($_GET['project_id'] ?? 0));

        if (!$project_id || !Duzz_Validate_ID::validate($project_id)) {
            return;
        }

        $user = wp_get_current_user();

        if (!array_intersect(['administrator', 'duzz_admin'], (array) $user->roles)) {
            return;
        }

        $popup_invite = new Duzz_Popup_Invite();

        $modalContent = $popup_invite->invite_customer_button_shortcode() . (new Duzz_Return_HTML('br'))->render();

        // Replace the Div usage
        $modalDiv = new Duzz_Return_HTML('div', ['class' => 'modal', 'id' => 'mylightbox']);
        $modalDiv->setContent($modalContent);

        // Replace the Link usage
        $link = new Duzz_Return_HTML('a', ['class' => 'edit-button-position', 'href' => '#', 'data-featherlight' => '#mylightbox']);
        $link->setContent('Invite');

        $html = $modalDiv->render() . $link->render();

        return $html;
    }
}

