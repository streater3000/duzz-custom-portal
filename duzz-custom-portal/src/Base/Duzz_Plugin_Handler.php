<?php

namespace Duzz\Base;

use Duzz\Base\Admin\Duzz_Admin;
use Duzz\Base\Admin\Duzz_Base;
use Duzz\Base\Menu\Duzz_Menu;
use Duzz\Base\Duzz_Activation;
use Duzz\Base\Admin\Duzz_Admin_Menu_Items;
use Duzz\Shared\Layout\Duzz_Layout;
use Duzz\Shared\Layout\CSS\Duzz_Class_Factory;
use Duzz\Base\Admin\Factory\Duzz_User;
use Duzz\Base\Duzz_Caps;
use Duzz\Core\Duzz_Processes;
use Duzz\Shared\Actions\Duzz_Status_Feed;
use Duzz\Core\Duzz_Redirect;
use Duzz\Core\Duzz_Enqueue;
use Duzz\Core\Duzz_Edit_Wordpress;
use Duzz\Shared\Actions\Duzz_Tribute;
use Duzz\Shared\Layout\Script\Duzz_Select2_Script;
use Duzz\Shared\Actions\Duzz_Field_Sync;
use Duzz\Shared\Actions\Duzz_Emails;
use Duzz\Base\Stripe\Duzz_Stripe_Checkout;
use Duzz\Base\Stripe\Duzz_Stripe_Enqueue;
use Duzz\Base\Admin\Duzz_ACF_Sync;

class Duzz_Plugin_Handler
{
    public function __construct($plugin_file)
    {

        // Instantiate required classes
        new Duzz_Processes();
        new Duzz_Base();
        new Duzz_Class_Factory();
        Duzz_Admin::getInstance($plugin_file, 'duzz-client-portal');
        new Duzz_Menu();
        new Duzz_Activation($plugin_file);
        new Duzz_Layout();
        new Duzz_User();
         Duzz_Caps::init();
        Duzz_Admin_Menu_Items::create_duzz_forms_connectors();
        new Duzz_Status_Feed();
        new Duzz_Redirect();
        new Duzz_Edit_Wordpress();
        new Duzz_Tribute();
        new Duzz_Select2_Script();
        new Duzz_Enqueue();
        new Duzz_Field_Sync();
        new Duzz_Emails();
        new Duzz_ACF_Sync();
        add_action('init', [$this, 'initialize_stripe_create']);

    }
       public function initialize_stripe_create() {
        new Duzz_Stripe_Checkout();
        new Duzz_Stripe_Enqueue();
    }
}