<?php

/**
 * @noinspection PhpUnhandledExceptionInspection
 */

use Plugin_Name_Replace_Me\Core\Base\Base;

// Load the autoloader.
require_once( __DIR__ . '/vendor/autoload.php' );
require_once( __DIR__ . '/lib/Core/index.php' );

Base::instance()
    ->set_config( require __DIR__ . '/config.php' )
    ->init();