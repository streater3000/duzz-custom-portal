<?php

namespace Duzz\Shared\Entity;
/**
 * Accessing meta values by their unique field key is more consistent than using the field name.
 * But they are not as human-readable. So this class provides a way to use the human readable name
 * but get the benefits of using the field key.
 */
class Duzz_Staff_Keys {
	public static $first_name  = 'field_61ee968d40869';
	public static $last_name   = 'field_61ee968d408a4';
	public static $company_id  = 'field_61ee96af3b991';
	public static $team_id     = 'field_6216a190044b0';
	public static $position    = 'field_61ee972c3b992';
	public static $address1    = 'field_61ee968d408dd';
	public static $address2    = 'field_61ee968d40916';
	public static $city        = 'field_61ee968d40950';
	public static $state       = 'field_61ee968d40989';
	public static $zip         = 'field_61ee968d409c2';
	public static $phone       = 'field_61ee968d409fb';
	public static $archived    = 'field_620e5bc710271';
	public static $archived_by = 'field_621cdaafe34b6';
	public static $notes       = 'field_621cc301b0850';
	public static $first_user  = 'field_621f7b1a17a54';
}
