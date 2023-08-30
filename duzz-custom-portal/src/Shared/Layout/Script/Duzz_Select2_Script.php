<?php

namespace Duzz\Shared\Layout\Script;

use Duzz\Shared\Layout\HTML\Duzz_Script;

class Duzz_Select2_Script {

    private static $duzz_select2_used = false;
    
    public function __construct() {
        add_action('admin_footer', [$this, 'render_select2_javascript']);
    }

    public static function select2_used() {
        self::$duzz_select2_used = true;
    }

    public function enqueue_select2_script($css_class = 'custom-dropdown') {
        
        $jsContent_one = "
        jQuery(document).ready(function($) {
            function formatOption(option) {
                if (!option.id) {
                    return option.text;
                }
                var \$option = $(
        ";

        $jsContent_two = "
                );
                return \$option;
            };

            $('." . esc_js($css_class) . "').each(function() {
                const maxSelections = $(this).data('max-selections');
                $(this).select2({
                    maximumSelectionLength: maxSelections,
                    placeholder: 'Select up to ' + maxSelections + ' columns',
                    closeOnSelect: false,
                    templateResult: formatOption,
                    templateSelection: formatOption
                });
            });
        });";

        $htmlPart = "'<span class=\"select-title-margin\">' + option.text + '</span>'";

        $scriptContent = $jsContent_one . $htmlPart . $jsContent_two;

    $script = new Duzz_Script($scriptContent, true);  // 'true' allows HTML content
    $script->render();
    }
    
    public function render_select2_javascript() {
        if (self::$duzz_select2_used) {
            $this->enqueue_select2_script();
        }
    }
}


