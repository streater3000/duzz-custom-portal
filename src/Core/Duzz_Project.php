<?php

namespace Duzz\Core;


use Duzz\Utils\Duzz_Keys;
/**
 * The Project entity class.
 */

class Duzz_Project {

	public $id;

	/**
	 * Autoload method
	 */
	public function __construct( $id ) {
		$this->id = $id;
	}

	/**
	 * Check if a Project has a customer linked to it.
	 */
	public function has_customer_id() {

		return duzz_get_field( 'customer_id', $this->id ) ?: false;
	}

	/**
	 * Archive a Project.
	 */
	static function archive( $id ) {
		return duzz_update_field( 'archived', 1, $id );
	}

	public function user_can_view( $user ) {

		// If the Customer is viewing their own Project.
		if ( $user === $this->customer_id ) {
			return true;
		}

		// If the above fails the Customer must be trying to view someone elses Project.
		if ( $user->role === 'duzz_customer' ) {
			return false;
		}

		// If the staff owns this Project.
		if ( $staff === $this->staff_id ) {
			return true;
		}

		// If the staff doesn't own this Project.
		// TODO add a condition if they are on a team so can view the Project
		if ( $user->role === 'duzz_staff' ) {
			return false;
		}

		$current_user = DUZZ_Staff( get_current_user_id() );
		$project_owner = DUZZ_Staff( $this->staff_id );

		if ( $user->role === 'duzz_subadmin' ) {
			if ( $current_user->get_company() === $project_owner->duzz_get_company() ) {
				return true;
			}
		}

		if ( $user->role === 'duzz_admin' ) {
			return true;
		}

		return false;
	}
}
