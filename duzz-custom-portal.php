<?php
/**
 * Plugin Name: Duzz Custom Portal
 * Plugin URI: https://duzz.io
 * Description: A customizable Wordpress customer portal plugin
 * Version: 1.0.72
 * Author: Streater Kelley
 * Author URI: https://duzz.io/about-us/
 * Text Domain: duzz-custom-portal
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Duzz
 */

namespace Duzz;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants.
define('DUZZ_PLUGIN_VERSION', '1.0.72');
define('DUZZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DUZZ_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DUZZ_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DUZZ_PLUGIN_BASENAME', plugin_basename(__FILE__));


// Load Composer autoloader.
require_once(DUZZ_PLUGIN_DIR . 'vendor/autoload.php');
require_once(ABSPATH . 'wp-load.php');



use Duzz\Base\Duzz_Plugin_Handler;
$plugin_handler = new Duzz_Plugin_Handler(__FILE__);


remove_filter('comment_text', 'wpautop');

