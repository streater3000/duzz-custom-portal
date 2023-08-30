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
	public function get_projects() {
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
	public function has_one_project() {
		$projects = $this->get_projects();
		if( count( $projects ) === 1 ) {
			return true;
		}
		return false;
	}

	/**
	 * Retrieve a single project.
	 */
	public function get_project() {
		$projects = $this->get_projects();
		return $projects[0];
	}

	/**
	* Archive a Customer.
	*/
	static function archive( $id ) {
		duzz_update_field( $archived, 1, $id );
	}

}
