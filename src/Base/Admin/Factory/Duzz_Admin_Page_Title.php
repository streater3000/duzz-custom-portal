<?php

namespace Duzz\Base\Admin\Factory;

class Duzz_Admin_Page_Title {
    public function duzz_getTitle() {
        return esc_html(get_admin_page_title());
    }

    public function duzz_render() {
        echo esc_html($this->duzz_getTitle());
    }
}
