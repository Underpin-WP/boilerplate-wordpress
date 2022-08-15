<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

use Plugin_Name_Replace_Me\Core\Base\Base;

// Load the autoloader.
require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );
require_once( plugin_dir_path( __FILE__ ) . 'lib/Core/index.php' );

Base::instance()
    ->set_config( require plugin_dir_path( __FILE__ ) . 'config.php' )
    ->init();