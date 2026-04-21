<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds the logged-in user's role(s) as body classes.
 * Enables CSS to show/hide elements based on user role (e.g. .role-manager).
 */
class BodyClass {

    public function __construct() {
        add_filter( 'body_class', [ $this, 'add_role_class' ] );
    }

    public function add_role_class( array $classes ): array {

        if ( ! is_user_logged_in() ) {
            return $classes;
        }

        $user = wp_get_current_user();

        foreach ( (array) $user->roles as $role ) {
            $classes[] = 'role-' . sanitize_html_class( $role );
        }

        return $classes;
    }
}
