<?php


namespace Duzz\Shared\Entity;

use Duzz\Core\Duzz_Helpers;

class Duzz_Customer {

	public $id;
	public $user;
	public $roles;

	/**
	 * Autoload method
	 */
	public function __construct( $id ) {
		$this->id = $id;
		$this->user = get_user_by( 'id', $id );
		$this->roles = $this->user->roles;
	}

	/**
	 * Get all this Customer's projects.
	 */
	public function duzz_get_projects() {
		$args = [
				'post_type'  => 'project',
				'meta_key'   => 'customer_id',
				'meta_value' => $this->id,
		];
		return get_posts( $args );
	}

	/**
	 * Check if the Customer only has one project.
	 */
	public function duzz_has_one_project() {
		$projects = $this->duzz_get_projects();
		if( count( $projects ) === 1 ) {
			return true;
		}
		return false;
	}

	/**
	 * Retrieve a single project.
	 */
	public function duzz_get_project() {
		$projects = $this->duzz_get_projects();
		return $projects[0];
	}

	/**
	* Archive a Customer.
	*/
	static function duzz_archive( $id ) {
		duzz_update_field( $archived, 1, $id );
	}

}
