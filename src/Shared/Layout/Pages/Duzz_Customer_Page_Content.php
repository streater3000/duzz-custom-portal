<?php

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Shared\Actions\Duzz_Display_Data;

class Duzz_Customer_Page_Content extends Duzz_Project_Constructor {

    private static $instance = null;

    public static function duzz_get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        parent::__construct();
    }

    public function duzz_addTopUpdates() {
        parent::duzz_addTopUpdates();

        // Replacing Div with Duzz_Return_HTML
        $this->welcomeBackMobile = new Duzz_Return_HTML('div', array('class' => 'welcome-back-mobile'));
        
        // Replacing TextNode with Duzz_Return_HTML with empty tag
        $this->welcomeBackMobile->duzz_addChild('', [], 'Hi&nbsp;');

        // Using the Duzz_Display_Data class here instead of the shortcode
        $displayData = new Duzz_Display_Data('customer_first_name', 'there');
        $dataOutput = $displayData->duzz_get_display_data();
        
        // Again, using Duzz_Return_HTML with an empty tag to replace TextNode
        $this->welcomeBackMobile->duzz_addChild('', [], $dataOutput);
        $this->welcomeBackMobile->duzz_addChild('', [], '!');

        $this->topUpdates->duzz_addChild($this->welcomeBackMobile);
    }
}
