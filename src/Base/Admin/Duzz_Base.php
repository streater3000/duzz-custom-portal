<?php
/**
 * Register the post types and taxonomies.
 */

namespace Duzz\Base\Admin;

class Duzz_Base {

	/**
	 * Autoload method
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'duzz_register_project_post_type' ), 20 );
		add_action( 'init', array( $this, 'duzz_register_company_post_type' ), 20 );
		add_action( 'init', array( $this, 'duzz_register_team_post_type' ), 20 );
		add_action( 'init', array( $this, 'duzz_register_payment_post_type' ), 20 ); 
	}


    public function duzz_register_payment_post_type() {
        $args = array(
            'labels' => array(
                'name' => __( 'Payments' ),
                'singular_name' => __( 'Payment' ),
            ),
            'public' => false,
            'show_ui' => true,
            'supports' => array( 'title', 'custom-fields' ),
            'taxonomies' => array(),
            'hierarchical' => false,
            'show_in_menu' => false,
        );

        $args = apply_filters( 'duzz_payment_post_type_args', $args );

        register_post_type( 'payment', $args );
    }

	/**
	 * Project post type.
	 */
	public function duzz_register_project_post_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Projects List' ),
				'singular_name' => __( 'Project' ),
			),
			'public' => false,
			'show_ui' => true,
			'supports' => array( 'title', 'custom-fields', 'comments' ),
			'taxonomies' => array(),
			'hierarchical' => false,
			'show_in_menu' => false,
		);

		$args = apply_filters( 'duzz_project_post_type_args', $args ); // Filterable arguments array

		register_post_type( 'project', $args );
	}

	/**
	 * Company post type.
	 */
	public function duzz_register_company_post_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Companies' ),
				'singular_name' => __( 'Company' ),
			),
			'public' => false,
			'show_ui' => true,
			'supports' => array( 'title', 'custom-fields' ),
			'taxonomies' => array(),
			'hierarchical' => false,
			'show_in_menu' => false,
		);

		$args = apply_filters( 'duzz_company_post_type_args', $args ); // Filterable arguments array

		register_post_type( 'company', $args );
	}

	/**
	 * Team post type.
	 */
	public function duzz_register_team_post_type() {
		$args = array(
			'labels' => array(
				'name' => __( 'Teams' ),
				'singular_name' => __( 'Team' ),
			),
			'public' => false,
			'show_ui' => true,
			'supports' => array( 'title', 'custom-fields' ),
			'taxonomies' => array(),
			'hierarchical' => false,
			'show_in_menu' => false,
		);

		$args = apply_filters( 'duzz_team_post_type_args', $args ); // Filterable arguments array

		register_post_type( 'team', $args );
	}
}

$base = new Duzz_Base();
