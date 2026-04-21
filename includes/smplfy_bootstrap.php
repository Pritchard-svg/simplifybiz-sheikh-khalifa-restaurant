<?php
/**
 * Loads specified files and all files in specified directories, then initialises dependencies.
 */

namespace SMPLFY\sheikh_khalifa;

function bootstrap_sheikh_khalifa_plugin() {
    require_sheikh_khalifa_dependencies();

    DependencyFactory::create_plugin_dependencies();
}

/**
 * When adding a new directory to the plugin, remember to require it here.
 *
 * @return void
 */
function require_sheikh_khalifa_dependencies() {

    require_file( 'includes/enqueue_scripts.php' );
    require_file( 'admin/DependencyFactory.php' );

    require_directory( 'public/php/types' );
    require_directory( 'public/php/entities' );
    require_directory( 'public/php/repositories' );
    require_directory( 'public/php/usecases' );
    require_directory( 'public/php/adapters' );
    require_directory( 'public/php/presentation' );
}
