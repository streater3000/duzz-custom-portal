<?php

namespace Duzz\Shared\Entity;
/**
 * Accessing meta values by their unique field key is more consistent than using the field name.
 * But they are not as human-readable. So this class provides a way to use the human readable name
 * but get the benefits of using the field key.
 */
class Duzz_Customer_Keys {
	public static $archived   = 'field_620e5bc710271';
	public static $first_name = 'field_61ec161612b56';
	public static $last_name  = 'field_61ec162212b57';
	public static $address1   = 'field_61eb1867ebc8f';
	public static $address2   = 'field_61eb1878ebc90';
	public static $city       = 'field_61eb1880ebc91';
	public static $state      = 'field_61eb1888ebc92';
	public static $zip        = 'field_61eb188cebc93';
	public static $phone      = 'field_61eb1892ebc94';
}
