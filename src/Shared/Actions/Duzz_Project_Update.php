<?php

namespace Duzz\Shared\Actions;

use Duzz\Utils\Duzz_Keys;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Core\Duzz_Helpers;

class Duzz_Project_Update {
    public function duzz_updateProject($project_id) {
        $archived = Duzz_Helpers::duzz_get_field('archived', $project_id);
        $project_status = Duzz_Helpers::duzz_get_field('project_status', $project_id);
        $approved_status = Duzz_Helpers::duzz_get_field('approved_status', $project_id);

        // Get the field object for the 'project_status' field
        $field_object = Duzz_Helpers::duzz_get_field_object('project_status', $project_id);

        // Check if 'choices' is a key in $field_object and is an array
if (isset($field_object['choices']) && is_array($field_object['choices'])) {
    // Map the keys to integers and then get the highest number
    $highest_number = max(array_map('intval', array_keys($field_object['choices'])));
} else {
    $highest_number = 10; // Original hardcoded value
}

        $new_project = '';
        switch (true) {

            case ($approved_status == 'Under Review' && $project_status == 1):
            $new_project = 'new-project';
            $new_project_title = 'New';
            Duzz_Helpers::duzz_update_field( 'archived', 0, $project_id );
            break;

            case ($approved_status == 'Under Review'):
            $new_project = 'display-none-project';
            $new_project_title = 'Working';
            Duzz_Helpers::duzz_update_field( 'archived', 0, $project_id );
            break;

            case ($approved_status == 'Yes'):
            $new_project = 'display-none-project';
            $new_project_title = 'Working';
            Duzz_Helpers::duzz_update_field( 'archived', 0, $project_id );
            break;

            case ($project_status > 1 && $project_status < $highest_number && $approved_status !== 'No'):
            $new_project = 'display-none-project';
            $new_project_title = 'Working';
            Duzz_Helpers::duzz_update_field( 'archived', 0, $project_id );
            break;

            case ($project_status == $highest_number && 
                  preg_match('/\b\w{0,3}w\w{0,3}\b/i', $approved_status) && 
                  strpos(strtolower($approved_status), 'lost') === false && 
                  strpos(strtolower($approved_status), 'no') === false):
                $new_project = 'completed-project';
                $new_project_title = 'Completed';
                Duzz_Helpers::duzz_update_field('archived', 1, $project_id);
                break;

            case ($archived == 1 || $approved_status == 'No' || strpos(strtolower($approved_status), 'lost') !== false):
                $new_project = 'archived-project';
                $new_project_title = 'Archived';
                Duzz_Helpers::duzz_update_field('archived', 1, $project_id);
                break;

            default:
                $new_project = 'new-project';
                $new_project_title = 'New';
        }
        return array($new_project, $new_project_title);
    }

public function duzz_project_update_output() {
    // Use get_query_var instead of directly accessing $_GET
        $project_id = get_query_var('project_id', false);   

        // Sanitize and validate the project_id
        $project_id = $project_id ? sanitize_text_field($project_id) : false;
        if (!$project_id || !Duzz_Validate_ID::duzz_validate($project_id)) {
            return '';
        }

        list($new_project, $new_project_title) = $this->duzz_updateProject($project_id);

        // Always ensure that any values you're inserting into your HTML are safely escaped
        $new_project = esc_attr($new_project);
        $new_project_title = esc_html($new_project_title);
        
        $title_style = 'border: 3px solid rgb(255, 255, 255); padding: 8px !important; border-radius: 10px !important;font-size: 20px;';

        $title_html = sprintf('<h2 class="%s" style="%s">%s</h2>', $new_project, $title_style, $new_project_title);

        return $title_html;
    }
}
