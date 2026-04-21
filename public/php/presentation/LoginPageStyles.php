<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Injects branded login page styles at wp_head priority 999 so they
 * load after MemberPress's own inline styles and win the cascade.
 *
 * The actual CSS lives in public/css/login.css — keeping presentation
 * markup out of PHP for cleanliness and easier maintenance.
 */
class LoginPageStyles {

    public function __construct() {
        add_action( 'wp_head', [ $this, 'print_styles' ], 999 );
    }

    public function print_styles(): void {

        if ( ! isset( $_SERVER['REQUEST_URI'] ) ) {
            return;
        }

        if ( strpos( $_SERVER['REQUEST_URI'], '/login' ) === false ) {
            return;
        }

        $cssPath = SMPLFY_NAME_PLUGIN_DIR . 'public/css/login.css';

        if ( ! file_exists( $cssPath ) ) {
            return;
        }

        echo "\n<!-- Sheikh Khalifa login styles -->\n";
        echo '<style>' . file_get_contents( $cssPath ) . '</style>' . "\n";
    }
}
