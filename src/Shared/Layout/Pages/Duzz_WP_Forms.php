<?php

namespace Duzz\Shared\Layout\Pages;

use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Layout\HTML\Duzz_ShortcodeNode;
use Duzz\Shared\Layout\HTML;

class Duzz_WP_Forms {
    public $container;

    public function __construct() {
        $this->container = new HTML\Duzz_Return_HTML('div', array('class' => 'wp-forms-container'));
    }

    public function duzz_render_staff_form() {
        $form_id_staff = Duzz_Get_Data::duzz_get_form_id('wp_forms_admin_admin_form_id_field_data', 'form_id');
        $shortcode_node = new HTML\Duzz_ShortcodeNode('[wpforms id="' . $form_id_staff . '"]');
        $this->container->duzz_addChild('', [], $shortcode_node);
    }
    
    public function duzz_render_customer_form() {
        $form_id_customer = Duzz_Get_Data::duzz_get_form_id('wp_forms_client_client_form_id_field_data', 'form_id');
        $shortcode_node = new HTML\Duzz_ShortcodeNode('[wpforms id="' . $form_id_customer . '"]');
        $this->container->duzz_addChild('', [], $shortcode_node);
    }
}
