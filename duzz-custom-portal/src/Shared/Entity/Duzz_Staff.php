<?php

namespace Duzz\Shared\Entity;

use Duzz\Core\Duzz_Helpers;

class Duzz_Staff {

	public $id;
	public $user;
	public $meta;

	/**
	 * Autoload method
	 */
	public function __construct( $id ) {
		$this->id = $id;
		$this->user = get_user_by( 'id', $id );
		$this->meta = get_user_meta( $id );
	}

	/**
	 * Get the user's role.
	 */
	public function get_role() {
		return $this->user->roles[0];
	}

	/**
	 * Get all this Team members's projects.
	 */
	public function get_projects() {
		$args = [
				'post_type'  => 'project',
				'meta_key'   => 'staff_id',
				'meta_value' => $this->id,
		];
		return get_posts( $args );
	}

	/**
	* Archive team member and remove projects from them.
	*/
	static function archive( $id ) {
		Duzz_Helpers::duzz_update_field( Duzz_Staff_Keys::$archived, 1, 'user_' . $id );
		Duzz_Helpers::duzz_update_field( Duzz_Staff_Keys::$archived_by, get_current_user_id(), 'user_' . $id );

		$project_args = [
			'post_type'  => 'project',
			'meta_query' => array(
					array(
							'key'     => 'staff_id',
							'value'   => $id,
							'compare' => '=',
					),
			),
		];

		$projects = get_posts( $project_args );

		foreach( $projects as $project ) {
			Duzz_Helpers::duzz_update_field( 'staff_id', 0, $project->ID );
		}
	}

	/**
	* Restore the user.
	*/
	static function unarchive( $id ) {
		update_field( Duzz_Staff_Keys::$archived, 0, 'user_' . $id );
		update_field( Duzz_Staff_Keys::$archived_by, '', 'user_' . $id );
	}

	/**
	* Permenently delete the user.
	*/
	static function delete( $id ) {
		require_once( ABSPATH . 'wp-admin/includes/user.php' );
		return wp_delete_user( $id );
	}

	/**
	* Get all staff.
	*/
	static function get_all( $custom_args = array() ) {
		$default_args = array(
				'posts_per_page' => -1,
				'role__in' => ['duzz_admin'],
				'meta_query' => array(
					array(
							'key'     => 'archived',
							'value'     => 0,
							'compare' => '=',
					),
				),
		);
		$args = wp_parse_args( $custom_args, $default_args );
		$team_members = get_users( $args );
		return $team_members;
	}
}
