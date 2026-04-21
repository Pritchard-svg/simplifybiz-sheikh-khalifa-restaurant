<?php
/**
 * Enqueue frontend scripts and styles.
 */

namespace SMPLFY\sheikh_khalifa;

function enqueue_sheikh_khalifa_frontend_scripts() {

    wp_enqueue_style(
        'smplfy-sk-frontend-styles',
        SMPLFY_NAME_PLUGIN_URL . 'public/css/frontend.css',
        [],
        '1.0.0'
    );
}

add_action( 'wp_enqueue_scripts', 'SMPLFY\sheikh_khalifa\enqueue_sheikh_khalifa_frontend_scripts' );
