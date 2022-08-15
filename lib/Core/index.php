<?php

/**
 * @noinspection PhpDocMissingThrowsInspection
 * @noinspection PhpUnhandledExceptionInspection
 */
namespace Plugin_Name_Replace_Me;

use DI\DependencyException;
use DI\NotFoundException;
use Plugin_Name_Replace_Me\Core\Base\Base;
use Plugin_Name_Replace_Me\Core\Base\Provider;
use Underpin\Exceptions\Unmet_Requirements;
use Underpin\Loaders\Logger;

/**
 * Public-facing function that provides everything within this plugin.
 *
 * @return Provider|null
 */
function plugin_name_replace_me(): ?Provider {
	try {
		return Base::instance()->get_provider();
	} catch ( DependencyException|NotFoundException $e ) {
		// Log if something went wrong.
		Logger::alert( $e );

		return null;
	} catch ( Unmet_Requirements ) {
		return null;
	}
}