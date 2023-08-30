<?php

namespace Duzz\Core;

use Duzz\Utils\Duzz_Keys;
use Duzz\Duzz\Shared\Actions\Duzz_Validate_ID; 

class Duzz_WPforms{

/**
 * Process the custom Smart Tag.
 *
 * @link   https://wpforms.com/developers/how-to-create-more-user-smart-tags/
 */
function duzz_process_smart_tag( $content, $tag ) {

	$project_id = absint(sanitize_text_field($_GET['project_id'] ?? 0));

	if (!$project_id || !Duzz_Validate_ID::validate($project_id)) {
		return $content;  // return original content if invalid ID
	}

	$project_id = get_post( $project_id );

	// Only run if it is our desired tag.
	if ( 'duzz_project_id' === $tag ) {
		$project_id = absint( $project_id );
		$content = str_replace( '{duzz_project_id}', $project_id, $content );
	}

	if ( 'duzz_customer_first_name' === $tag ) {
		$duzz_customer_first_name = duzz_get_field('customer_first_name', $project_id );
		$content = str_replace( '{duzz_customer_first_name}', $duzz_customer_first_name, $content );
	}

	if ( 'duzz_customer_last_name' === $tag ) {
		$duzz_customer_last_name = duzz_get_field('customer_last_name', $project_id );
		$content = str_replace( '{duzz_customer_last_name}', $duzz_customer_last_name, $content );
	}

	if ( 'duzz_customer_email' === $tag ) {
		$duzz_customer_email = duzz_get_field('customer_email', $project_id );
		$content = str_replace( '{duzz_customer_email}', $duzz_customer_email, $content );
	}

	if ( 'duzz_customer_phone' === $tag ) {
		$duzz_customer_phone = duzz_get_field('customer_phone', $project_id );
		$content = str_replace( '{duzz_customer_phone}', $duzz_customer_phone, $content );
	}

	if ( 'duzz_carrier' === $tag ) {
		$duzz_carrier = duzz_get_field( 'carrier', $project_id );
		$content = str_replace( '{duzz_carrier}', $duzz_carrier, $content );
	}

	if ( 'duzz_policy_number' === $tag ) {
		$duzz_policy_number = duzz_get_field( 'policy_number', $project_id );
		$content = str_replace( '{duzz_policy_number}', $duzz_policy_number, $content );
	}

	return $content;
}
add_filter( 'wpforms_smart_tag_process', 'duzz_process_smart_tag', 10, 2 );

/**
 * Process the custom Smart Tag.
 *
 * @link   https://wpforms.com/developers/how-to-create-more-user-smart-tags/
 */
function duzz_process_staff_smart_tags( $content, $tag ) {

	$staff_id = absint(sanitize_text_field($_GET['staff_id'] ?? 0));

	if ($staff_id <= 0) {
		return $content;  // return original content if invalid ID
	}

	// Only run if it is our desired tag.
	if ( 'duzz_staff_id' === $tag ) {
			$duzz_staff_id = absint( $staff_id );
			$content = str_replace( '{duzz_staff_id}', $duzz_staff_id, $content );
	}

	if ( 'duzz_staff_email' === $tag ) {
			$duzz_staff_email = get_the_author_meta( 'user_email', $staff_id );
			$content = str_replace( '{duzz_staff_email}', $duzz_staff_email, $content );
	}

	if ( 'duzz_staff_name' === $tag ) {
			$duzz_staff_name = get_the_author_meta( 'display_name', $staff_id );
			$content = str_replace( '{duzz_staff_name}', $duzz_staff_name, $content );
	}

	if ( 'duzz_staff_phone' === $tag ) {
			$duzz_staff_phone = get_the_author_meta( 'user_phone', $staff_id );
			$content = str_replace( '{duzz_staff_phone}', $duzz_staff_phone, $content );
	}

	return $content;
}
add_filter( 'wpforms_smart_tag_process', 'duzz_process_staff_smart_tags', 10, 2 );

}