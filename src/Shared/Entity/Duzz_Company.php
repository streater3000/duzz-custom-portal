<?php
/**
 * The Company entity class.
 */

namespace Duzz\Shared\Entity;

class Duzz_Company {

	/**
	 * Autoload method
	 */
	public function __construct() {
	}

	/**
	 * Add a Company
	 */
	static function duzz_add( $args ) {
		return wp_insert_post( $args );
	}

	/**
	 * Get all Companies
	 */
	static function duzz_get_all() {
		$args = array(
        'post_type' => 'company',
        'posts_per_page' => -1
    );
		$companies = get_posts( $args );
		return $companies;
	}

}
