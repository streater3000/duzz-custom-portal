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

    public function render_staff_form() {
        $form_id_staff = Duzz_Get_Data::get_form_id('admin_form_id_field_data', 'form_id');
        $shortcode_node = new HTML\Duzz_ShortcodeNode('[wpforms id="' . $form_id_staff . '"]');
        $this->container->addChild('', [], $shortcode_node);
    }
    
    public function render_customer_form() {
        $form_id_customer = Duzz_Get_Data::get_form_id('client_form_id_field_data', 'form_id');
        $shortcode_node = new HTML\Duzz_ShortcodeNode('[wpforms id="' . $form_id_customer . '"]');
        $this->container->addChild('', [], $shortcode_node);
    }
}
