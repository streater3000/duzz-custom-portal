<?php

namespace Duzz\Base\Admin\Factory;

class Duzz_Admin_Page_Title {
    public function getTitle() {
        return esc_html(get_admin_page_title());
    }

    public function render() {
        echo esc_html($this->getTitle());
    }
}
