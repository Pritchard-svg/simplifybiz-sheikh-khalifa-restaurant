<?php

namespace SMPLFY\sheikh_khalifa;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Appends a dynamic Login / Log Out link to the primary nav menu.
 */
class NavMenu {

    public function __construct() {
        add_filter( 'wp_nav_menu_items', [ $this, 'append_login_logout_item' ], 10, 2 );
    }

    public function append_login_logout_item( $items, $args ) {

        if ( $args->theme_location !== 'primary' ) {
            return $items;
        }

        if ( is_user_logged_in() ) {
            $url   = wp_logout_url( home_url() );
            $label = 'Log Out';
        } else {
            $url   = '/login';
            $label = 'Login';
        }

        $items .= '<li class="menu-item menu-item-login"><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';

        return $items;
    }
}
