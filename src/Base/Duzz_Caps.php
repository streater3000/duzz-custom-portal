<?php

namespace Duzz\Base;

class Duzz_Caps {

    private $postTypesInstance;

    private function __construct() {
        $this->postTypesInstance = new Duzz_Post_Types();
        $this->add_roles();
        add_action('init', array($this, 'assign_caps'), 11);
    }

    public static function init() {
        new self();  
    }

    public static function add_roles() {
        add_role(
            'duzz_bot',
            __( 'Duzz Bot' ),
            array(
                'read'         => false,  // true allows this capability
                'edit_posts'   => false,
                'delete_posts' => false, // Use false to explicitly deny
            )
        );

        add_role(
            'duzz_admin',
            __( 'Duzz Admin' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => true,  // true allows this capability, needed for Posts menu
                'delete_posts' => false, // Use false to explicitly deny
                'publish_posts' => true, // true allows this capability
                'edit_others_posts' => true, // true allows this capability
                'upload_files' => true, // true allows this capability, needed for Media menu
            )
        );
    }

    private function create_caps($role, $defaultVisibility, $customCaps = []) {
        $postTypeNames = $this->postTypesInstance->getAllPostTypeNames();
        $capabilityPrefixes = ['add_', 'view_', 'edit_', 'archive_'];

        $customVisibility = !$defaultVisibility;  // Automatically set the opposite value for custom capabilities

        foreach ($postTypeNames as $name) {
            foreach ($capabilityPrefixes as $prefix) {
                $capName = $prefix . $name;
                $visibility = in_array($capName, $customCaps) ? $customVisibility : $defaultVisibility;
                $role->add_cap($capName, $visibility);
            }
        }
    }

    public function assign_caps() {
        $admin = get_role('duzz_admin');
        $this->create_caps($admin, true, [
            'add_company',
            'archive_company'
        ]);
    }
}
