<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions\Duzz_Format_Label;
use Duzz\Core\Duzz_Helpers;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;  // Import the Duzz_Return_HTML class

class Duzz_Labeled_Data {
    public $selected_fields;
    public $fallback;

public function __construct($selected_fields, $fallback = 'TBD') {
    $project_id = get_query_var('project_id', false);
    
    if (!$project_id) {
        return;  // Handle the project when project_id is not available.
    }
    
    $this->project_id = absint($project_id);
    $this->selected_fields = $selected_fields;
    $this->fallback = $fallback;
}

public function render_all_fields() {
    $container = new Duzz_Return_HTML('div', ['class' => 'labeled-data-container']);
    
    foreach ($this->selected_fields as $field_name => $field_key) {
        $this->field_name = $field_name;
        $this->field_key = $field_key;
        $this->title = Duzz_Format_Label::format($field_key);
        $container->addChild($this->render()); // No need to specify div and attributes
    }
    
    return $container;
}


    public function render() {
        // Render title
        $title_div = new Duzz_Return_HTML('div', ['class' => 'account-titles']);
        $title_div->addChild('div', [], $this->title);

        // Render metadata field output
        $raw_value = Duzz_Helpers::duzz_get_field( $this->field_key, $this->project_id) ?: $this->fallback;
        $field_div = new Duzz_Return_HTML('div', ['class' => 'customer-email-shortcode left-account']);
        $field_div->addChild('div', [], $raw_value);

        // Combine title and metadata field output
        $container = new Duzz_Return_HTML('div', ['class' => 'column-account']);
        $container->addChild('div', ['class' => 'account-titles'], $title_div->render());
        $container->addChild('div', ['class' => 'customer-email-shortcode left-account'], $field_div->render());

        // Wrap container in another div
        $DivContainer = new Duzz_Return_HTML('div', ['class' => 'column-account min-width-flex']);
        $DivContainer->addChild('div', ['class' => 'column-account'], $container->render());

        return $DivContainer;
    }
}
