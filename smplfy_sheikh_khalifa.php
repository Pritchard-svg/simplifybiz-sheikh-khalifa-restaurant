<?php
namespace SMPLFY\sheikh_khalifa;

ini_set( 'display_errors', 0 );
ini_set( 'log_errors', 1 );
ini_set( 'error_log', __DIR__ . '/debug-error.txt' );
error_reporting( E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED );

/**
 * Plugin Name: Sheikh Khalifa Restaurant
 * Version: 1.0
 * Description: Custom plugin for Sheikh Khalifa Restaurant — nav control, role visibility, table availability, Google Chat notifications and debug logging.
 * Author: Pritchard Zimondi
 * Author URI: https://simplifybiz.com/
 * Requires PHP: 7.4
 * Requires Plugins: smplfy-core
 *
 * @package SheikhKhalifa
 * @author Pritchard Zimondi
 * @since 1.0
 */

prevent_external_script_execution();

define( 'SITE_URL', get_site_url() );
define( 'SMPLFY_NAME_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SMPLFY_NAME_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once SMPLFY_NAME_PLUGIN_DIR . 'admin/utilities/smplfy_require_utilities.php';
require_once SMPLFY_NAME_PLUGIN_DIR . 'includes/smplfy_bootstrap.php';

add_action( 'plugins_loaded', 'SMPLFY\sheikh_khalifa\bootstrap_sheikh_khalifa_plugin' );

function prevent_external_script_execution(): void {
    if ( ! function_exists( 'get_option' ) ) {
        header( 'HTTP/1.0 403 Forbidden' );
        die;
    }
}
