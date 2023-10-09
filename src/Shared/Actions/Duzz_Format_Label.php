<?php 

namespace Duzz\Shared\Actions;

class Duzz_Format_Label {
  
  public static function duzz_format($label) {
    $label = ucwords(str_replace('_', ' ', $label));
    $label = preg_replace('/\bIp\b/', 'IP', $label);
    $label = preg_replace('/\bId\b/', 'ID', $label);
    $label = preg_replace('/\bId\b/', 'API', $label);
    $label = str_ireplace('customer ', '', $label); // Remove the word 'customer' (case-insensitive)
    $label = str_ireplace('project ', '', $label); // Remove the word 'project' (case-insensitive)
    return $label;
  }

 public static function duzz_format_company_name($company_name) {
    // Initial transformation
    $company_name = ucwords(strtolower($company_name));

    // Lists for special cases
    $to_upper = ['llc', 'llp', 'pllc', 'inc', 'corp', 'co', 'ltd', 'dba'];
    $to_lower = ['io'];
    $mixed_case = ['pty ltd']; // just in case you have more in the future

    // Convert to uppercase
    foreach($to_upper as $item) {
      $company_name = preg_replace('/\b' . preg_quote($item, '/') . '\b/i', strtoupper($item), $company_name);
    }

    // Convert to lowercase
    foreach($to_lower as $item) {
      $company_name = preg_replace('/\b' . preg_quote($item, '/') . '\b/i', strtolower($item), $company_name);
    }

    // Convert to mixed case
    foreach($mixed_case as $item) {
      $words = explode(' ', $item);
      $formatted = implode(' ', array_map('ucfirst', array_map('strtolower', $words)));
      $company_name = preg_replace('/\b' . preg_quote($item, '/') . '\b/i', $formatted, $company_name);
    }

    return $company_name;
  }

public static function duzz_format_user_name($user_name) {
    $to_upper = ['iii', 'iv', 'vii'];
    $mixed_case = ['jr', 'sr'];
    $prefixes = ['mr', 'mrs', 'ms', 'dr'];

    $name_suffix = '';
    $first_name = '';
    $last_name_without_suffix = '';

    // Remove all periods
    $user_name = str_replace('.', '', $user_name);
    $original_name_parts = explode(' ', $user_name);

    $name_parts = explode(' ', strtolower($user_name));

    // Remove and skip prefix
    if (in_array($name_parts[0], $prefixes)) {
        $first_name = ucfirst($name_parts[1]);
        array_shift($name_parts); // Remove the prefix for further processing
        array_shift($original_name_parts); // Remove the prefix from the original array too
    } else {
        $first_name = ucfirst($name_parts[0]);
    }

    // Detect and store suffix
    if (in_array(end($name_parts), $to_upper) || in_array(end($name_parts), $mixed_case)) {
        $name_suffix = array_pop($name_parts);
        array_pop($original_name_parts); // Remove the suffix from the original array too

        if (in_array($name_suffix, $to_upper)) {
            $name_suffix = strtoupper($name_suffix);
        } else {
            $name_suffix = ucfirst($name_suffix);
        }
    }

    // Check for name casing in original input
    $original_last_name = end($original_name_parts);
    if (ctype_upper($original_last_name) || ctype_lower($original_last_name)) {
        // Apply ucfirst if the name is all upper or lower case
        $last_name_without_suffix = ucfirst(strtolower(end($name_parts)));
    } else {
        // Preserve the casing if it's a mix
        $last_name_without_suffix = $original_last_name;
    }

    return [
        'first_name' => $first_name,
        'last_name' => trim($last_name_without_suffix . ' ' . $name_suffix),
        'customer_name' => trim($first_name . ' ' . $last_name_without_suffix . ' ' . $name_suffix),
    ];
}

public static function duzz_format_website($input) {
    return strtolower($input);
}

public static function duzz_format_address($address1, $address2, $city, $state, $zip) {
    $formatted_address1 = ucwords(strtolower(trim($address1)));
    $formatted_address2 = ucwords(strtolower(trim($address2)));
    $formatted_city = ucwords(strtolower(trim($city)));
    $formatted_state = strtoupper(trim($state));
    $formatted_zip = trim($zip);

    $address_parts = [
        $formatted_address1, 
        $formatted_address2, 
        $formatted_city, 
        $formatted_state, 
        $formatted_zip
    ];

    $updated_address = implode(', ', array_filter($address_parts));

    return [
        'address1' => $formatted_address1,
        'address2' => $formatted_address2,
        'city' => $formatted_city,
        'state' => $formatted_state,
        'zip' => $formatted_zip,
        'customer_address' => $updated_address
    ];
}

}
