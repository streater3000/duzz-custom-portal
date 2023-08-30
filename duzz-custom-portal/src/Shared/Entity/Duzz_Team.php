<?php

namespace Duzz\Shared\Entity;

use Duzz\Core\Duzz_Helpers;

class Duzz_Team {

	/**
	 * Autoload method
	 */
	public function __construct() {
	}

	/**
	 * Add a team.
	 */
	static function add( $args ) {
		return wp_insert_post( $args );
	}

	/**
	 * Get all teams.
	 */
	static function get_all( $custom_args = array() ) {
		$default_args = array(
        'post_type' => 'team',
        'posts_per_page' => -1,
				'meta_query' => array(
	        array(
	            'key'     => 'archived',
	            'value'     => 0,
	            'compare' => '=',
	        ),
	    	),
    );
		$args = wp_parse_args( $custom_args, $default_args );
		$teams = get_posts( $args );
		return $teams;
	}

	/**
	* Archive a team.
	*/
	static function archive( $id ) {
		Duzz_Helpers::duzz_update_field( 'field_620e51353811a', 1, $id );
	}

}
