<?php

namespace Duzz\Base\Admin\Factory;

use Duzz\Base\Admin\Factory\Duzz_Admin_Page_Title;

class Duzz_Forms_Connector {

    private $page_name;
    private $sections = [];

    public function __construct(string $page_name) {
        $this->page_name = $page_name;
    }

    public function add_section(array $field_data, string $section_name, $form_type = 'table', $available_selections = null) {
        // create a section object with its own fields
        $section = new Duzz_Admin_Settings_Sections ($field_data, $this->page_name, $section_name, $this, $form_type, $available_selections); 
        // add the section to the array of sections
        $this->sections[$section_name] = $section;
    }

    public function init() {
        foreach ($this->sections as $section) {
            // Initialize every section
            $section->init();
        }
    }

public function output_form(string $group_name, string $page_slug) {
    $titleObj = new Duzz_Admin_Page_Title();
    ?>
    <div class="wrap">
        <h1><?php $titleObj->render(); ?></h1>
        <form action="options.php" method="post">
            <?php
            // Output nonce, action, and option_page fields
            settings_fields($group_name);
            
            // Loop through each section and render the form table
            foreach ($this->sections as $section) {
                $section->render_form_table($page_slug);
            }
            // Output the submit button
            submit_button();
            ?>
        </form>
    </div>
    <?php
}



   public function get_option_group() {
        // Generate the option name based on the form ID
        return 'duzz_forms_' . $this->page_name . '_connector_options';
    }

   public function get_page_slug() {
        // Generate the option name based on the form ID
        return 'duzz_forms_' . $this->page_name . '_connector';
    }
}
